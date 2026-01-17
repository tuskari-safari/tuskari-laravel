<?php

namespace Database\Factories;

use App\Models\Safari;
use App\Models\SafariImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SafariImage>
 */
class SafariImageFactory extends Factory
{
    protected $model = SafariImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'safari_id' => Safari::factory(),
            'images' => null,
            'thumbnail' => null,
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
}
