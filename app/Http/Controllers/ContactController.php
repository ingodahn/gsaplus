<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use UxWeb\SweetAlert\SweetAlert as Alert;

use App\Patient;
use App\Models\UserRole;

use Validator;

/**
 * @author dahn
 * @version 1.0
 * @created 12-Feb-2016 10:26:45
 */
class ContactController extends Controller
{

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

		$list_of_patients = $request->input('list_of_names');
		$listS = implode(', ', $list_of_patients);
		// return dd($list_of_patients);
		return view('system.mail_editor')->with('ListOfPatients', $listS);
	}

	/**
	 * Sendet eine Mail an eine Liste von Patienten
	 * Nur Therapeuten und Administratoren dürfen diese Funktion nutzen
	 *
	 * @param list_of_names
	 * @param mail_subject
	 * @param mail_body
	 */
	public function message_to_patients(Request $request)
	{
		$user_role = $request->user()->type;
		if ($user_role !== UserRole::ADMIN && $user_role !== UserRole::THERAPIST) {
			Alert::error("Sie haben kein Recht, auf diese Seite zuzugreifen");
			return Redirect::to('/Home');
		}
		$list_of_names = $request->input('list_of_names');
		$mail_subject = $request->input('mail_subject');
		$mail_body = $request->input('mail_body');

		$list_of_names = str_replace(' ', '', $list_of_names);

		// sort both collections by name
		$patient_names = collect(explode(',', $list_of_names))->sort()->flatten();
		$patient_mails = array_pluck(Patient::whereIn('name', $patient_names)->get()->sortBy('name'), 'email');

		$eMailTeam = config('mail.team.address');
		$nameTeam = config('mail.team.name');

		$patient_names_array = $patient_names->toArray();

		for ($i = 0; $i < count($patient_mails); $i++) {
			sleep(config('mail.time_between_consecutive_mails'));

			Mail::raw($mail_body, function ($message) use ($patient_names_array, $patient_mails, $i, $eMailTeam, $nameTeam, $mail_subject) {
				// works because collections are sorted (see above)
				$message->from($eMailTeam, $nameTeam)
							->subject($mail_subject)
							->to($patient_mails[$i], $patient_names_array[$i]);
			});
		}

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
				$message->from($eMail)->to($eMailTeam, $nameTeam)->subject($subject);
		});

		Alert::success('Ihre Nachricht wurde an das Projektteam übermittelt')->persistent();
		return Redirect::to('/Home');
	}

}
?>
