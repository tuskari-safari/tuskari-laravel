<?php

namespace Database\Factories;

use App\Models\AccomodationToStay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccomodationToStay>
 */
class AccomodationToStayFactory extends Factory
{
    protected $model = AccomodationToStay::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $accommodations = [
            [
                'title' => 'Luxury Lodge',
                'description' => 'Experience world-class luxury in the heart of the African wilderness. Our luxury lodges feature private suites, gourmet dining, and panoramic views.',
                'types' => ['Private Suite', 'Infinity Pool', 'Spa', 'Fine Dining'],
            ],
            [
                'title' => 'Tented Safari Camp',
                'description' => 'Authentic safari experience in spacious canvas tents with en-suite bathrooms and all modern amenities.',
                'types' => ['Canvas Tent', 'En-suite Bathroom', 'Deck', 'Bush Views'],
            ],
            [
                'title' => 'Budget Camping',
                'description' => 'Affordable camping experience with basic facilities. Perfect for adventure seekers on a budget.',
                'types' => ['Shared Facilities', 'Camping Gear', 'Communal Area'],
            ],
            [
                'title' => 'Mobile Camp',
                'description' => 'Follow the great migration with our mobile camps that move with the wildlife.',
                'types' => ['Mobile Setup', 'Following Migration', 'Intimate Experience'],
            ],
            [
                'title' => 'Tree House Lodge',
                'description' => 'Unique elevated accommodations offering spectacular views from the treetops.',
                'types' => ['Elevated Platform', 'Panoramic Views', 'Wildlife Spotting'],
            ],
            [
                'title' => 'Eco Lodge',
                'description' => 'Sustainable accommodations that minimize environmental impact while maximizing comfort.',
                'types' => ['Solar Power', 'Water Conservation', 'Local Materials'],
            ],
        ];

        $accommodation = $this->faker->unique()->randomElement($accommodations);

        return [
            'title' => $accommodation['title'],
            'description' => $accommodation['description'],
            'types' => $accommodation['types'],
            'image' => null,
            'status' => true,
        ];
    }

    /**
     * Indicate that the accommodation is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}
