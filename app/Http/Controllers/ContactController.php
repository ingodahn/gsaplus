<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Prologue\Alerts\Facades\Alert;

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
		$bodyMessage=$request->input('message');

		$eMailTeam = config('mail.team.address');
		// $nameTeam = config('mail.team.name');

		Mail::raw($bodyMessage, function ($message) use ($eMail, $eMailTeam, $subject) {
				// no from part needed - the sites name and email address can be found
				// under 'mail.from' in file config/mail.php
				$message->from($eMail)->to($eMailTeam, 'Team GSA Online Plus')->subject($subject);
			});

		// uncomment to send confirmation
		/* Mail::send('emails.contact_mail_sent', ['bodyMessage' => $bodyMessage, 'subject' => $subject],
			function ($message) use ($eMail, $subject) {
				// no from part needed - the sites name and email address can be found
				// under 'mail.from' in file config/mail.php
				$message->to($eMail)->subject("Ihre Anfrage");
			}); */

		// alert doesn't work with more than one redirect
		Alert::info('Ihre Nachricht wurde an das Projektteam übermittelt')->flash();

		return Redirect::back();
	}

}
?>