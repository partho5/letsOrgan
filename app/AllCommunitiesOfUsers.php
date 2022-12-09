<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllCommunitiesOfUsers extends Model
{
    protected $table = 'all_communities_of_a_user';

    protected $fillable = [
        'id',
        'user_id',
        'community_id',
        'role'
    ];
}
