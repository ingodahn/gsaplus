<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Session;

use Jenssegers\Date\Date;

use App\Code;
use App\Patient;
use App\Therapist;
use App\Helper;
use App\Models;

use UxWeb\SweetAlert\SweetAlert as Alert;
use Prologue\Alerts\Facades\Alert;

use App\SituationSurvey;
use App\Task;



/**
 * Diese Klasse behandelt alle Aufrufe des servers in Zusammenhang mit dem
 * Registrierungs- und Anmeldeprozess.
 *
 * Neben den angegebenen Operationen wird für jedes Signal eine Methode benötigt
 * die den entsprechenden https-Aufruf mit den Signalparametern verarbeitet.
 *
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 15:50:31
 */
class GateController extends Controller
{

	const CODE_INCORRECT = 0;
	const CODE_UNREGISTERED = 1;
	const CODE_REGISTERED = 2;

	// Schrittnr. -> Name
	const PAGE_STRING_MAP = [self::PAGE_START => 'start',
								self::PAGE_WELCOME => 'welcome',
								self::PAGE_AGREEMENT => 'agreement',
								self::PAGE_FORM => 'form'];

	// verfügbare Seiten bzw. Schritte (in der richtigen Reihenfolge)
	const PAGE_START = 0;
	const PAGE_WELCOME = 1;
	const PAGE_AGREEMENT = 2;
	const PAGE_FORM = 3;

	// Der bis dato weiteste Schritt
	// (wenn man mal bei Schritt 3 war, kann man immer zu den Schritten 1-3 oder zum nächsten Schritt (4) gehen)
	const PAGE_SESSION_KEY = 'last_reg_page';
	const CODE_SESSION_KEY = 'code';

