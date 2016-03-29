<?php

namespace App;

use App\Models\InfoModel;

class Survey extends InfoModel
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

    public function to_info($current_info = []) {
        $info = parent::to_info($current_info);

        if ($this->phq4) {
            $info[$this->info_array_key()] = $this->phq4->to_info($info[$this->info_array_key()]);
        }

        if ($this->wai) {
            $info[$this->info_array_key()] = $this->wai->to_info($info[$this->info_array_key()]);
        }

        return $info;
    }

}
