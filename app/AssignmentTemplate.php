<?php

namespace App;

use App\Models\InfoModel;

use Jenssegers\Date\Date;

class AssignmentTemplate extends InfoModel
{

    /**
     * Relationship to all derived assignments (which are based on this template).
     * Please use $assignment_template->assignments to access the collection.
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
