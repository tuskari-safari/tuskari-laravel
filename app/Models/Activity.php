<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'icon', 
        'added_by'
    ];
    protected $appends = [
        'full_icon_url'
    ];

    public function getFullIconUrlAttribute()
    {
        return $this->icon ? asset($this->icon): 'frontend_assets/images/gamedrives-icon.svg';
    }
    
}
