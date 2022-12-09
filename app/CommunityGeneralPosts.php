<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityGeneralPosts extends Model
{
    protected $fillable = [
        'user_id',
        'community_id',
        'academic_session',
        'title',
        'details',
        'is_anonymous',
        'post_type',
        'is_read'
    ];

    protected $dates = ['created_at', 'updated_at'];
}
