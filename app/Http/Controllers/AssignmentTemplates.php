<?php
namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: dahn
 * Date: 06.05.2016
 * Time: 12:22
 * Diese Klasse stellt die Verbindung vom Controller zum Modell her
 * Sie ist f&uuml;r das Speichern und abrufen von Mustern für Schreibimpulse zust&auml;ndig
 */
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AssignmentTemplates extends Controller
{
    public function get_template(Request $request)
    {
        //ToDo: get all assignment templates from database
        $template_title = $request->input('templateTitle');
        $templates = [
            "Problem 1" => "Text 1\nZeile 1.2",
            "Problem 2" => "Text 2\nÄÖÜß"
        ];

        return $templates[$template_title];
    }

    public static function get_template_titles() {
        //ToDo: get all titles of assignment templates from database
        return ["Problem 1", "Problem 2"];
    }

    public static function save_template($title,$text){
        //Todo: save assignment to database. If an assignment with the same title exists, it will be overwritten
        return [
            "type"=>"error",
            "message"=>"Muster ".$title." nicht gespeichert - noch nicht implementiert"
        ];
    }
}
?>

