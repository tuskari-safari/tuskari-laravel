<?php

namespace Database\Seeders;

use App\Models\WildLife;
use Illuminate\Database\Seeder;

class WildLifeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $animals = [
            ['name' => 'Lion', 'subtitle' => 'The King of the Jungle - Africa\'s apex predator and symbol of the savanna.'],
            ['name' => 'African Elephant', 'subtitle' => 'The largest land mammal on Earth, known for their intelligence and family bonds.'],
            ['name' => 'Leopard', 'subtitle' => 'The elusive spotted cat of the African bush, master of stealth and camouflage.'],
            ['name' => 'Cape Buffalo', 'subtitle' => 'One of Africa\'s most dangerous animals, known as the "Black Death".'],
            ['name' => 'Black Rhinoceros', 'subtitle' => 'Critically endangered horn-bearing giant, a conservation priority.'],
            ['name' => 'Cheetah', 'subtitle' => 'The fastest land animal in the world, capable of speeds up to 70 mph.'],
            ['name' => 'Giraffe', 'subtitle' => 'The tallest mammal on the planet, gracefully browsing treetops.'],
            ['name' => 'Zebra', 'subtitle' => 'Africa\'s striped horse, each with unique patterns like human fingerprints.'],
            ['name' => 'Hippopotamus', 'subtitle' => 'The river horse of Africa, surprisingly dangerous despite their docile appearance.'],
            ['name' => 'Wildebeest', 'subtitle' => 'Star of the Great Migration, traveling over 1,000 miles annually.'],
            ['name' => 'Spotted Hyena', 'subtitle' => 'Africa\'s most successful predator with complex social structures.'],
            ['name' => 'Nile Crocodile', 'subtitle' => 'Ancient reptile lurking in African waterways, apex river predator.'],
            ['name' => 'Mountain Gorilla', 'subtitle' => 'Our closest relative in the African rainforest, gentle giants of the mist.'],
            ['name' => 'African Wild Dog', 'subtitle' => 'Africa\'s most endangered large carnivore with remarkable hunting success.'],
            ['name' => 'Warthog', 'subtitle' => 'The comical pig of the African savanna, often seen running with tail erect.'],
        ];

        foreach ($animals as $animal) {
            WildLife::firstOrCreate(
                ['name' => $animal['name']],
                [
                    'name' => $animal['name'],
                    'subtitle' => $animal['subtitle'],
                    'thumbnail' => null,
                ]
            );
        }
    }
}
