<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitorTracker extends Model
{
    protected $table = 'visitor_tracker';

    protected $guarded = [];

    public $timestamps = false;
}
