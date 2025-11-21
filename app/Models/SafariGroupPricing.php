<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafariGroupPricing extends Model
{
     protected $fillable = [
          'safari_id',
          'person_type',
          'count',
          'season',
          'net_price'
     ];

     public function safari(): BelongsTo
     {
          return $this->belongsTo(Safari::class);
     }
}
