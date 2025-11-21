<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatDeletion extends Model
{
       protected $fillable = [
        'user_id',
        'chat_room_id',
        'deleted_at'
        
    ];
}
