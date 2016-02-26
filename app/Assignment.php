<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{

    protected $dates = ['created_at', 'updated_at', 'assigned_on'];

    /**
     * Get the underlying template.
     */
    public function assignment_template()
    {
        return $this->belongsTo('App\AssignmentTemplate');
    }

    /**
     * Get the patient who should write an answer.
     */
    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }

    /**
     * Get the response of the therapist.
     */
    public function response()
    {
        return $this->hasOne('App\Response');
    }
}
