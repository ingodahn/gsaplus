<?php


namespace App\Models;


/**
 * Cookie steht f�r Informationen die persistent auf dem Rechner des Benutzers
 * gespeichert sind und die bei jedem Aufruf automatisch �bermittelt werden ohne
 * dass es dazu spezieller Programmierung bedarf.
 * @author dahn
 * @version 1.0
 * @created 13-Jan-2016 15:50:30
 */
class Cookie
{

	/**
	 * Der Code steht f�r eine Information die es erlaubt, den Benutzer eindeutig zu
	 * identifizieren. Ggf. muss diese Information durch Codierung gesch�tzt werden so
	 * dass sie nicht von Unbefugten ge�ndert oder gestohlen werden kann.
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