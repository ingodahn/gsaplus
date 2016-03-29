<?php

namespace App;

use App\Models\InfoModel;

class WAI extends InfoModel
{

    protected $table = 'wai';

    protected $dates = ['created_at', 'updated_at'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['survey_id'];

    /**
     * Relationship to the survey. Please use $wai->survey to
     * access the survey.
     */
    public function survey() {
        return $this->belongsTo('App\Survey');
    }

    public function info_array_key()
    {
        return strtolower(parent::info_array_key());
    }

}
