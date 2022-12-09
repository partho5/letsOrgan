<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadRequestResponse extends Model
{

    protected $fillable = [
        'request_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'soft_delete'
    ];
}
