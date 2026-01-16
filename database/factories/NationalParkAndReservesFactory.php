<?php

namespace Database\Factories;

use App\Models\CountryGuide;
use App\Models\NationalParkAndReserves;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NationalParkAndReserves>
 */
class NationalParkAndReservesFactory extends Factory
{
    protected $model = NationalParkAndReserves::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $parks = [
            ['name' => 'Maasai Mara National Reserve', 'type' => 'private_reserve', 'short' => 'Kenya\'s premier wildlife destination'],
            ['name' => 'Serengeti National Park', 'type' => 'national_park', 'short' => 'Endless plains of Tanzania'],
            ['name' => 'Kruger National Park', 'type' => 'national_park', 'short' => 'South Africa\'s flagship park'],
            ['name' => 'Okavango Delta', 'type' => 'private_reserve', 'short' => 'Botswana\'s inland delta paradise'],
            ['name' => 'Ngorongoro Crater', 'type' => 'national_park', 'short' => 'World\'s largest intact caldera'],
            ['name' => 'Amboseli National Park', 'type' => 'national_park', 'short' => 'Best views of Mount Kilimanjaro'],
            ['name' => 'Etosha National Park', 'type' => 'national_park', 'short' => 'Namibia\'s salt pan wilderness'],
            ['name' => 'Chobe National Park', 'type' => 'national_park', 'short' => 'Largest elephant population in Africa'],
        ];

        $park = $this->faker->unique()->randomElement($parks);

        return [
            'country_id' => CountryGuide::factory(),
            'name' => $park['name'],
            'type' => $park['type'],
            'short_description' => $park['short'],
            'title' => $park['name'],
            'title_2' => 'Explore ' . $park['name'],
            'subtitle' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(4, true),
            'location' => $this->faker->city() . ', ' . $this->faker->country(),
            'lat' => $this->faker->latitude(-35, 5),
            'long' => $this->faker->longitude(15, 45),
            'banner_image' => null,
            'middle_banner_image' => null,
            'thumbnail' => null,
            'category' => json_encode(['Safari', 'Wildlife', 'Nature']),
            'wildlife_highlights_title' => 'Wildlife Highlights',
            'wildlife_highlights_desc' => $this->faker->paragraph(),
            'best_visit_time' => json_encode([
                ['month' => 'June - October', 'description' => 'Dry season - best for wildlife viewing'],
                ['month' => 'November - May', 'description' => 'Green season - great for bird watching'],
            ]),
            'wild_lives_ids' => null,
            'accomodation_ids' => null,
            'traveler_tips' => json_encode([
                ['tip' => 'Bring binoculars for better wildlife viewing'],
                ['tip' => 'Pack layers as temperatures vary'],
                ['tip' => 'Book game drives in advance during peak season'],
            ]),
            'unique_experience' => json_encode([
                ['title' => 'Sunrise Game Drive', 'description' => $this->faker->sentence()],
                ['title' => 'Bush Breakfast', 'description' => $this->faker->sentence()],
            ]),
            'impact' => $this->faker->paragraph(),
            'impact_image' => null,
            'is_most_popular' => $this->faker->boolean(30),
            'is_hidden_gem' => $this->faker->boolean(20),
            'faqs' => json_encode([
                ['question' => 'What animals can I see?', 'answer' => $this->faker->paragraph()],
                ['question' => 'How do I get there?', 'answer' => $this->faker->paragraph()],
            ]),
            'status' => true,
            'is_hidden' => false,
        ];
    }

    /**
     * Indicate that the park is a national park.
     */
    public function nationalPark(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'national_park',
        ]);
    }

    /**
     * Indicate that the park is a private reserve.
     */
    public function privateReserve(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'private_reserve',
        ]);
    }

    /**
     * Indicate that the park is most popular.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_most_popular' => true,
        ]);
    }

    /**
     * Indicate that the park is a hidden gem.
     */
    public function hiddenGem(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_hidden_gem' => true,
        ]);
    }

    /**
     * Associate with an existing country.
     */
    public function forCountry(CountryGuide $country): static
    {
        return $this->state(fn (array $attributes) => [
            'country_id' => $country->id,
        ]);
    }

    /**
     * Indicate that the park is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}
