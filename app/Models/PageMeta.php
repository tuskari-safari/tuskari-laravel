<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageMeta extends Model
{
    protected $fillable = [
        'page_name',
        'meta_title',
        'meta_description',
        'og_title',
        'og_type',
        'og_image',
        'og_image_width',
        'og_image_height',
        'og_url',
        'og_description',
        'canonical_url',
        'index_follow',
        'schema_details'
    ];

    protected $casts = [
        'index_follow' => 'boolean',
        'og_image_width' => 'integer',
        'og_image_height' => 'integer'
    ];
}
