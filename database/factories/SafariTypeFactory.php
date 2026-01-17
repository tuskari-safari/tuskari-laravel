<?php

namespace Database\Factories;

use App\Models\SafariType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SafariType>
 */
class SafariTypeFactory extends Factory
{
    protected $model = SafariType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            ['title' => 'Wildlife Safari', 'subtitle' => 'Experience the Big Five and more in their natural habitat'],
            ['title' => 'Photography Safari', 'subtitle' => 'Capture stunning wildlife moments with expert guidance'],
            ['title' => 'Luxury Safari', 'subtitle' => 'Indulge in 5-star accommodations and exclusive experiences'],
            ['title' => 'Budget Safari', 'subtitle' => 'Affordable adventures without compromising on experience'],
            ['title' => 'Family Safari', 'subtitle' => 'Kid-friendly adventures for the whole family'],
            ['title' => 'Walking Safari', 'subtitle' => 'Get closer to nature on foot with expert guides'],
            ['title' => 'Birding Safari', 'subtitle' => 'Discover rare and exotic bird species'],
            ['title' => 'Cultural Safari', 'subtitle' => 'Immerse yourself in local cultures and traditions'],
        ];

        $type = $this->faker->unique()->randomElement($types);

        return [
            'title' => $type['title'],
            'subtitle' => $type['subtitle'],
            'thumbnail' => null,
            'button_text' => 'Explore ' . $type['title'],
        ];
    }
}
