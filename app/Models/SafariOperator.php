<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafariOperator extends Model
{
    protected $fillable = ['name', 'details', 'location', 'no_of_employees', 'type', 'status', 'logo'];
    protected $casts = [
        'status' => 'string',
    ];
    protected $appends = [
        'operator_logo_url'
    ];
    public function getOperatorLogoUrlAttribute()
    {
        return ("{$this->logo}") ? url()->to('/' . "{$this->logo}") : '';
    }
}