	/**
	 * Bestimmt den bisher weitesten Schritt und prüft, ob der gewünschte
	 * Schritt (zu $to) erlaubt ist.
	 *
	 * @param $to
	 * 			der gewünschte Schritt
	 * @return bool
	 * 			true, falls der gewünschte Schritt erlaubt ist, andernfalls false
	 */
	private function is_valid_step_to($to) {
		$furthest_page_string = Session::get(self::PAGE_SESSION_KEY);
		$furthest_page = array_flip(self::PAGE_STRING_MAP)[$furthest_page_string];

		$code_is_unknown = Session::get(self::CODE_SESSION_KEY) === null
			|| Session::get(self::CODE_SESSION_KEY) === '';

		// code has to be known
		if (!$code_is_unknown || $furthest_page === self::PAGE_START) {
			// the transition to the next or a previous state is valid
			if ($to <= $furthest_page + 1) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Ermittelt den weitesten Schritt (max($to_save, < bisher weitester Schritt >))
	 * und legt diesen in der Session ab.
	 *
	 * @param $to_save
	 * 			der zu vergleichende Schritt
	 */
	private function save_furthest_step($to_save) {
		$furthest_page_string = Session::get(self::PAGE_SESSION_KEY);
		$furthest_page = array_flip(self::PAGE_STRING_MAP)[$furthest_page_string];

		if ($to_save > $furthest_page) {
			Session::put(self::PAGE_SESSION_KEY, self::PAGE_STRING_MAP[$to_save]);
		}
	}

	/**
	 * Ermittelt den Code-Status und gibt ihn zurück.
	 *
	 * @param Code
	 * 			zu überprüfender Code
	 *
	 * @return der Status ($CODE_INCORRECT, $CODE_UNREGISTERED oder $CODE_CODE_REGISTERED)
	 */
	private function code_status($code)
	{
		if (Patient::whereCode($code)->exists()) {
			return self::CODE_REGISTERED;
		} else if (Code::whereValue($code)->exists()) {
			return self::CODE_UNREGISTERED;
		} else {
			return self::CODE_INCORRECT;
		}
	}

	/**
	 * Beim Aufruf wird geprüft
	 *
	 * <ol>
	 * 	<li> Ist der Benutzer angemeldet und die Session aktiv (sollte der Fall sein,
	 * 			wenn StayLoggedIn=true), so wird er auf seine Homepage /Home weitergeleitet.
	 * 	<li> Gibt es keine freien Tage so wird so wird die Startseite mit Kontaktmöglichkeit
	 * 			statt mit Registrierung ausgeliefert</li>
	 * 	<li> Ansonsten wird die Seite Startseite ausgeliefert</li>
	 * </ol>
	 */
	public function enter_system()
	{
		if (Auth::check()) {
			return Redirect::to('/Home');//->with('alert_messages', Alert::all());
		} else {
			return Redirect::to('/Login');//->with('alert_messages', Alert::all());
		}
	}

	/**
	 * Weiterleitung zur Seite mit den Verpflichtungen.
	 */
	public function from_welcome()
	{
		if ($this->is_valid_step_to(self::PAGE_AGREEMENT)) {
			$this->save_furthest_step(self::PAGE_AGREEMENT);
			return view('gate.accept');
		} else {
			return Redirect::to('/');
		}
	}

	/**
	 * Auf dem Server werden die verfügbaren Tage berechnet:
	 * Days.get_available_days()
	 *
	 * Wenn keine Tage verfügbar sind wird zur Startseite zurückgesprungen die dann
	 * die Information anzeigt, dass eine Registrierung nicht möglich ist.
	 *
	 * Mit diesen Informationen wird die Seite zur Erfassung der Patientendaten
	 * aufgebaut und ausgeliefert.
	 *
	 * Aufgerufen von: /Accepted
	 */
	public function req_patient_data()
	{
		$days = new Days;

		if ($this->is_valid_step_to(self::PAGE_FORM)
			&& $days->day_available() === true) {
			$available_days = $days->get_available_days();

			$this->save_furthest_step(self::PAGE_FORM);

			return view('gate.patient_data')->with('DayOfWeek', $available_days);
		} else {
			return Redirect::to('/');
		}
	}

	/**
	 * Es werden im Profil des Patienten gespeichert:
	 * <ol>
	 * 	<li>Cookie.Code</li>
	 * 	<li>Name</li>
	 * 	<li>Passwort1</li>
	 * 	<li>Email1</li>
	 * 	<li>gewählter Tag</li>
	 * 	<li>Zeit der Registrierung</li>
	 * </ol>
	 *
	 * Es wird vermerkt, dass der Code registriert ist
	 * Die Anzahl der Slots für den gewählten Tag wird um 1 vermindert:
	 * Days.decrease_day(gewählter Tag)
	 * Danach wird auf die Patienten-Homepage weitergeleitet.
	 *
	 * @param code
	 * @param name
	 * @param password
	 * @param email
	 * @param day
	 */
	public function save_patient_data(Request $request)
	{
		$code = $request->session()->get(self::CODE_SESSION_KEY);
		$name = $request->input('name');
		$password = $request->input('password');
		$email = $request->input('email');
		$day = $request->input('day_of_week');

		$emailExists = Patient::whereEmail($email)->exists();
		$nameExists = Patient::whereName($name)->exists();

		//if (Name or eMail already in use) {
		if ($nameExists || $emailExists) {
			$message = "Ihre Registrierung ist leider fehlgeschlagen.".
				($nameExists ? "Bitte wählen Sie einen anderen Benutzernamen." :
					"Bitte überprüfen Sie die eingegebene E-Mail-Adresse.");

			$days = new Days;
			$day_of_week=$days->get_available_days();
			Alert::error($message, 'Fehler bei Registrierung')->persistent();

			//Zeige Seite PatientenDaten
			return view('gate.patient_data')->with('DayOfWeek',$day_of_week);
		} else {
			$dateMap = Helper::generate_day_name_map();

			//Result: Registered=true;
			$patient = new Patient;
			$patient->name = $name;
			$patient->email = $email;
			$patient->password = bcrypt($password);
			$patient->registration_date = Date::now();
			$patient->code = $code;
			$patient->assignment_day = $dateMap[$day];
			$patient->assignment_day_changes_left = 1;
			$patient->is_random = false;
			$patient->date_from_clinics = null;

			$patient->save();

			// create all assignments (all future assignments will be defined)
			for ($week = 1; $week <= 12; $week++) {
				$assignment = ($week == 1) ?
					new SituationSurvey :
					new Task;

				$assignment->week = $week;
				$assignment->dirty = false;

				$patient->assignments()->save($assignment);
			}

			$days = new Days;
			$days->decrease_day($day);

			Auth::login($patient);

			Alert::success('Sie haben sich erfolgreich registriert.')->persistent();
			return Redirect::to('/Diary');

			//return view('patient.diary')->with('name',$name);
		}
	}

	/**
	 * Start des Registrierungsprozesses.
	 *
	 * System prüt, ob der Code in der Datenbank vorhanden und noch nicht belegt ist.
	 * Die Begrüungsseite wird im Erfolgsfall angezeigt. Ansonsten wird der Benutzer
	 * informiert
	 *
	 * Aufgerufen von: /StartRegistration
	 */

	public function start_registration(Request $request) {
		if ($this->is_valid_step_to(self::PAGE_WELCOME)) {
			// $this->validate($request, ['Code' => 'required']);
			// Versuch nach Cookbook:
			$rules = array('Code' => 'required');
			$validation = Validator::make(Input::all(), $rules);

			if ($validation->fails()) {
				// return Redirect::back()->withErrors($validation)->withInput();
				Alert::warning('Bitte geben Sie den Code ein, den Sie für die Teilnahme an der Studie erhalten haben.')->persistent();
				return Redirect::to('/Login');
			}

			// Alternativ: Input::get('Code');
			$code = $request->input('Code');

			$request->session()->put(self::CODE_SESSION_KEY, $code);

			if ($this->code_status($code) === self::CODE_REGISTERED) {
				// code is already registered
				Alert::warning('Dieser Code wurde bereits registriert. Bitte loggen Sie sich mit Ihrem Benutzernamen und Passwort ein.')->persistent();
				return Redirect::to('/Login');
			} else if ($this->code_status($code) === self::CODE_UNREGISTERED) {
				// code isn't yet registered
				$this->save_furthest_step(self::PAGE_WELCOME);
				/* TODO: better URLs
				return Redirect::to('/registration/welcome'); */
				return view('gate.welcome');
			} else {
				// code is incorrect
				Alert::warning('Der einegegebene Code ' . $code . ' ist nicht korrekt. Hilfe zur Code-Eingabe:...')->persistent();
				return Redirect::to('/Login');
			}
		} else {
			// invalid (wizard) step
			return Redirect::to('/Login');
		}
	}

	// TODO: better URLs - use method - see routes ('/registration/welcome')
	/**
	 * Zeigt die Willkommensseite. Mit Hilfe dieser Methode kann die
	 * Seite direkt aufgerufen werden.
	 */
	public function show_welcome() {
		return view('gate.welcome');
	}

}
?>
