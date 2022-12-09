<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityAssignments extends Model
{

    protected $fillable = [
        'user_id',
        'community_id',
        'academic_session',
        'course_name',
        'given_date',
        'deadline',
        'details',
        'is_anonymous'
    ];
}
