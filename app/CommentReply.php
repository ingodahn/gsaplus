<?php

namespace App;

use App\Models\InfoModel;

class CommentReply extends InfoModel
{

    protected $dates = ['created_at', 'updated_at'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['comment_id', 'created_at', 'updated_at', 'is_random'];

    /**
     * Relationship to the comment. Please use $->comment_reply
     * to access the comment.
     */
    public function comment()
    {
        return $this->belongsTo('App\Comment', 'comment_id');
    }

    public function getCreatedAtAttribute($date) {
        return $date === null ? null : new Date($date);
    }

    public function getUpdatedAtAttribute($date) {
        return $date === null ? null : new Date($date);
    }

}
