<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
	// public function enter_system() {
		// return view('Welcome');
	// }
	public function enter_system()
	{

		//if ((cookie.stay_logged_inn) && (Code
		//is registered)) {
		// Result: "relogin";
		//} else if (Days.day_available())  {
		return view('gate.start_page');
		// Result: "registrationPossible";
		//} else {
		//return view(gatelLogin_only);
		//Result:"registrationImpossible";
		//}


	}

}