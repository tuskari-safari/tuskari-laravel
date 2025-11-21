<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'chat_name',
        'picture',
        'is_group',
    ];
    protected $appends = ['last_message'];


    public function chatMembers(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function message(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getLastMessageAttribute()
    {
        $lastMessage = $this->message()->latest()->first();
        return $lastMessage ?? null;
    }
}
