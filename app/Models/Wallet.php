<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'available_amount',
        'pending_amount',
        'total_earned',
        'total_withdrawn',
        'last_transfer_date',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function addFunds($amount, $bookingId = null)
    {
        $this->increment('pending_amount', $amount);

        return $this->transactions()->create([
            'booking_id' => $bookingId,
            'type' => 'credit',
            'amount' => $amount,
            'status' => 'pending',
        ]);
    }

    public function deductFunds($amount, $bookingId = null)
    {
       $this->decrement('pending_amount', $amount);

        return $this->transactions()->create([
            'wallet_id' => $this->id,
            'booking_id' => $bookingId,
            'type' => 'debit',
            'amount' => $amount,
            'status' => 'completed',
        ]);
       
    }

    public function settleFunds($amount)
    {
        if ($this->pending_amount >= $amount) {
            $this->decrement('pending_amount', $amount);
            $this->increment('available_amount', $amount);
            $this->increment('total_earned', $amount);
            return true;
        }
        return false;
    }

    public function withdrawFunds($amount)
    {
        if ($this->available_amount >= $amount) {
            $this->decrement('available_amount', $amount);
            $this->increment('total_withdrawn', $amount);
            $this->update(['last_transfer_date' => now()]);
            return true;
        }
        return false;
    }
}
