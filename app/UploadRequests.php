<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadRequests extends Model
{
    protected $fillable=[
        'type',
        'category',
        'details',
        'request_by'
    ];
}
