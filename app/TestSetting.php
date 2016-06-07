<?php

namespace App;

use App\Models\InfoModel;

use Jenssegers\Date\Date;

class TestSetting extends InfoModel
{

    public $timestamps = false;

    protected $dates = [
        'test_date'
    ];


    protected $fillable = [
        'test_date'
    ];

    /*
     * The following accessors will convert every date to an instance
     * of Jenssegers\Date\Date which supports localization.
     *
     * All dates are originally returned as Carbon instances. The
     * Date class extends the Carbon class. So conversion is a
     * piece of cake.
     */

    public function getTestDateAttribute($date) {
        return $date === null ? null : new Date($date);
    }

}
