<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use UxWeb\SweetAlert\SweetAlert as Alert;

use App\Patient;

use Validator;

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
	public function mail_editor(Request $request)
	{
		// return dd($request);
		$list_of_patients = $request->input('list_of_names');
		$listS = implode(', ', $list_of_patients);
		// return dd($list_of_patients);
		return view('system.mail_editor')->with('ListOfPatients', $listS);
	}

	/**
	 * Sendet eine Mail an eine Liste von Patienten
	 *
	 * @param list_of_names
	 * @param mail_subject
	 * @param mail_body
	 */
	public function message_to_patients(Request $request)
	{
		$list_of_names = $request->input('list_of_names');
		$mail_subject = $request->input('mail_subject');
		$mail_body = $request->input('mail_body');

		$list_of_names = str_replace(' ', '', $list_of_names);

		// sort both collections by name
		$patient_names = collect(explode(',', $list_of_names))->sort()->flatten();
		$patient_mails = array_pluck(Patient::whereIn('name', $patient_names)->get()->sortBy('name'), 'email');

		Mail::raw($mail_body, function ($message) use ($patient_names, $patient_mails, $mail_subject) {
			// no from part needed - the sites name and email address can be found
			// under 'mail.from' in file config/mail.php
			for ($i = 0; $i < count($patient_mails); $i++) {
				// works because collections are sorted (see above)
				$message->to($patient_mails[$i], $patient_names->toArray()[$i]);
			}

			$message->subject($mail_subject);
		});

		// Alert not shown
		Alert::success('Die Mails wurden verschickt.')->persistent();
		return Redirect::to('/Home');
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
		$validator = Validator::make($request->all(), [
			'eMail' => 'required|email',
			'subject' => 'required',
			'message' => 'required'
		]);

		if ($validator->fails()) {
			return Redirect::back()
				->withErrors($validator)
				->withInput();
		}

		$eMail = $request->input('eMail');
		$subject = $request->input('subject');
		$bodyMessage = $request->input('message');

		$eMailTeam = config('mail.team.address');
		$nameTeam = config('mail.team.name');

		Mail::raw($bodyMessage, function ($message) use ($eMail, $eMailTeam, $nameTeam, $subject) {
				// no from part needed - the sites name and email address can be found
				// under 'mail.from' in file config/mail.php
				$message->from($eMail)->to($eMailTeam, $nameTeam)->subject($subject);
			});

		// uncomment to send confirmation
		/* Mail::send('emails.contact_mail_sent', ['bodyMessage' => $bodyMessage, 'subject' => $subject],
			function ($message) use ($eMail, $subject) {
				// no from part needed - the sites name and email address can be found
				// under 'mail.from' in file config/mail.php
				$message->to($eMail)->subject("Ihre Anfrage");
			}); */

		// alert doesn't work with more than one redirect
		Alert::success('Ihre Nachricht wurde an das Projektteam übermittelt')->persistent();

		return redirect("/");
	}

}
?>
