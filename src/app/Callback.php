<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    protected $fillable = [
        'email',
        'message',
        'status'
    ];

    protected $table = 'callback';
}
