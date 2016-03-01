<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{

    protected $dates = ['created_at', 'updated_at', 'date'];

    /**
     * Get the assignment.
     */
    public function assignment()
    {
        return $this->belongsTo('App\Assignment');
    }

    /**
     * Get the author of the response.
     */
    public function therapist()
    {
        return $this->belongsTo('App\Therapist');
    }
}
