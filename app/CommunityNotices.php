<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityNotices extends Model
{

    protected $fillable = [
        'user_id',
        'community_id',
        'about_what',
        'notice_for',
        'published_at',
        'details',
        'is_anonymous'
    ];
}
