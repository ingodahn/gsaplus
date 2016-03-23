<?php

namespace App;

use App\Models\InfoModel;

use Jenssegers\Date\Date;

class Response extends InfoModel
{

    protected $dates = ['created_at', 'updated_at', 'date'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['assignment_id', 'therapist_id'];

    /**
     * Relationship to the commented assignment. Please use
     * $response->assignment to access the assignment.
     */
    public function assignment()
    {
        return $this->belongsTo('App\Assignment');
    }

    /**
     * Relationship to the author of the response. Please use
     * $response->therapist to access the therapist.
     */
    public function therapist()
    {
        return $this->belongsTo('App\Therapist');
    }

    public function to_info($current_info = []) {
        $info = parent::to_info($current_info);

        $assignment_text = $this->assignment ? $this->assignment->assignment_text : $this->info_null_string;
        $therapist_name = $this->therapist ? $this->therapist->name : $this->info_null_string;

        $info = array_add($info, $this->class_name() .'.assignment', $assignment_text);
        $info = array_add($info, $this->class_name() .'.therapist', $therapist_name);

        return $info;
    }

    /*
     * The following accessors will convert every date to an instance
     * of Jenssegers\Date\Date which supports localization.
     *
     * All dates are originally returned as Carbon instances. The
     * Date class extends the Carbon class. So conversion is a
     * piece of cake.
     */

    public function getCreatedAtAttribute($date) {
        return new Date($date);
    }

    public function getUpdatedAtAttribute($date) {
        return new Date($date);
    }

    public function getDateAttribute($date) {
        return new Date($date);
    }
}
