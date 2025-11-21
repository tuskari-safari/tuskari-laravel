<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpAndSupport extends Model
{
     protected $fillable = [
        'tag',
        'question',
        'answer',
        'status'
    ];

       protected $casts = [
        'status' => 'string',
    ];
}
