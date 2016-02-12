<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Therapist extends User
{

    protected static $singleTableType = 'therapist';

    /**
     * Get the responses to our assignments.
     */
    public function responses()
    {
        return $this->hasMany('App\Response');
    }

    /**
     * Get the patients for whom we provide guidance.
     */
    public function patients()
    {
        return $this->hasMany('App\Patient');
    }

}
