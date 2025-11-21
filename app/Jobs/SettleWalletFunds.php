<?php

namespace App\Jobs;

use App\Models\SafariBooking;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SettleWalletFunds implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Get bookings that are 2 days old and confirmed
        $bookings = SafariBooking::where('payment_status', 'confirmed')
            ->where('created_at', '<=', Carbon::now()->subDays(2))
            ->with('operator')
            ->get();

        foreach ($bookings as $booking) {
            $wallet = Wallet::firstOrCreate(
                ['operator_id' => $booking->operator_id],
                [
                    'available_amount' => 0,
                    'pending_amount' => 0,
                    'total_earned' => 0,
                    'total_withdrawn' => 0,
                ]
            );

            // Check if funds already settled for this booking
            $existingTransaction = WalletTransaction::where('booking_id', $booking->id)
                ->where('status', 'completed')
                ->first();

            if (!$existingTransaction) {
                // Add funds to wallet
                $wallet->addFunds($booking->total_price, $booking->id, 'Safari booking settlement');
                
                // Immediately settle since 2 days have passed
                $wallet->settleFunds($booking->total_price);
                
                // Mark transaction as completed
                WalletTransaction::where('booking_id', $booking->id)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                    ]);
            }
        }
    }
}