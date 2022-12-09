<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpDownVoteRecords extends Model
{
    protected $fillable = [
        'q_ans_type',
        'post_id',
        'user_id',
        'vote_type'
    ];
}
