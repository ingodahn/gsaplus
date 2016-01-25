<?php


namespace App\Http\Controllers;


/**
 * Es wird persistent genau ein Objekt dieser Klasse ben�tigt. In den Attributen
 * Sonntag...Donnerstag wird gespeichert, wieviele Patienten sich f�r diesen Tag
 * neu registrieren d�rfen.
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
	 * Es wird true zur�ckgegeben wenn es wenigstens einen Tag gibt, f�r den eine
	 * Registrierung m�glich ist, sonst false
	 */
	public function day_available()
	{

		//if (get_available_days() == {})
			return true;
		//} else {
		// return true;
		//}


	}

	/**
	 * Die Anzahl der Slots f�r den Tag wird um eins vermindert. Wenn kein Slot �brig
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
	 * Es wird die Liste aller der Tage T zur�ckgegeben f�r die es wenigstens einen freien Slot gibt. 
	 * Die Liste hat die Form ["Sonntag",...,"Donnerstag"].
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
		return array("Sonntag","Dienstag","Donnerstag");



	}

	/**
	 * Es wird eine Liste aus der Datenbank geholt die angibt, f�r welchen Tag
	 * wieviele Slots verf�gbar sind.
	 * Der R�ckgabewert hat die Form einer Liste wie ["Sonntag"=>3,..,"Donnerstag"=>5]. 
	 * In der Liste sind alle Tage von Sonntag bis Donnerstag genau einmal vertreten.
	 */
	public function get_days()
	{
	}

	/**
	 * F�r jeden Tag wird in der Datenbank die Anzahl der verf�gbaren Tage gespeichert.
	 * F�r jeden Tag wird in der Datenbank die Anzahl der verf�gbaren Tage gespeichert.
	 * Das Argument wird in der Form einer Liste der Form ["Sonntag"=>3,..,"Donnerstag"=>5] �bergeben. 
	 * In der Liste sind alle Tage von Sonntag bis Donnerstag genau einmal vertreten.
	 * 
	 * @param day_list
	 */
	public function set_days($day_list)
	{
	}

}
?>