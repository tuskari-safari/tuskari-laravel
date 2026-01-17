<?php

namespace Database\Factories;

use App\Models\Safari;
use App\Models\SafariBooking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SafariBooking>
 */
class SafariBookingFactory extends Factory
{
    protected $model = SafariBooking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $noOfAdults = $this->faker->numberBetween(1, 4);
        $noOfChildren = $this->faker->numberBetween(0, 2);
        $adultPrice = $this->faker->randomFloat(2, 1500, 5000);
        $childPrice = round($adultPrice * 0.7, 2);
        $totalPrice = ($noOfAdults * $adultPrice) + ($noOfChildren * $childPrice);
        $platformFee = round($totalPrice * 0.10, 2);
        $stripeFee = round($totalPrice * 0.029 + 0.30, 2);
        $adminCommission = round($totalPrice * 0.15, 2);
        $payToOperator = $totalPrice - $platformFee - $stripeFee - $adminCommission;

        $checkInDate = $this->faker->dateTimeBetween('+1 week', '+6 months');
        $duration = $this->faker->numberBetween(3, 10);
        $checkOutDate = (clone $checkInDate)->modify("+{$duration} days");

        return [
            'booking_id' => 'TUS-' . strtoupper(Str::random(8)),
            'safari_id' => Safari::factory(),
            'traveler_id' => User::factory(),
            'operator_id' => User::factory(),
            'total_price' => $totalPrice,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'duration' => $duration . ' days',
            'no_of_adults' => $noOfAdults,
            'no_of_children' => $noOfChildren,
            'adult_price' => $adultPrice,
            'children_price' => $childPrice,
            'status' => 'PENDING',
            'payment_status' => 'pending',
            'completion_date' => null,
            'platform_fee' => $platformFee,
            'stripe_fee' => $stripeFee,
            'additional_fee' => 0,
            'pay_to_operator' => $payToOperator,
            'admin_commission' => $adminCommission,
            'deposit_amount' => round($totalPrice * 0.30, 2),
            'next_deposit_amount' => null,
            'next_deposit_date' => null,
            'payment_method_id' => null,
            'payment_type' => 'pay_in_full',
            'is_full_paid' => false,
            'cancel_reason' => null,
            'refund_amount' => null,
            'cancelled_at' => null,
        ];
    }

    /**
     * Indicate that the booking is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'PENDING',
            'payment_status' => 'pending',
            'is_full_paid' => false,
        ]);
    }

    /**
     * Indicate that the booking is active (confirmed).
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ACTIVE',
            'payment_status' => 'completed',
            'is_full_paid' => true,
        ]);
    }

    /**
     * Indicate that the booking is completed.
     */
    public function completed(): static
    {
        $checkInDate = $this->faker->dateTimeBetween('-3 months', '-1 week');
        $duration = $this->faker->numberBetween(3, 10);
        $checkOutDate = (clone $checkInDate)->modify("+{$duration} days");

        return $this->state(fn (array $attributes) => [
            'status' => 'COMPLETED',
            'payment_status' => 'completed',
            'is_full_paid' => true,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'completion_date' => $checkOutDate,
        ]);
    }

    /**
     * Indicate that the booking is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(function (array $attributes) {
            $refundAmount = round($attributes['total_price'] * 0.5, 2);

            return [
                'status' => 'CANCELLED',
                'payment_status' => 'refunded',
                'cancel_reason' => $this->faker->sentence(),
                'refund_amount' => $refundAmount,
                'cancelled_at' => now(),
            ];
        });
    }

    /**
     * Set payment type to pay in full.
     */
    public function payInFull(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_type' => 'pay_in_full',
        ]);
    }

    /**
     * Set payment type to deposit with auto payment.
     */
    public function depositAutoPayment(): static
    {
        return $this->state(function (array $attributes) {
            $depositAmount = round($attributes['total_price'] * 0.30, 2);
            $nextDepositAmount = $attributes['total_price'] - $depositAmount;

            return [
                'payment_type' => 'deposit_auto_payment',
                'deposit_amount' => $depositAmount,
                'next_deposit_amount' => $nextDepositAmount,
                'next_deposit_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
                'is_full_paid' => false,
            ];
        });
    }

    /**
     * Set payment type to deposit with manual payment.
     */
    public function depositManualPayment(): static
    {
        return $this->state(function (array $attributes) {
            $depositAmount = round($attributes['total_price'] * 0.30, 2);
            $nextDepositAmount = $attributes['total_price'] - $depositAmount;

            return [
                'payment_type' => 'deposit_manual_payment',
                'deposit_amount' => $depositAmount,
                'next_deposit_amount' => $nextDepositAmount,
                'next_deposit_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
                'is_full_paid' => false,
            ];
        });
    }

    /**
     * Associate with a specific safari.
     */
    public function forSafari(Safari $safari): static
    {
        return $this->state(fn (array $attributes) => [
            'safari_id' => $safari->id,
            'operator_id' => $safari->user_id,
        ]);
    }

    /**
     * Associate with a specific traveler.
     */
    public function forTraveler(User $traveler): static
    {
        return $this->state(fn (array $attributes) => [
            'traveler_id' => $traveler->id,
        ]);
    }

    /**
     * Associate with a specific operator.
     */
    public function forOperator(User $operator): static
    {
        return $this->state(fn (array $attributes) => [
            'operator_id' => $operator->id,
        ]);
    }
}
