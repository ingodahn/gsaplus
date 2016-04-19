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
