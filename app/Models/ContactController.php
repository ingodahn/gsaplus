<?php
namespace App\Http\Controllers;

require_once ('Controller.php');

use App\Http\Controllers;
/**
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 17:59:13
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

		//dialog.contact_form.Show=true;


	}

	/**
	 * Es wird mit der Seite confirm_message bestätigt, dass eine Nachricht an das
	 * Team geschickt wurde.
	 * 
	 * @param eMail
	 * @param subject
	 * @param message
	 */
	public function send_message($eMail, $subject, $message)
	{

		//Trace("Sending message ");
		//dialog.confirmation_message.
		//confirmation_info.Text="Ihre Nachricht
		//wurde an das Projektteam übermittelt";
		//dialog.confirmation_message.Show=true;


	}

		/**
		 * Die Nachricht mit angegebenem subject, Absender und message wird an das team
		 * geschickt und
		 * Es wird mit der info_message bestätigt, dass eine Nachricht an das Team
		 * geschickt wurde.
		 * 
		 * @param eMail
		 * @param subject
		 * @param message
		 */
		public function send_message($eMail, $subject, $message)
		{

			//Send Message to team;
			//return View::make('system.
			//info_message') -> with('Text',"Ihre
			//Nachricht wurde an das Projektteam
			//übermittelt");



		}

}
?>