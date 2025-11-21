<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'thumbnail', 'description', 'status', 'category_id', 'tags', 'posted_by'];

    protected $appends = [
        'full_photo_url'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function getFullPhotoUrlAttribute()
    {
        return ("{$this->thumbnail}") ? url()->to('/' . "{$this->thumbnail}") : '';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
