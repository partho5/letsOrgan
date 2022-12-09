<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookDownloadLinks extends Model
{
    protected $fillable=[
        'name',
        'author',
        'category',
        'link',
        'upload_by',
        'total_download'
    ];

//    protected $table='book_download_links';
}
