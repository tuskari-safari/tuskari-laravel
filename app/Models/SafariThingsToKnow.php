<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafariThingsToKnow extends Model
{
    protected $fillable = [
        'safari_id',
        'heading',
        'description',
    ];
}
