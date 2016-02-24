<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends User
{

    protected static $singleTableType = 'patient';

    protected static $persisted = ['code', 'assignment_day', 'assignment_day_changes_left', 'date_from_clinics',
        // patient status should be determined - not cached
        // 'patient_status',
        'last_activity', 'personal_information', 'notes_of_therapist', 'registration_date', 'therapist_id' ];

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
