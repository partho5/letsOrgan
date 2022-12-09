<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadRequest extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'file_category',
        'details',
        'shared_with_community'
    ];
}
