<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\SafariBooking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => SafariBooking::factory(),
            'traveler_id' => User::factory(),
            'transaction_id' => 'txn_' . Str::random(24),
            'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'amount' => $this->faker->randomFloat(2, 500, 10000),
            'payment_intent_id' => 'pi_' . Str::random(24),
            'payment_method' => $this->faker->randomElement(['card', 'bank_transfer']),
            'payment_status' => 'succeeded',
            'payment_details' => json_encode([
                'brand' => $this->faker->randomElement(['visa', 'mastercard', 'amex']),
                'last4' => $this->faker->numerify('####'),
                'exp_month' => $this->faker->numberBetween(1, 12),
                'exp_year' => $this->faker->numberBetween(2025, 2030),
            ]),
            'refund_details' => null,
        ];
    }

    /**
     * Indicate that the payment succeeded.
     */
    public function succeeded(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'succeeded',
        ]);
    }

    /**
     * Indicate that the payment failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'failed',
        ]);
    }

    /**
     * Indicate that the payment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'pending',
        ]);
    }

    /**
     * Indicate that the payment was refunded.
     */
    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'refunded',
            'refund_details' => json_encode([
                'refund_id' => 're_' . Str::random(24),
                'refund_date' => now()->toDateTimeString(),
                'reason' => $this->faker->sentence(),
            ]),
        ]);
    }

    /**
     * Associate with a specific booking.
     */
    public function forBooking(SafariBooking $booking): static
    {
        return $this->state(fn (array $attributes) => [
            'booking_id' => $booking->id,
            'traveler_id' => $booking->traveler_id,
            'amount' => $booking->total_price,
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
     * Set payment method to card.
     */
    public function card(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'card',
            'payment_details' => json_encode([
                'brand' => $this->faker->randomElement(['visa', 'mastercard', 'amex']),
                'last4' => $this->faker->numerify('####'),
                'exp_month' => $this->faker->numberBetween(1, 12),
                'exp_year' => $this->faker->numberBetween(2025, 2030),
            ]),
        ]);
    }

    /**
     * Set payment method to bank transfer.
     */
    public function bankTransfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'bank_transfer',
            'payment_details' => json_encode([
                'bank_name' => $this->faker->company(),
                'account_last4' => $this->faker->numerify('####'),
            ]),
        ]);
    }
}
