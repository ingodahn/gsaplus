<?php
namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: dahn
 * Date: 06.05.2016
 * Time: 12:22
 */
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AssignmentTemplates extends Controller
{
    public function get_template(Request $request)
    {
        $template_title = $request->input('templateTitle');
        $templates = [
            "Problem 1" => "Text 1\nZeile 1.2",
            "Problem 2" => "Text 2\nÄÖÜß"
        ];

        return $templates[$template_title];
    }

    public static function get_template_titles() {
        return ["Problem 1", "Problem 2"];
    }
}
?>