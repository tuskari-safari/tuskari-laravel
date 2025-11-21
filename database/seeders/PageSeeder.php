<?php

namespace Database\Seeders;

use App\Models\CmsManagement;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'tag' => 'What is Tuskari',
                'title' => 'Your Gateway to Africa’s Greatest Adventures',
            ],
            [
                'tag' => 'How It Works',
                'title' => 'How Tuskari Works.',
            ],
            [
                'tag' => 'Why Book With Tuskari',
                'title' => 'Why Book With Tuskari',
            ],
            [
                'tag' => 'Trip Regions',
                'title' => 'Explore By Region',
            ],
            [
                'tag' => 'Operator Highlights',
                'title' => 'Meet the People Behind the Journeys',
            ],
            [
                'tag' => 'Why Travel With Us',
                'title' => 'Why Travel With Us',
            ],
            [
                'tag' => 'Why Tuskari Exists',
                'title' => 'Built from real journeys, inspired by real people.',
            ],
            [
                'tag' => 'Footer',
            ],
            [
                'tag' => 'Why Join Tuskari',
                'title' => 'Real travellers. Direct bookings. Fairer margins.'
            ],
            [
                'tag' => 'What You Can List',
                'title' => 'We welcome a wide range of local travel businesses — as long as it’s genuine and locally run',
            ],
            [
               'tag' => 'Built for You' ,
               'title' => 'A dashboard designed for real operators.',
            ],
            [
                'tag' => 'More of the money goes',
                'title' => 'More of the money goes to the people who make your trip special.',
            ],
            [
                'tag' => 'Where Safari Born',
                'title' => 'Where Safari Was Born',
            ],
            [
                'tag' => 'Mara Region',
                'title' => 'The Mara Region',
            ],
            [
                'tag' => 'Laikipia and Northern Kenya',
                'title' => 'Laikipia and Northern Kenya',
            ],
            [
                'tag' => 'Kenyan Coast',
                'title' => 'Kenyan Coast',
            ],
        ];

        foreach ($pages as $page) {
            CmsManagement::create($page);
        }
    }
}
