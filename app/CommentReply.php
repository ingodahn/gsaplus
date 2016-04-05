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

    public $relation_methods = [
        'comment'
    ];

    protected function info_relation_map()
    {
        return ['comment' => 'text'];
    }

    /**
     * Relationship to the comment. Please use $->comment_reply
     * to access the comment.
     */
    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }

}
