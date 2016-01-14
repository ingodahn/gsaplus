<?php


namespace App\Models;


/**
 * Cookie steht für Informationen die persistent auf dem Rechner des Benutzers
 * gespeichert sind und die bei jedem Aufruf automatisch übermittelt werden ohne
 * dass es dazu spezieller Programmierung bedarf.
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 15:50:30
 */
class Cookie
{

	/**
	 * Der Code steht für eine Information die es erlaubt, den Benutzer eindeutig zu
	 * identifizieren. Ggf. muss diese Information durch Codierung geschützt werden so
	 * dass sie nicht von Unbefugten geändert oder gestohlen werden kann.
	 */
	public $Code = NotSet;
	public $StayLoggedIn = false;

	function __construct()
	{
	}

	function __destruct()
	{
	}



}
?>