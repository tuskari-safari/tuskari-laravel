<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'bank_name',
        'account_holder_name',
        'account_number',
        'ifsc_code'
    ];


    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class, 'operator_bank_id');
    }
}
