<?php

namespace Database\Factories;

use App\Models\CountryGuide;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CountryGuide>
 */
class CountryGuideFactory extends Factory
{
    protected $model = CountryGuide::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countries = [
            ['name' => 'Kenya', 'subtitle' => 'Home of the Maasai Mara and the Great Migration'],
            ['name' => 'Tanzania', 'subtitle' => 'Experience the Serengeti and Ngorongoro Crater'],
            ['name' => 'South Africa', 'subtitle' => 'Diverse wildlife from Kruger to the Cape'],
            ['name' => 'Botswana', 'subtitle' => 'Pristine wilderness of the Okavango Delta'],
            ['name' => 'Namibia', 'subtitle' => 'Desert-adapted wildlife and stunning landscapes'],
            ['name' => 'Uganda', 'subtitle' => 'Mountain gorilla trekking in misty forests'],
            ['name' => 'Rwanda', 'subtitle' => 'The land of a thousand hills and gorillas'],
        ];

        $country = $this->faker->unique()->randomElement($countries);

        return [
            'region_id' => Region::factory(),
            'name' => $country['name'],
            'slug' => Str::slug($country['name']),
            'subtitle' => $country['subtitle'],
            'banner_image' => null,
            'thumbnail' => null,
            'content_details' => json_encode([
                'overview' => $this->faker->paragraphs(3, true),
                'climate' => $this->faker->paragraph(),
                'best_time' => $this->faker->paragraph(),
            ]),
            'unique_experience_title' => 'Unique Experiences in ' . $country['name'],
            'unique_experience_desc' => $this->faker->paragraph(),
            'unique_experience' => json_encode([
                ['title' => $this->faker->sentence(3), 'description' => $this->faker->sentence()],
                ['title' => $this->faker->sentence(3), 'description' => $this->faker->sentence()],
            ]),
            'middle_sec_title' => 'Discover ' . $country['name'],
            'middle_sec_subtitle' => $this->faker->sentence(),
            'bottom_banner' => json_encode([
                'title' => 'Ready to explore ' . $country['name'] . '?',
                'subtitle' => $this->faker->sentence(),
            ]),
            'faqs' => json_encode([
                ['question' => 'What is the best time to visit?', 'answer' => $this->faker->paragraph()],
                ['question' => 'Do I need a visa?', 'answer' => $this->faker->paragraph()],
                ['question' => 'What vaccinations are required?', 'answer' => $this->faker->paragraph()],
            ]),
            'status' => true,
        ];
    }

    /**
     * Indicate that the country guide is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Associate with an existing region.
     */
    public function forRegion(Region $region): static
    {
        return $this->state(fn (array $attributes) => [
            'region_id' => $region->id,
        ]);
    }
}
