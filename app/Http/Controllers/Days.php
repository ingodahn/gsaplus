<?php


namespace App\Http\Controllers;


/**
 * Es wird persistent genau ein Objekt dieser Klasse benötigt. In den Attributen
 * Sonntag...Donnerstag wird gespeichert, wieviele Patienten sich für diesen Tag
 * neu registrieren dürfen.
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 15:50:31
 */
class Days
{

	function __construct()
	{
	}

	function __destruct()
	{
	}



	/**
	 * Es wird true zurückgegeben wenn es wenigstens einen Tag gibt, für den eine
	 * Registrierung möglich ist, sonst false
	 */
	public function day_available()
	{

		//if (get_available_days() == {})
		// return false;
		//} else {
		return true;
		//}


	}

	/**
	 * Die Anzahl der Slots für den Tag wird um eins vermindert. Wenn kein Slot übrig
	 * ist wird der Admin benachrichtigt.
	 * 
	 * @param day    Day
	 */
	public function decrease_day(Date $day)
	{

		//var day_list=get_days();
		//day_list.day--;
		//if (day_list.day <= 0) {
		//send Mail to admin
		//};
		//set_days(day_list);
		//



	}

	/**
	 * Es wird die Liste aller Tage T zurückgegeben für die this.T > 0 ist.
	 */
	public function get_available_days()
	{

		//var day_list=get_available_days();
		//var available_days={};
		//for each day in keys(day_list){
		// if (day_list.day > 0) {
		//   push(day,available_days);
		//}}
		//return day_list;



	}

	/**
	 * Es wird eine Liste aus der Datenbank geholt die angibt, für welchen Tag
	 * wieviele Slots verfügbar sind.
	 */
	private function get_days()
	{
	}

	/**
	 * Für jeden Tag wird in der Datenbank die Anzahl der verfügbaren Tage gespeichert.
	 * 
	 * 
	 * @param day_list
	 */
	private function set_days($day_list)
	{
	}

}
?>