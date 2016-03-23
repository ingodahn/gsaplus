<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{

    protected $dates = ['created_at', 'updated_at'];

    /*
     * hide ids from list of attributes
     * (ids are used to resolve relationships)
     */
    protected $hidden = ['comment_id'];

    /**
     * Relationship to the comment. Please use $->comment_reply
     * to access the comment.
     */
    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }
}
