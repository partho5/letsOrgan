<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrentCommunityInMyCloud extends Model
{
    protected $fillable = [
        'user_id',
        'community_id'
    ];

    protected $table = 'current_community_in_my_cloud';
}
