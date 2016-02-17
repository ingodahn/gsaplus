<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Patient;
use App\Helper;

use Yajra\Datatables\Datatables;

class DatatablesController extends Controller
{
    /**
     * Displays datatables front end view
     *
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('datatables.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $days_map = Helper::generate_day_number_map();

        return Datatables::of(Patient::select('*'))
            ->addColumn('overdue', function ($patient) {
                if ($patient->assignments()->get()->last()->state === 0) {
                    return "ja";
                } else {
                    return "nein";
                }
            })
            ->edit_column('assignment_day', function($row) use ($days_map) {
                return $days_map[$row->assignment_day];
            })
            ->make(true);
    }
}
