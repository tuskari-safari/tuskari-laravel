<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $fillable = ['full_name', 'email', 'message', 'status'];


    protected $casts = [
        'status' => 'string',
    ];
}
