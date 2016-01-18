<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignmentTemplate extends Model
{
    /**
     * Get all assignments which are based on this template.
     */
    public function assignments()
    {
        return $this->hasMany('App\Assignment');
    }
}
