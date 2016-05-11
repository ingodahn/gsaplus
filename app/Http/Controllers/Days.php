<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

use App\WeekDay;
use App\Helper;

/**
 * In den Attributen Sonntag...Donnerstag wird gespeichert, wieviele Patienten
 * sich für diesen Tag neu registrieren dürfen.
 *
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 15:50:31
 */
class Days
{
	// [name] = number
	private $days_map;

	function __construct()
	{
		$this->days_map = Helper::generate_day_name_map();
	}

	/**
	 * Es wird true zurückgegeben wenn es wenigstens einen Tag gibt, für den eine
	 * Registrierung möglich ist, sonst false
	 */
	public function day_available()
	{
		return WeekDay::all()->sum('free_time_slots') > 0 ? true : false;
	}

	/**
	 * Die Anzahl der Slots für den Tag wird um eins vermindert. Wenn kein Slot übrig
	 * ist wird der Admin benachrichtigt.
	 * 
	 * @param day	Carbon::MONDAY ... Carbon::SUNDAY (0..6)
	 * 				oder der Name des Wochentags
	 */
	public function decrease_day($day)
	{
		$entry = $this->get_week_day($day);

		$entry->free_time_slots--;

		if ($entry->save() && $entry->free_time_slots <= 0) {
			$this->send_mail($entry);
		}
	}

	/**
	 * Es wird die Liste aller der Tage T zurückgegeben für die es wenigstens einen freien Slot gibt.
	 * Die Liste hat die Form
	 * 		["Sonntag", ... , "Donnerstag"].
	 */
	public function get_available_days()
	{
		return array_pluck(WeekDay::where('free_time_slots', '>', 0)->get()->toArray(), 'name');
	}

	/**
	 * Es wird eine Liste aus der Datenbank geholt die angibt, für welchen Tag
	 * wieviele Slots verfügbar sind.
	 *
	 * Der Rückgabewert hat die Form einer Liste wie
 * 			["Sonntag" => 3, ... , "Donnerstag" => 5]
	 * .
	 *
	 * In der Liste sind alle Tage von Sonntag bis Donnerstag genau einmal vertreten.
	 */
	public function get_days()
	{
		$days = [];

		foreach (WeekDay::all() as $day) {
			$days[$day->name] = $day->free_time_slots;
		}

		return $days;
	}

	/**
	 * Für jeden Tag wird in der Datenbank die Anzahl der verfügbaren Tage gespeichert.
	 *
	 * Das Argument wird in der Form einer Liste der Form
 * 			["Sonntag"=>3, ... ,"Donnerstag"=>5]
	 *
	 * übergeben.
	 *
	 * In der Liste sind alle Tage von Sonntag bis Donnerstag genau einmal vertreten.
	 * 
	 * @param day_list
	 */
	public function set_days($day_list)
	{
		foreach ($day_list as $day => $free_time_slots) {
			if (!$entry = $this->get_week_day($day)) {
				// add an entry if none exists
				if (array_key_exists($day, $this->days_map)){
					$entry = new WeekDay();

					$entry->number = $this->days_map[$day];
					$entry->name = $day;
				} else {
					return;
				}
			}

			$entry->free_time_slots = $free_time_slots;
			$entry->save();
		}
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
		// don't query all days - search by primary key (faster)
		if (is_int($day) && $day >= 0 && $day <= 4) {
			$entry = WeekDay::find($day);
		} else if (array_key_exists($day, $this->days_map)) {
			$entry = WeekDay::find($this->days_map[$day]);
		} else {
			$entry = null;
		}

		return $entry;
	}

	/**
	 * Den Admin via E-Mail darüber informieren, dass alle Plätze belegt sind.
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