<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Session;
use Illuminate\Support\Facades\Auth;

/**
 * @author dahn
 * @version 1.0
 * @created 12-Feb-2016 10:26:45
 */
class ContactController extends Controller
{

	function __construct()
	{
	}

	function __destruct()
	{
	}



	/**
	 * Zeige das Kontaktformular
	 */
	public function contact_team()
	{

		return view('system.contact_form');


	}

	/**
	 * Zeigt ein Formular zum verfassen von Mails an eine reihe von Patienten an
	 * 
	 * @param list_of_patients
	 */
	public function mail_editor($list_of_patients)
	{
		return view('system.mail_editor');
	}

	/**
	 * Sendet eine Mail an eine Liste von Patienten
	 * 
	 * @param list_of_names
	 * @param mail_subject
	 * @param mail_body
	 */
	public function message_to_patients($list_of_names, $mail_subject, $mail_body)
	{
	}
	
	/**
	 * Die Nachricht mit angegebenem subject, Absender und message wird an das team
	 * geschickt und
	 * Es wird mit alert bestätigt, dass eine Nachricht an das Team geschickt wurde.
	 * 
	 * @param eMail
	 * @param subject
	 * @param message
	 */
	public function send_message(Request $request)
	{
		$eMail=$request->input('eMail');
		$subject=$request->input('subject');
		$message=$request->input('message');
		//Send Message to team;
		Alert::info('Ihre Nachricht wurde an das Projektteam übermittelt');
		return Redirect::to('/Home');


	}

}
?>