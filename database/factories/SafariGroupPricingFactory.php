<?php

namespace Database\Factories;

use App\Models\Safari;
use App\Models\SafariGroupPricing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SafariGroupPricing>
 */
class SafariGroupPricingFactory extends Factory
{
    protected $model = SafariGroupPricing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'safari_id' => Safari::factory(),
            'person_type' => $this->faker->randomElement(['ADULT', 'CHILD']),
            'count' => $this->faker->numberBetween(1, 6),
            'season' => $this->faker->randomElement(['high_season', 'low_season', 'shoulder_season', 'all_year']),
            'net_price' => $this->faker->randomFloat(2, 500, 5000),
        ];
    }

    /**
     * Indicate pricing for adults.
     */
    public function forAdults(): static
    {
        return $this->state(fn (array $attributes) => [
            'person_type' => 'ADULT',
        ]);
    }

    /**
     * Indicate pricing for children.
     */
    public function forChildren(): static
    {
        return $this->state(fn (array $attributes) => [
            'person_type' => 'CHILD',
        ]);
    }

    /**
     * Set high season pricing.
     */
    public function highSeason(): static
    {
        return $this->state(fn (array $attributes) => [
            'season' => 'high_season',
        ]);
    }

    /**
     * Set low season pricing.
     */
    public function lowSeason(): static
    {
        return $this->state(fn (array $attributes) => [
            'season' => 'low_season',
        ]);
    }

    /**
     * Set shoulder season pricing.
     */
    public function shoulderSeason(): static
    {
        return $this->state(fn (array $attributes) => [
            'season' => 'shoulder_season',
        ]);
    }

    /**
     * Set all year pricing.
     */
    public function allYear(): static
    {
        return $this->state(fn (array $attributes) => [
            'season' => 'all_year',
        ]);
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
     * Set a specific group count.
     */
    public function count(int $count): static
    {
        return $this->state(fn (array $attributes) => [
            'count' => $count,
        ]);
    }

    /**
     * Set a specific price.
     */
    public function price(float $price): static
    {
        return $this->state(fn (array $attributes) => [
            'net_price' => $price,
        ]);
    }
}
