<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariDiscount extends Model
{
     protected $fillable = ['safari_id', 'person', 'count', 'discount_type', 'discount'];

     public function safari(): BelongsTo
     {
          return $this->belongsTo(Safari::class);
     }
}
