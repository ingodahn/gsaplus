<?php

namespace App;

use App\Models\InfoModel;

class Survey extends InfoModel
{

    protected $dates = ['created_at', 'updated_at'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['assignment_id', 'created_at', 'updated_at', 'is_random'];

    /**
     * Relationship to the first question of the survey (of type PHQ-4).
     * Please use $survey->phq4 to access the results.
     */
    public function phq4() {
        return $this->hasOne('App\PHQ4');
    }

    /**
     * Relationship to the second question of the survey (of type WAI).
     * Please use $survey->wai to access the result (the work ability
     * index).
     */
    public function wai() {
        return $this->hasOne('App\WAI');
    }

    /**
     * Relationship to the assignment (which was saved along with the
     * surveys answers). Please use $survey->assignment to access the
     * assignment.
     */
    public function assignment() {
        return $this->belongsTo('App\Assignment', 'assignment_id');
    }

    public function getCreatedAtAttribute($date) {
        return $date === null ? null : new Date($date);
    }

    public function getUpdatedAtAttribute($date) {
        return $date === null ? null : new Date($date);
    }

}
