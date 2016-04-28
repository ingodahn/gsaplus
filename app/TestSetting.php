<?php

namespace App;

use App\Models\InfoModel;

use Jenssegers\Date\Date;

class TestSetting extends InfoModel
{

    protected $dates = [
        'test_date',
        'created_at',
        'updated_at'
    ];


    protected $fillable = [
        'test_date',
        'first_reminder',
        'new_reminder',
        'due_reminder',
        'calc_next_writing_date'
    ];

    /*
     * The following accessors will convert every date to an instance
     * of Jenssegers\Date\Date which supports localization.
     *
     * All dates are originally returned as Carbon instances. The
     * Date class extends the Carbon class. So conversion is a
     * piece of cake.
     */

    public function getCreatedAtAttribute($date) {
        return $date === null ? null : new Date($date);
    }

    public function getUpdatedAtAttribute($date) {
        return $date === null ? null : new Date($date);
    }

    public function getTestDateAttribute($date) {
        return $date === null ? null : new Date($date);
    }

}
