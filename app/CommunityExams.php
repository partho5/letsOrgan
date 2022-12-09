<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityExams extends Model
{
    protected $fillable = [
        'user_id',
        'community_id',
        'academic_session',
        'course_name',
        'declared_at',
        'exam_date',
        'details',
        'is_anonymous'
    ];
}
