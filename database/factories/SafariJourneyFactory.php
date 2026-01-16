<?php

namespace Database\Factories;

use App\Models\NationalParkAndReserves;
use App\Models\Safari;
use App\Models\SafariJourney;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SafariJourney>
 */
class SafariJourneyFactory extends Factory
{
    protected $model = SafariJourney::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $headings = [
            'Arrival and Welcome',
            'Game Drive Adventure',
            'Morning Safari and Sundowner',
            'Full Day Game Drive',
            'Walking Safari Experience',
            'Transfer to Next Camp',
            'Lake Safari and Bird Watching',
            'Cultural Village Visit',
            'Hot Air Balloon Safari',
            'Final Game Drive and Departure',
        ];

        $meals = [
            'Breakfast, Lunch, Dinner',
            'Full Board',
            'All Meals Included',
            'Breakfast and Dinner',
            'Lunch and Dinner',
        ];

        $accommodations = [
            'Luxury Tented Camp',
            'Safari Lodge',
            'Wilderness Camp',
            'Mobile Camp',
            'Boutique Lodge',
        ];

        return [
            'safari_id' => Safari::factory(),
            'day_number' => $this->faker->numberBetween(1, 10),
            'heading' => $this->faker->randomElement($headings),
            'subtext' => $this->faker->paragraphs(2, true),
            'accommodation' => $this->faker->randomElement($accommodations),
            'meal' => $this->faker->randomElement($meals),
            'included' => $this->faker->randomElements([
                'Game drives',
                'Park fees',
                'Guide services',
                'Drinking water',
                'Airport transfers',
            ], 3, false) ? implode(', ', $this->faker->randomElements([
                'Game drives',
                'Park fees',
                'Guide services',
                'Drinking water',
                'Airport transfers',
            ], 3)) : 'Game drives, Park fees',
            'wildlife_location' => null,
            'wildlife_highlights' => $this->faker->sentence(),
            'today_highlights' => $this->faker->paragraph(),
            'status' => true,
            'no_accommodation_included' => false,
        ];
    }

    /**
     * Set a specific day number.
     */
    public function day(int $dayNumber): static
    {
        return $this->state(fn (array $attributes) => [
            'day_number' => $dayNumber,
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
     * Associate with a specific national park for wildlife location.
     */
    public function atPark(NationalParkAndReserves $park): static
    {
        return $this->state(fn (array $attributes) => [
            'wildlife_location' => $park->id,
        ]);
    }

    /**
     * Indicate no accommodation is included.
     */
    public function noAccommodation(): static
    {
        return $this->state(fn (array $attributes) => [
            'no_accommodation_included' => true,
            'accommodation' => null,
        ]);
    }

    /**
     * Indicate that the journey is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}
