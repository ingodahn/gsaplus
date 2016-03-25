<?php

namespace App;

use App\Models\AssignmentType;
use App\Models\InfoModel;

use Jenssegers\Date\Date;

class Comment extends InfoModel
{

    protected $dates = ['created_at', 'updated_at', 'date'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['assignment_id', 'therapist_id'];

    /**
     * Relationship to the commented assignment. Please use
     * $comment->assignment to access the assignment.
     */
    public function assignment()
    {
        return $this->belongsTo('App\Assignment');
    }

    /**
     * Relationship to the author of the comment. Please use
     * $comment->therapist to access the therapist.
     */
    public function therapist()
    {
        return $this->belongsTo('App\Therapist');
    }

    /**
     * Relationship to the patients reply. Please use $comment->reply
     * to access the reply.
     */
    public function comment_reply() {
        return $this->hasOne('App\CommentReply');
    }

    public function to_info($current_info = []) {
        $info = parent::to_info($current_info);

        if ($this->assignment->type === AssignmentType::TASK) {
            $problem = $this->assignment->problem ? $this->assignment->problem : $this->info_null_string;

            $info = array_add($info, $this->class_name() .'.task', $problem);
        }

        $therapist_name = $this->therapist ? $this->therapist->name : $this->info_null_string;

        $info = array_add($info, $this->class_name() .'.therapist', $therapist_name);

        return $info;
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
