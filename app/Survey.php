<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{

    protected $dates = ['created_at', 'updated_at'];

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

}
