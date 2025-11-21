<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'title',
        'description',
        'full_name',
        'rating',
        'user_image',
        'status'
    ];

    protected $appends = ['testimonial_user_image_path'];

    public function getTestimonialUserImagePathAttribute()
    {
        return $this->user_image ? asset($this->user_image) : '';
    }
}
