<?php

namespace Database\Factories;

use App\Models\AccomodationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccomodationType>
 */
class AccomodationTypeFactory extends Factory
{
    protected $model = AccomodationType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            'Lodge',
            'Tented Camp',
            'Luxury Camp',
            'Bush Camp',
            'Mobile Camp',
            'Tree House',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($types),
            'status' => true,
        ];
    }

    /**
     * Indicate that the accommodation type is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}
