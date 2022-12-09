<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeptCourses extends Model
{

//    protected $fillable = [
//        'course_code',
//        'course_name',
//        'about_course',
//        'possessed_by_community',
//        'data_added_by'
//    ];

    protected $primaryKey = 'id';

    protected $guarded = [];
}
