<?php

namespace App;

use App\Models\InfoModel;

use Jenssegers\Date\Date;

class AssignmentTemplate extends InfoModel
{

    /**
     * Get all assignments which are based on this template.
     */
    public function assignments()
    {
        return $this->hasMany('App\Assignment');
    }

    /*
     * The following accessors will convert every date to an instance
     * of Jenssegers\Date\Date which supports localization.
     *
     * All dates are originally returned as Carbon instances. The
     * Date class extends the Carbon class. So conversion is a
     * piece of cake.
     */

    public function getCreatedAtAttribute($date)
    {
        return new Date($date);
    }

    public function getUpdatedAtAttribute($date)
    {
        return new Date($date);
    }

}
