<?php

namespace App;

use App\Models\UserRole;

use Illuminate\Database\Eloquent\Model;

class Therapist extends User
{

    protected static $singleTableType = UserRole::THERAPIST;

    public $relation_methods = [
        'patients',
        'comments'
    ];

    protected function info_relation_map() {
        return ['comments' => 'collection_info',
                'patients' => 'collection_info'];
    }

    /**
     * Relationship to the therapists comments . Please use
     * $therapist->comments to access the collection.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
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
