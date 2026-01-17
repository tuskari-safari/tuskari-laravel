<?php

namespace Database\Factories;

use App\Models\Safari;
use App\Models\SeasonalPricing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeasonalPricing>
 */
class SeasonalPricingFactory extends Factory
{
    protected $model = SeasonalPricing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $adultPrice = $this->faker->randomElement([1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000]);

        return [
            'safari_id' => Safari::factory(),
            'season' => 'LOW',
            'available_start_date' => now()->toDateString(),
            'available_end_date' => now()->addYear()->toDateString(),
            'blocked_start_date' => null,
            'blocked_end_date' => null,
            'adult_price' => $adultPrice,
            'child_price' => round($adultPrice * 0.7, 2),
        ];
    }

    /**
     * Indicate that this is a low season pricing.
     */
    public function lowSeason(): static
    {
        return $this->state(fn (array $attributes) => [
            'season' => 'LOW',
            'available_start_date' => now()->addMonths(1)->toDateString(),
            'available_end_date' => now()->addMonths(3)->toDateString(),
        ]);
    }

    /**
     * Indicate that this is a high season pricing.
     */
    public function highSeason(): static
    {
        return $this->state(function (array $attributes) {
            $adultPrice = $attributes['adult_price'] ?? 2500;

            return [
                'season' => 'HIGH',
                'adult_price' => round($adultPrice * 1.25, 2),
                'child_price' => round($adultPrice * 0.7 * 1.25, 2),
                'available_start_date' => now()->addMonths(4)->toDateString(),
                'available_end_date' => now()->addMonths(8)->toDateString(),
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
        ]);
    }

    /**
     * Set specific pricing.
     */
    public function withPricing(float $adultPrice, ?float $childPrice = null): static
    {
        return $this->state(fn (array $attributes) => [
            'adult_price' => $adultPrice,
            'child_price' => $childPrice ?? round($adultPrice * 0.7, 2),
        ]);
    }
}
