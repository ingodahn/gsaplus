<?php
namespace App\Http\Controllers;

// require_once ('..\..\Models\Cookie.php');
require_once ('Controller.php');
//require_once('Days.php');

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models;
use App\Http\Controllers;
use Prologue\Alerts\Facades\Alert;

/**
 * Diese Klasse behandelt alle Aufrufe des servers in Zusammenhang mit dem
 * Registrierungs- und Anmeldeprozess.
 * Neben den angegebenen Operationen wird für jedes Signal eine Methode benötigt
 * die den entsprechenden https-Aufruf mit den Signalparametern verarbeitet.
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 15:50:31
 */
class GateController extends Controller
{

	function __construct()
	{
	}

	function __destruct()
	{
	}



	/**
	 * wenn Username oder Password falsch sind wird Zugriff verweigert.
	 * Ansonsten wird Zugriff erlaubt und Code und StayLoggedin weirden im Cookie
	 * gespeichert.
	 * 
	 * @param NameOrEmail
	 * @param Password
	 * @param StayLoggedIn
	 */
	public function check_login_password(String $NameOrEmail, String $Password, boolean $StayLoggedIn)
	{

		//if (incorrect name or password) {
		//Result: AccessAllowed=false;
		// return View::make(system.info_message)
		//-> where ('Text',"Falscher Benutzername
		//oder Passwort, bitte noch einmal
		//versuchen");
		//} else {
		//Result: AccesAllowed=true;;
		//Store Code and Cookie.code
		// Store StayLoggedIn in Cookie.
		//stay_loggedin;
		// Create SessionInfo;
		// AuxController@home();
		//}



	}

	/**
	 * 
	 * @param Code
	 */
	private function code_status(String $Code)
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
	 * Beim ersten Aufruf wird geprüft
	 * <ol>
	 * 	<li>Ob der Cookie gesetzt ist</li>
	 * 	<li>Wenn ja ob der Parameter StayLoggedIn auf true steht</li>
	 * 	<li>Es wird geprüft, ob der Code im Cookie registriert ist.</li>
	 * 	<li>Schlägt einer dieser Tests fehl wird verfahren als ob der Cookie nicht
	 * gesetzt wäre.</li>
	 * 	<li>Ist StayLoggindIn==true so wird zum Tagebuch schreiben weitergeleitet.
	 * </li>
	 * 	<li>Gibt es keine freien Tage so wird so wird die Seite Login_only
	 * ausgeliefert</li>
	 * 	<li>Ansonsten wird die Seite Startseite ausgeliefert</li>
	 * </ol>
	 * 
	 * @param cookie
	 */
	 public function enter_system(Request $request)
	// public function enter_system(Cookie $cookie)
	{
		// $session=$request->session();
		//ID $code = $request->session()->get('Code');
		$code = "BBB";
		$days = new Days;
		//if ((cookie.stay_logged_in) && (Code
		//is registered)) {
		if ( $code == "AAA") {
		// Result: "relogin";
			return "relogin";
		} else if ($days->day_available())  {
			return view('gate.start_page')->with('RegistrationPossible',true);
		// Result: "registrationPossible";
		} else {
		// return "registrationImpossible";
			return view('gate.start_page')->with('RegistrationPossible',false);
		//	return view(gate.login_only);
		//Result:"registrationImpossible";
		}


	}

	/**
	 * Weiterleitung zur Seite mit den Verpflichtungen
	 */
	public function from_welcome()
	{

		return view('gate.accept');


	}

	/**
	 * 
	 * @param ResetCode
	 */
	public function get_reset_code(String $ResetCode)
	{

		//if (ResetCode == "BBB-Reset") {
		//Trace("Zur Profilseite");
		//sim.ResetCodeCorrect=true;
		//} else {
		//dialog.ResetCodeIncorrect.Show=true;;
		//}


	}

	/**
	 * Es wird geprüft, ob die Mail-Adresse registriert ist. Wenn ja wird ein Reset-
	 * Code dafür angelegt, eine Mail wird verschickt.
	 * Um die vorhandenen Mailadressen zu schützen wird der Benutzer In jedem Fall
	 * darüber informiert, dass eine Mail verschickt wurde, auch wenn dies nicht der
	 * Fall ist, weil die Mail-Adresse nicht registriert war.
	 * 
	 * @param Mail
	 */
	public function mail_for_password(String $Mail)
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
	 * Aufgerufen von: /Accepted
	 */
	public function req_patient_data()
	{

		//if (! Days.days_available()) {
		// Generic.home();
		// return;
		//}
		//Setze Auswahlliste  Patientendaten.
		//Wochentag unter Verwendung von Days.
		//get_available_days()
		//Zeige Seite PatientenDaten
		return view('gate.patient_data');


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
	 * Es wird vermerkt, dass der Code registriert ist
	 * Die Anzahl der Slots für den gewählten Tag wird um 1 vermindert:
	 * Days.decrease_day(gewählter Tag)
	 * 
	 * @param code
	 * @param name
	 * @param password
	 * @param email
	 * @param day
	 */
	public function save_patient_data($Code, $Name, $Password, $eMail, $Day)
	{

		//if (Name or eMail already in use) {
		//Result: Registered=false;
		//return View::make(system.info-message) -
		//> with('text',"Ihre Registrierung ist
		//leider fehlgeschlagen, Bitte wählen Sie einen anderen Benutzernamen");
		//} else {
		//Result: Registered=true;
		//Patient.code=Code;
		//Patient.user_name=Name;
		//Patient.assword=Password;
		//Patient.eMail=eMail;
		//Patient.day=Day;
		//save Patient;
		//return view(gate.registration_success).
		//}


	}

	/**
	 * Start des Registrierungsprozesses.
	 * System prüft, ob der Code in der Datenbank vorhanden und noch nicht belegt ist.
	 * Die Begrüßungsseite wird im Erfolgsfall angezeigt. Ansonsten wird der Benutzer
	 * informiert
	 * Aufgerufen von: /StartRegistration
	 * 
	 * @param Code
	 */
	
	public function start_registration(Request $request) {
	    $code = $request->input('Code');
		// \Session::put('Code',$code);
		if (! $code) {
			return $this->missing_input('Code',$request);
		}
		if ($code == "AAA") {
		//if (code already registered) {
			Alert::warning('Dieser Code wurde bereits registriert, Sie können sich anmelden.')->flash();
			return $this->enter_system();
		//View::make(system.info_message)-> where ('Text',"Dieser Code wurde bereits registriert, Sie können sich anmelden");
		// Result: CodeStatus="registered";
		} else if ($code == "BBB") {
		//(Code not yet registered) {
			return view('gate.welcome');
		// Result: CodeStatus="unregistered";
		} else {
			Alert::warning('Der einegegebene Code '.$code.' ist nicht korrekt. Hilfe zur Code-Eingabe:...')->flash();
			return $this->enter_system();
		//  return View::make(system.info_message) -> where ('Text',"Der einegegebene Code ist nicht korrekt. Hilfe zur Code-Eingabe:...");
		// Result: CodeStatus="incorrect";
		}

	}
	
	function missing_input($par,$request) {
		return "Missing Parameter ".$par." In Request START:".$request.":END";
	}

}
?>