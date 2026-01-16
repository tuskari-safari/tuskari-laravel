<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            [
                'name' => 'East Africa',
                'description' => 'Home to the Great Migration and iconic safari destinations including Kenya, Tanzania, Uganda, and Rwanda. Experience diverse landscapes from savannas to mountains.',
            ],
            [
                'name' => 'Southern Africa',
                'description' => 'Discover world-class wildlife in South Africa, Botswana, Zimbabwe, Zambia, and Namibia. From the Okavango Delta to Victoria Falls.',
            ],
            [
                'name' => 'West Africa',
                'description' => 'Explore the lesser-known wildlife areas of Ghana, Senegal, and The Gambia. Unique cultural experiences and diverse ecosystems await.',
            ],
            [
                'name' => 'Central Africa',
                'description' => 'Venture into the heart of Africa with gorilla trekking in the Congo Basin, Gabon\'s pristine rainforests, and the Central African Republic.',
            ],
        ];

        foreach ($regions as $region) {
            Region::firstOrCreate(
                ['slug' => Str::slug($region['name'])],
                [
                    'name' => $region['name'],
                    'slug' => Str::slug($region['name']),
                    'description' => $region['description'],
                    'image' => null,
                    'status' => true,
                ]
            );
        }
    }
}
