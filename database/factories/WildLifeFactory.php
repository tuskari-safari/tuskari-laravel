<?php

namespace Database\Factories;

use App\Models\WildLife;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WildLife>
 */
class WildLifeFactory extends Factory
{
    protected $model = WildLife::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $animals = [
            ['name' => 'Lion', 'subtitle' => 'The King of the Jungle - Africa\'s apex predator'],
            ['name' => 'African Elephant', 'subtitle' => 'The largest land mammal on Earth'],
            ['name' => 'Leopard', 'subtitle' => 'The elusive spotted cat of the African bush'],
            ['name' => 'Cape Buffalo', 'subtitle' => 'One of Africa\'s most dangerous animals'],
            ['name' => 'Black Rhinoceros', 'subtitle' => 'Critically endangered horn-bearing giant'],
            ['name' => 'Cheetah', 'subtitle' => 'The fastest land animal in the world'],
            ['name' => 'Giraffe', 'subtitle' => 'The tallest mammal on the planet'],
            ['name' => 'Zebra', 'subtitle' => 'Africa\'s striped horse with unique patterns'],
            ['name' => 'Hippopotamus', 'subtitle' => 'The river horse of Africa'],
            ['name' => 'Wildebeest', 'subtitle' => 'Star of the Great Migration'],
            ['name' => 'Hyena', 'subtitle' => 'Africa\'s most successful predator'],
            ['name' => 'Crocodile', 'subtitle' => 'Ancient reptile lurking in African waters'],
            ['name' => 'Gorilla', 'subtitle' => 'Our closest relative in the African rainforest'],
            ['name' => 'Wild Dog', 'subtitle' => 'Africa\'s most endangered large carnivore'],
            ['name' => 'Warthog', 'subtitle' => 'The comical pig of the African savanna'],
        ];

        $animal = $this->faker->unique()->randomElement($animals);

        return [
            'name' => $animal['name'],
            'subtitle' => $animal['subtitle'],
            'thumbnail' => null,
        ];
    }
}
