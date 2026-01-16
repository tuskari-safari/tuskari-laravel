<?php

namespace Database\Seeders;

use App\Models\SafariType;
use Illuminate\Database\Seeder;

class SafariTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'title' => 'Wildlife Safari',
                'subtitle' => 'Classic game drives to spot the Big Five and other iconic African wildlife in their natural habitat.',
                'button_text' => 'Explore Wildlife Safaris',
            ],
            [
                'title' => 'Photography Safari',
                'subtitle' => 'Designed for photographers with extended game viewing, special vehicle positioning, and expert guidance.',
                'button_text' => 'Explore Photography Safaris',
            ],
            [
                'title' => 'Luxury Safari',
                'subtitle' => 'Indulge in 5-star lodges, gourmet dining, spa treatments, and exclusive private experiences.',
                'button_text' => 'Explore Luxury Safaris',
            ],
            [
                'title' => 'Budget Safari',
                'subtitle' => 'Affordable camping and lodge options without compromising on incredible wildlife experiences.',
                'button_text' => 'Explore Budget Safaris',
            ],
            [
                'title' => 'Family Safari',
                'subtitle' => 'Kid-friendly activities, family rooms, and educational experiences for all ages.',
                'button_text' => 'Explore Family Safaris',
            ],
            [
                'title' => 'Walking Safari',
                'subtitle' => 'Get closer to nature on foot with armed guides, tracking wildlife through the bush.',
                'button_text' => 'Explore Walking Safaris',
            ],
            [
                'title' => 'Birding Safari',
                'subtitle' => 'Specialized tours for bird enthusiasts featuring Africa\'s incredible avian diversity.',
                'button_text' => 'Explore Birding Safaris',
            ],
            [
                'title' => 'Cultural Safari',
                'subtitle' => 'Immerse yourself in local traditions, village visits, and authentic cultural experiences.',
                'button_text' => 'Explore Cultural Safaris',
            ],
        ];

        foreach ($types as $type) {
            SafariType::firstOrCreate(
                ['title' => $type['title']],
                [
                    'title' => $type['title'],
                    'subtitle' => $type['subtitle'],
                    'thumbnail' => null,
                    'button_text' => $type['button_text'],
                ]
            );
        }
    }
}
