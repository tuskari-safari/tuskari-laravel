<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
        'name',
        'show_in_frontend'
    ];

    protected $casts = [
        'show_in_frontend' => 'boolean'
    ];

    public function safariCollections()
    {
        return $this->hasMany(SafariCollection::class);
    }

    public function safaris()
    {
        return $this->belongsToMany(Safari::class, 'safari_collections', 'collection_id', 'safari_id');
    }
}
