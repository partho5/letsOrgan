<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{

    protected $fillable = [
        'user_id',
        'question_id',
        'answer',
        'upvote',
        'downvote'
    ];
}
