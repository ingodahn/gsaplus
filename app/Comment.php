<?php

namespace App;

use App\Models\InfoModel;

use Jenssegers\Date\Date;

class Comment extends InfoModel
{

    protected $dates = ['created_at', 'updated_at', 'date'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['assignment_id', 'therapist_id', 'created_at', 'updated_at', 'is_random'];

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
