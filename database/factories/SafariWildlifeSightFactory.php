<?php

namespace Database\Factories;

use App\Models\Safari;
use App\Models\SafariWildlifeSight;
use App\Models\WildLife;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SafariWildlifeSight>
 */
class SafariWildlifeSightFactory extends Factory
{
    protected $model = SafariWildlifeSight::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $probabilities = [
            'Very High',
            'High',
            'Moderate',
            'Low',
            'Rare',
        ];

        return [
            'safari_id' => Safari::factory(),
            'animal_id' => WildLife::factory(),
            'probability' => $this->faker->randomElement($probabilities),
            'note' => $this->faker->sentence(),
        ];
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
     * Associate with a specific animal.
     */
    public function forAnimal(WildLife $animal): static
    {
        return $this->state(fn (array $attributes) => [
            'animal_id' => $animal->id,
        ]);
    }

    /**
     * Set very high probability of sighting.
     */
    public function veryHighProbability(): static
    {
        return $this->state(fn (array $attributes) => [
            'probability' => 'Very High',
        ]);
    }

    /**
     * Set high probability of sighting.
     */
    public function highProbability(): static
    {
        return $this->state(fn (array $attributes) => [
            'probability' => 'High',
        ]);
    }

    /**
     * Set moderate probability of sighting.
     */
    public function moderateProbability(): static
    {
        return $this->state(fn (array $attributes) => [
            'probability' => 'Moderate',
        ]);
    }

    /**
     * Set low probability of sighting.
     */
    public function lowProbability(): static
    {
        return $this->state(fn (array $attributes) => [
            'probability' => 'Low',
        ]);
    }

    /**
     * Set rare probability of sighting.
     */
    public function rareProbability(): static
    {
        return $this->state(fn (array $attributes) => [
            'probability' => 'Rare',
        ]);
    }
}
