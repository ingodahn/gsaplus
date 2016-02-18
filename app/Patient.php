<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends User
{

    protected static $singleTableType = 'patient';

    protected static $persisted = ['code', 'assignment_day', 'assignment_day_changes_left',
        // patient status should be determined - not cached
        // 'patient_status',
        'registration_date', 'therapist_id' ];

    /**
     * Get our assignments (all - independent of state).
     */
    public function assignments()
    {
        return $this->hasMany('App\Assignment');
    }

    /**
     * Get the responsible therapist.
     */
    public function therapist()
    {
        return $this->belongsTo('App\Therapist', 'therapist_id');
    }

}
