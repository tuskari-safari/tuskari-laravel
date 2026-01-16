<?php

namespace Database\Seeders;

use App\Models\KeyExperience;
use Illuminate\Database\Seeder;

class KeyExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $experiences = [
            [
                'title' => 'Big Five Sighting',
                'subtitle' => 'Spot lions, elephants, leopards, rhinos, and buffalos - Africa\'s most iconic wildlife.',
            ],
            [
                'title' => 'Hot Air Balloon Safari',
                'subtitle' => 'Soar above the savanna at sunrise for breathtaking aerial views of wildlife and landscapes.',
            ],
            [
                'title' => 'Night Game Drive',
                'subtitle' => 'Discover nocturnal wildlife with spotlights - see leopards, hyenas, and other creatures of the night.',
            ],
            [
                'title' => 'Bush Walk',
                'subtitle' => 'Track wildlife on foot with expert armed guides for an intimate connection with nature.',
            ],
            [
                'title' => 'Sundowner Experience',
                'subtitle' => 'Enjoy cocktails and canap\u00e9s as the sun sets over the African plains.',
            ],
            [
                'title' => 'Cultural Village Visit',
                'subtitle' => 'Meet local Maasai, Samburu, or Himba communities and learn about traditional ways of life.',
            ],
            [
                'title' => 'Great Migration',
                'subtitle' => 'Witness millions of wildebeest and zebras crossing the Mara River in one of nature\'s greatest spectacles.',
            ],
            [
                'title' => 'Bird Watching',
                'subtitle' => 'Spot over 500 bird species in diverse habitats from wetlands to savannas.',
            ],
            [
                'title' => 'Photography Workshop',
                'subtitle' => 'Learn from professional wildlife photographers with tips on capturing the perfect shot.',
            ],
            [
                'title' => 'Conservation Experience',
                'subtitle' => 'Participate in wildlife conservation projects and learn about protection efforts.',
            ],
        ];

        foreach ($experiences as $experience) {
            KeyExperience::firstOrCreate(
                ['title' => $experience['title']],
                [
                    'title' => $experience['title'],
                    'subtitle' => $experience['subtitle'],
                    'thumbnail' => null,
                    'image' => null,
                ]
            );
        }
    }
}
