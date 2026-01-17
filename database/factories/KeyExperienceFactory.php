<?php

namespace Database\Factories;

use App\Models\KeyExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KeyExperience>
 */
class KeyExperienceFactory extends Factory
{
    protected $model = KeyExperience::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $experiences = [
            ['title' => 'Big Five Sighting', 'subtitle' => 'Spot lions, elephants, leopards, rhinos, and buffalos'],
            ['title' => 'Hot Air Balloon Safari', 'subtitle' => 'Soar above the savanna at sunrise'],
            ['title' => 'Night Game Drive', 'subtitle' => 'Discover nocturnal wildlife with spotlight'],
            ['title' => 'Bush Walk', 'subtitle' => 'Track wildlife on foot with expert guides'],
            ['title' => 'Sundowner Experience', 'subtitle' => 'Enjoy cocktails as the sun sets over the plains'],
            ['title' => 'Cultural Village Visit', 'subtitle' => 'Meet local Maasai communities'],
            ['title' => 'Great Migration', 'subtitle' => 'Witness millions of wildebeest crossing the Mara'],
            ['title' => 'Bird Watching', 'subtitle' => 'Spot over 500 bird species in diverse habitats'],
            ['title' => 'Photography Workshop', 'subtitle' => 'Learn from professional wildlife photographers'],
            ['title' => 'Conservation Experience', 'subtitle' => 'Participate in wildlife conservation projects'],
        ];

        $experience = $this->faker->unique()->randomElement($experiences);

        return [
            'title' => $experience['title'],
            'subtitle' => $experience['subtitle'],
            'thumbnail' => null,
            'image' => null,
        ];
    }
}
