<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $fillable = [
        'traveller_enquiries',
        'operator_partnerships',
        'press_media',
        'general',
        'facebook_link',
        'instagram_link',
        'tiktok_link',
    ];
}
