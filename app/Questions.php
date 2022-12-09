<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{

    protected $fillable = [
        'user_id',
        'question',
        'details',
        'topic',
        'upvote',
        'downvote'
    ];

}
