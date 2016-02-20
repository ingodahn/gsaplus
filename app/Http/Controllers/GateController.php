<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Session;
use Illuminate\Support\Facades\Auth;
// use Validator, Input, Redirect; 

use App\Code;
use App\User;
use App\Patient;

use App\Http\Controllers\Days;

use Carbon\Carbon;

use App\Helper;

use App\Models;
use App\Http\Controllers;
use Prologue\Alerts\Facades\Alert;

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

	// Name -> Schritt (nr.)
	private $page_string_map;

	// Reihenfolge der Schritte
	protected static $PAGE_START = 0;
	protected static $PAGE_WELCOME = 1;
	protected static $PAGE_ACCEPT = 2;
	protected static $PAGE_FORM = 3;

	// Der bis dato weiteste Schritt
	// (wenn man mal bei Schritt 3 war, kann man immer zu den Schritten 1-3 oder zum nächsten Schritt (4) gehen)
	private $current_page_key = 'last_reg_page';
	private $current_code_key = 'code';

	public function __construct()
	{
		$this->page_string_map = [GateController::$PAGE_START => 'start',
			GateController::$PAGE_WELCOME => 'welcome',
			GateController::$PAGE_ACCEPT => 'accept',
			GateController::$PAGE_FORM => 'form'];
	}

	/**
	 * Bestimmt den bisher weitesten Schritt (Session) und prüft, ob der nächste
	 * Schritt verfügbar ist.
	 *
	 * @param $to
	 * 			der nächste Schritt
	 * @return bool
	 * 			ob der nächste Schritt verfügbar ist
	 */
	private function is_valid_step_to($to) {
		$current_page_string = Session::get($this->current_page_key);
		// $current_page_is_unknown = (!$current_page_string === null || $current_page_string === '' ||
		//	!in_array($current_page_string, $this->page_string_map));

		$current_page = // $current_page_is_unknown ? null :
			array_flip($this->page_string_map)[$current_page_string];
		$code_is_unknown = Session::get($this->current_code_key) === null
			|| Session::get($this->current_code_key === '');


		// code has to be known
		if (!$code_is_unknown || $current_page === GateController::$PAGE_START) {
			// the transition to the next or the previous state is valid
			if ($to <= $current_page + 1) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Ermittelt den weitesten Schritt (max($to_save, << current step >>)) und
	 * legt diesen in der Session ab.
	 *
	 * @param $to_save
	 * 			der nächste Schritt
	 */
	private function save_furthest_step($to_save) {
		$current_page_string = Session::get($this->current_page_key);
		$current_page = array_flip($this->page_string_map)[$current_page_string];

		if ($to_save > $current_page) {
			Session::put($this->current_page_key, $this->page_string_map[$to_save]);
		}
	}

	/**
	 *
	 * @param Code
	 */
	private function code_status($code)
	{

		//if (Code == "BBB") {
		// return "registered;
		//} else if (Code == "AAA") {
		// return "unregistered";
		//} else {
		// return "incorrect";
		//}


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
	public function enter_system(Request $request)
	{
		if (Auth::check()) {
			return redirect('/Home');
		} else {
			return Redirect::to('/Login')->with('alert_messages', Alert::all());
		}
	}

	/**
	 * Weiterleitung zur Seite mit den Verpflichtungen
	 */
	public function from_welcome()
	{
		if ($this->is_valid_step_to(GateController::$PAGE_ACCEPT)) {
			$this->save_furthest_step(GateController::$PAGE_ACCEPT);
			return view('gate.accept');
		} else {
			return Redirect::to('/');
		}
	}

	/**
	 * Es wird geprüft, ob die Mail-Adresse registriert ist. Wenn ja wird ein Reset-
	 * Code dafür angelegt, eine Mail wird verschickt.
	 *
	 * Um die vorhandenen Mailadressen zu schützen wird der Benutzer In jedem Fall
	 * darüber informiert, dass eine Mail verschickt wurde, auch wenn dies nicht der
	 * Fall ist, weil die Mail-Adresse nicht registriert war.
	 *
	 * @param Mail
	 */
	public function mail_for_password($email)
	{

		//return View::make(gate.mail_sent) ->
		//where ('Text',"Mail an "+Mail+"
		//verschickt");
		//
		//



	}

	public function registration_complete()
	{

		//dialog.Startseite.show=true;



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
		if ($this->is_valid_step_to(GateController::$PAGE_FORM)) {
			$days = new Days;
			if (!$days->day_available()) {
				return Redirect::to('/');
			}

			//Setze Auswahlliste  Patientendaten.
			//Wochentag unter Verwendung von Days.
			$day_of_week = $days->get_available_days();
			//Zeige Seite PatientenDaten
			// return $day_of_week;

			$this->save_furthest_step(GateController::$PAGE_FORM);

			return view('gate.patient_data')->with('DayOfWeek', $day_of_week);
		} else {
			return Redirect::to('/');
		}
	}

	public function reset_password()
	{

		//dialog.PasswortVergessen.Show=true;


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
		// public function save_patient_data($Code, $Name, $Password, $eMail, $Day)
	{
		$code = $request->session()->get($this->current_code_key);
		$name = $request->input('name');
		$password = $request->input('password');
		$email = $request->input('email');
		$day = $request->input('day_of_week');

		$userExists = Patient::where(function($query) use($email, $name) {
			$query->where('email', '=', $email)
				->orWhere('name', '=', $name);
		})->first();

		//if (Name or eMail already in use) {
		if ($userExists) {
			//Result: Registered=false;
			Alert::danger("Ihre Registrierung ist leider fehlgeschlagen. Bitte w&auml;hlen Sie einen anderen Benutzernamen.")->flash();
			$days = new Days;
			$day_of_week=$days->get_available_days();
			//Zeige Seite PatientenDaten
			return view('gate.patient_data')->with('DayOfWeek',$day_of_week);
		} else {
			$dateMap = Helper::generate_day_name_map();

			//Result: Registered=true;
			$patient = new Patient;
			$patient->name = $name;
			$patient->email = $email;
			$patient->password = bcrypt($password);
			$patient->registration_date = Carbon::create();
			$patient->code = $code;
			$patient->assignment_day = $dateMap[$day];
			$patient->assignment_day_changes_left = 1;
			$patient->is_random = false;

			$patient->save();

			$days = new Days;
			$days->decrease_day($day);

			Auth::login($patient);

			// confirmation_message 'registration_success';
			Alert::info('Sie haben sich erfolgreich registriert.');

			return view('patient.diary')->with('name',$name);
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
		if ($this->is_valid_step_to(GateController::$PAGE_WELCOME)) {
			// $this->validate($request, ['Code' => 'required']);
			// Versuch nach Cookbook:
			$rules = array('Code' => 'required');
			$validation = Validator::make(Input::all(), $rules);

			if ($validation->fails()) {
				// return Redirect::back()->withErrors($validation)->withInput();
				Alert::warning('Bitte geben Sie den Code ein, den Sie f&uuml;r die Teilnahme an der Studie erhalten haben.')->flash();
				return Redirect::to('/Login');
			}

			// Alternativ: Input::get('Code');
			$code = $request->input('Code');

			$request->session()->put($this->current_code_key, $code);

			if (Patient::where('code', $code)->first() !== null) {
				//if (code already registered) {

				Alert::warning('Dieser Code wurde bereits registriert, Sie k&ouml;nen sich anmelden.')->flash();
				return Redirect::to('/Login');

				// Result: CodeStatus="registered";
			} else if (Code::where('value', $code)->first() !== null) {
				//(Code not yet registered) {

				return Redirect::to('/registration/welcome');
				// Result: CodeStatus="unregistered";
			} else {
				Alert::warning('Der einegegebene Code ' . $code . ' ist nicht korrekt. Hilfe zur Code-Eingabe:...')->flash();
				return Redirect::to('/Login');

				// Result: CodeStatus="incorrect";
			}
		} else {
			return Redirect::to('/Login');
		}
	}

	public function show_welcome() {
		$this->save_furthest_step(GateController::$PAGE_WELCOME);

		return view('gate.welcome');
	}

}
?>