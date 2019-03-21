<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    const STATUS_TO_DO = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_DONE = 3;
    
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'priority',
        'status'
    ];
}
