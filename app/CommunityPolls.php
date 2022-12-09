<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityPolls extends Model
{

    protected $fillable = [
        'user_id',
        'community_id',
        'question',
        'options',
        'is_anonymous'
    ];
}
