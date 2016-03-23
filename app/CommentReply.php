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
    protected $hidden = ['comment_id'];

    /**
     * Relationship to the comment. Please use $->comment_reply
     * to access the comment.
     */
    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }

    public function to_info($current_info = []) {
        $info = parent::to_info($current_info);

        $comment_text = $this->comment ? $this->comment->text : $this->info_null_string;

        $info = array_add($info, $this->class_name() .'.comment', $comment_text);

        return $info;
    }

}
