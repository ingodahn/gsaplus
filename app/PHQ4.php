<?php

namespace App;

use App\Models\InfoModel;

class PHQ4 extends InfoModel
{

    protected $table = 'phq4';

    protected $dates = ['created_at', 'updated_at'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['survey_id', 'created_at', 'updated_at', 'is_random'];

    /**
     * Relationship to the survey. Please use $phq4->survey to
     * access the survey.
     */
    public function survey() {
        return $this->belongsTo('App\Survey');
    }

}
