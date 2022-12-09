<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyCloud extends Model
{
    protected $table = 'my_cloud';

    protected $fillable = [
        'user_id',
        'name',
        'parent_dir',
        'is_root',
        'full_dir_url',
        'file_ext',
        'file_size',
        'access_code',
        'possessed_by_community',
        'shared_with_user',
        'soft_delete',
        'permanent_delete'
    ];
}