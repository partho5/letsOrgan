<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamQuestions extends Model
{

    protected $fillable = [
        'course_id',
        'year',
        'question_of',
        'file_name',
        'file_path',
        'file_size',
        'cloud_id',
        'uploaded_by',
        'total_view',
        'total_download'
    ];

}
