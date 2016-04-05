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
    protected $hidden = ['survey_id'];

    public $relation_methods = [
        'survey'
    ];

    /**
     * Relationship to the survey. Please use $phq4->survey to
     * access the survey.
     */
    public function survey() {
        return $this->belongsTo('App\Survey');
    }

}
