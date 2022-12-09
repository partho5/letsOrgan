<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherFiles extends Model
{
    protected $fillable=[
        'file_name',
        'file_type',
        'category',
        'description',
        'other_file',
        'file_path',
        'uploaded_by',
        'total_view',
        'total_download',
        'cloud_id'
    ];
}
