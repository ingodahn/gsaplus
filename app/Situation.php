<?php

namespace App;

use App\Models\InfoModel;

class Situation extends InfoModel
{

    protected $dates = ['created_at', 'updated_at'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['situation_survey_id'];

    /**
     * Relationship to the situation survey. Please use
     * $situation->situation_survey to access the survey.
     */
    public function situation_survey() {
        return $this->belongsTo('App\SituationSurvey', 'situation_survey_id');
    }

}
