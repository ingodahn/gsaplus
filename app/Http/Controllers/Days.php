<?php


namespace App\Http\Controllers;

use App\WeekDay;
use Illuminate\Support\Facades\Mail;

/**
 * Es wird persistent genau ein Objekt dieser Klasse benÃ¶tigt. In den Attributen
 * Sonntag...Donnerstag wird gespeichert, wieviele Patienten sich fÃ¼r diesen Tag
 * neu registrieren dÃ¼rfen.
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 15:50:31
 */
class Days
{

	/**
	 * Es wird true zurÃ¼ckgegeben wenn es wenigstens einen Tag gibt, fÃ¼r den eine
	 * Registrierung mÃ¼glich ist, sonst false
	 */
	public function day_available()
	{
		return WeekDay::all()->sum('free_time_slots') > 0 ? true : false;
	}

	/**
	 * Die Anzahl der Slots fÃ¼r den Tag wird um eins vermindert. Wenn kein Slot Ã¼brig
	 * ist wird der Admin benachrichtigt.
	 * 
	 * @param day	bitte die Konstanten Carbon::MONDAY ... Carbon::SUNDAY
	 * 				oder den Namen des Wochentags (als String) benutzen
	 */
	public function decrease_day($day)
	{
		$entry = $this->get_week_day($day);

		$entry->free_time_slots--;
		$entry->save();

		if ($entry->free_time_slots <= 0) {
			$this->send_mail($entry);
		}
	}

	/**
	 * Es wird die Liste aller der Tage T zurückgegeben für die es wenigstens einen freien Slot gibt. 
	 * Die Liste hat die Form ["Sonntag",...,"Donnerstag"].
	 */
	public function get_available_days()
	{
		return array_pluck(WeekDay::where('free_time_slots', '>', 0)->get()->toArray(), 'name');
	}

	/**
	 * Es wird eine Liste aus der Datenbank geholt die angibt, für welchen Tag
	 * wieviele Slots verfügbar sind.
	 * Der Rückgabewert hat die Form einer Liste wie ["Sonntag"=>3,..,"Donnerstag"=>5]. 
	 * In der Liste sind alle Tage von Sonntag bis Donnerstag genau einmal vertreten.
	 */
	public function get_days()
	{
	}

	/**
	 * Für jeden Tag wird in der Datenbank die Anzahl der verfügbaren Tage gespeichert.
	 * Für jeden Tag wird in der Datenbank die Anzahl der verfügbaren Tage gespeichert.
	 * Das Argument wird in der Form einer Liste der Form ["Sonntag"=>3,..,"Donnerstag"=>5] übergeben. 
	 * In der Liste sind alle Tage von Sonntag bis Donnerstag genau einmal vertreten.
	 * 
	 * @param day_list
	 */
	public function set_days($day_list)
	{
	}

	/**
	 * Reads the weekday's entry and returns the eloquent model.
	 *
	 * @param $day
	 * 			the weekday - either a number between 0 and 6 or the name
	 *
	 * @return the appropriate eloquent model
	 */
	private function get_week_day($day) {
		if (is_int($day) && $day >= 0 && $day <= 6) {
			$entry = WeekDay::find($day); // don't query all days - search by primary key (faster)
		} else {
			$entry = WeekDay::where('name', '=', $day)->first();
		}

		return $entry;
	}

	/**
	 * Den Admin via E-Mail darÃ¼ber informieren, dass alle PlÃ¤tze belegt sind.
	 *
	 * @param WeekDay $day
	 */
	private function send_mail(WeekDay $day) {
		$admin_name = config('mail.admin.name');
		$admin_address = config('mail.admin.address');

		// translate subject to german
		$subject = trans('registration.no_more_time_slots');

		Mail::send('emails.no_more_time_slots', ['day' => $day->name, 'admin_name' => $admin_name],
			function ($message) use ($admin_name, $admin_address, $subject) {
				// no from part needed - the sites name and email address can be found
				// under 'mail.from' in file config/mail.php
				$message->to($admin_address, $admin_name)->subject($subject);
			});
	}

}
?>