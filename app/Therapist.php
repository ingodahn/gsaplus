<?php

namespace App;

use App\Models\UserRole;

use Illuminate\Database\Eloquent\Model;

class Therapist extends User
{

    protected static $singleTableType = UserRole::THERAPIST;

    /**
     * Relationship to the therapists responses . Please use
     * $therapist->responses to access the collection.
     */
    public function responses()
    {
        return $this->hasMany('App\Response');
    }

    /**
     * Relationship to the patients for whom the therapist is responsible.
     * Please use $therapist->patients to access the collection.
     */
    public function patients()
    {
        return $this->hasMany('App\Patient');
    }

}
