<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafariCollection extends Model
{
    protected $fillable = [
        'collection_id',
        'safari_id',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function safari()
    {
        return $this->belongsTo(Safari::class);
    }
}
