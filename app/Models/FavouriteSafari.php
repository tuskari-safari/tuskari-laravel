<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavouriteSafari extends Model
{
    protected $fillable = [
        'safari_id',
        'user_id',
    ];
}
