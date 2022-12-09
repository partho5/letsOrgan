<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComPollOptions extends Model
{

    protected $fillable = [
        'poll_id',
        'option_id',
        'option_text',
        'voter_id'
    ];
}
