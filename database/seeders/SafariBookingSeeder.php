<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Safari;
use App\Models\SafariBooking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SafariBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get travelers (users with TRAVELLER role) or create test travelers
        $travelers = User::role('TRAVELLER')->get();

        if ($travelers->isEmpty()) {
            // Create test travelers
            $travelers = User::factory(10)->create()->each(function ($user) {
                $user->assignRole('TRAVELLER');
            });
        }

        $safaris = Safari::where('is_approved', true)->where('status', true)->get();

        if ($safaris->isEmpty()) {
            $this->command->warn('No approved safaris found. Please run SafariSeeder first.');

            return;
        }

        // Create 25 bookings with mixed statuses
        $bookingConfigs = [
            // Completed bookings (for reviews)
            ['status' => 'COMPLETED', 'count' => 10],
            // Active bookings
            ['status' => 'ACTIVE', 'count' => 8],
            // Pending bookings
            ['status' => 'PENDING', 'count' => 4],
            // Cancelled bookings
            ['status' => 'CANCELLED', 'count' => 3],
        ];

        $bookingNumber = 1;

        foreach ($bookingConfigs as $config) {
            for ($i = 0; $i < $config['count']; $i++) {
                $safari = $safaris->random();
                $traveler = $travelers->random();

                $noOfAdults = rand(1, 4);
                $noOfChildren = rand(0, 2);
                $adultPrice = $safari->price_for_adult;
                $childPrice = $safari->price_for_child ?? $adultPrice * 0.7;
                $totalPrice = ($noOfAdults * $adultPrice) + ($noOfChildren * $childPrice);

                $platformFee = round($totalPrice * 0.10, 2);
                $stripeFee = round($totalPrice * 0.029 + 0.30, 2);
                $adminCommission = round($totalPrice * 0.15, 2);
                $payToOperator = round($totalPrice - $platformFee - $stripeFee - $adminCommission, 2);

                $paymentType = collect(['pay_in_full', 'deposit_auto_payment', 'deposit_manual_payment'])->random();
                $depositAmount = $paymentType !== 'pay_in_full' ? round($totalPrice * 0.30, 2) : 0;

                // Set dates based on status
                switch ($config['status']) {
                    case 'COMPLETED':
                        $checkInDate = now()->subMonths(rand(1, 3))->subDays(rand(0, 20));
                        $duration = (int) ($safari->day_duration ?? rand(3, 7));
                        $checkOutDate = (clone $checkInDate)->addDays($duration);
                        $completionDate = $checkOutDate;
                        $paymentStatus = 'completed';
                        $isFullPaid = true;
                        break;

                    case 'ACTIVE':
                        $checkInDate = now()->addDays(rand(7, 60));
                        $duration = (int) ($safari->day_duration ?? rand(3, 7));
                        $checkOutDate = (clone $checkInDate)->addDays($duration);
                        $completionDate = null;
                        $paymentStatus = 'completed';
                        $isFullPaid = $paymentType === 'pay_in_full';
                        break;

                    case 'PENDING':
                        $checkInDate = now()->addDays(rand(30, 90));
                        $duration = (int) ($safari->day_duration ?? rand(3, 7));
                        $checkOutDate = (clone $checkInDate)->addDays($duration);
                        $completionDate = null;
                        $paymentStatus = 'pending';
                        $isFullPaid = false;
                        break;

                    case 'CANCELLED':
                        $checkInDate = now()->addDays(rand(7, 30));
                        $duration = (int) ($safari->day_duration ?? rand(3, 7));
                        $checkOutDate = (clone $checkInDate)->addDays($duration);
                        $completionDate = null;
                        $paymentStatus = 'refunded';
                        $isFullPaid = false;
                        break;
                }

                $booking = SafariBooking::create([
                    'booking_id' => 'TUS-' . str_pad($bookingNumber, 6, '0', STR_PAD_LEFT),
                    'safari_id' => $safari->id,
                    'traveler_id' => $traveler->id,
                    'operator_id' => $safari->user_id,
                    'total_price' => $totalPrice,
                    'check_in_date' => $checkInDate,
                    'check_out_date' => $checkOutDate,
                    'duration' => $duration . ' days',
                    'no_of_adults' => $noOfAdults,
                    'no_of_children' => $noOfChildren,
                    'adult_price' => $adultPrice,
                    'children_price' => $childPrice,
                    'status' => $config['status'],
                    'payment_status' => $paymentStatus,
                    'completion_date' => $completionDate,
                    'platform_fee' => $platformFee,
                    'stripe_fee' => $stripeFee,
                    'additional_fee' => 0,
                    'pay_to_operator' => $payToOperator,
                    'admin_commission' => $adminCommission,
                    'deposit_amount' => $depositAmount,
                    'next_deposit_amount' => $paymentType !== 'pay_in_full' ? round($totalPrice - $depositAmount, 2) : 0,
                    'next_deposit_date' => $paymentType !== 'pay_in_full' ? $checkInDate->copy()->subDays(14) : null,
                    'payment_method_id' => null,
                    'payment_type' => $paymentType,
                    'is_full_paid' => $isFullPaid,
                    'cancel_reason' => $config['status'] === 'CANCELLED' ? 'Change of travel plans' : null,
                    'refund_amount' => $config['status'] === 'CANCELLED' ? round($totalPrice * 0.5, 2) : null,
                    'cancelled_at' => $config['status'] === 'CANCELLED' ? now()->subDays(rand(1, 7)) : null,
                ]);

                // Create payment records for non-pending bookings
                if ($config['status'] !== 'PENDING') {
                    $paymentAmount = $paymentType === 'pay_in_full' ? $totalPrice : $depositAmount;

                    Payment::create([
                        'booking_id' => $booking->id,
                        'traveler_id' => $traveler->id,
                        'transaction_id' => 'txn_' . Str::random(24),
                        'payment_date' => $checkInDate->copy()->subDays(rand(14, 30)),
                        'amount' => $paymentAmount,
                        'payment_intent_id' => 'pi_' . Str::random(24),
                        'payment_method' => 'card',
                        'payment_status' => $config['status'] === 'CANCELLED' ? 'refunded' : 'succeeded',
                        'payment_details' => json_encode([
                            'brand' => collect(['visa', 'mastercard', 'amex'])->random(),
                            'last4' => rand(1000, 9999),
                            'exp_month' => rand(1, 12),
                            'exp_year' => rand(2025, 2028),
                        ]),
                        'refund_details' => $config['status'] === 'CANCELLED' ? json_encode([
                            'refund_id' => 're_' . Str::random(24),
                            'refund_date' => now()->subDays(rand(1, 5))->toDateTimeString(),
                            'reason' => 'Customer requested cancellation',
                        ]) : null,
                    ]);
                }

                $bookingNumber++;
            }
        }

        $this->command->info('Created 25 safari bookings with payments.');
    }
}
