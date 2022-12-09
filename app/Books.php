<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    protected $fillable=[
        'id',
        'book_name',
        'author',
        'category',
        'description',
        'book_file',
        'file_path',
        'uploaded_by',
        'total_view',
        'total_download',
        'cloud_id'
    ];
}
