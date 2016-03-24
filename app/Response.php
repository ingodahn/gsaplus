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

    /*
     * The following accessors will convert every date to an instance
     * of Jenssegers\Date\Date which supports localization.
     *
     * All dates are originally returned as Carbon instances. The
     * Date class extends the Carbon class. So conversion is a
     * piece of cake.
     */

    public function getCreatedAtAttribute($date) {
        return new Date($date);
    }

    public function getUpdatedAtAttribute($date) {
        return new Date($date);
    }

    public function getDateAttribute($date) {
        return new Date($date);
    }
}
