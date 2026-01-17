<?php

namespace Database\Seeders;

use App\Models\AccomodationToStay;
use Illuminate\Database\Seeder;

class AccomodationToStaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accommodations = [
            [
                'title' => 'Luxury Lodge',
                'description' => 'Experience world-class luxury in the heart of the African wilderness. Our luxury lodges feature private suites with panoramic views, infinity pools, world-class spas, and gourmet dining experiences. Each lodge is designed to blend seamlessly with the natural environment while providing ultimate comfort.',
                'types' => ['Private Suite', 'Infinity Pool', 'Spa', 'Fine Dining', 'Butler Service'],
            ],
            [
                'title' => 'Tented Safari Camp',
                'description' => 'Authentic safari experience in spacious canvas tents with en-suite bathrooms and all modern amenities. Fall asleep to the sounds of the African bush while enjoying comfortable beds, private decks, and stunning views. The perfect blend of adventure and comfort.',
                'types' => ['Canvas Tent', 'En-suite Bathroom', 'Private Deck', 'Bush Views', 'Outdoor Shower'],
            ],
            [
                'title' => 'Budget Camping',
                'description' => 'Affordable camping experience with basic facilities, perfect for adventure seekers on a budget. Shared facilities, camping gear provided, and communal dining areas foster a sense of community among fellow travelers.',
                'types' => ['Shared Facilities', 'Camping Gear Provided', 'Communal Dining', 'Campfire Evenings'],
            ],
            [
                'title' => 'Mobile Camp',
                'description' => 'Follow the great migration with our mobile camps that move with the wildlife. These exclusive camps offer an intimate safari experience in remote locations, bringing you closer to nature than any permanent lodge can.',
                'types' => ['Mobile Setup', 'Migration Following', 'Intimate Experience', 'Remote Locations'],
            ],
            [
                'title' => 'Tree House Lodge',
                'description' => 'Unique elevated accommodations offering spectacular views from the treetops. Wake up at eye-level with the canopy, spot wildlife from your private balcony, and enjoy a truly unique perspective on the African wilderness.',
                'types' => ['Elevated Platform', 'Panoramic Views', 'Wildlife Spotting', 'Unique Experience'],
            ],
            [
                'title' => 'Eco Lodge',
                'description' => 'Sustainable accommodations that minimize environmental impact while maximizing comfort. Solar-powered, water-conscious, and built with local materials, these lodges prove that luxury and sustainability can coexist.',
                'types' => ['Solar Power', 'Water Conservation', 'Local Materials', 'Eco-Certified', 'Community Support'],
            ],
        ];

        foreach ($accommodations as $accommodation) {
            AccomodationToStay::firstOrCreate(
                ['title' => $accommodation['title']],
                [
                    'title' => $accommodation['title'],
                    'description' => $accommodation['description'],
                    'types' => $accommodation['types'],
                    'image' => null,
                    'status' => true,
                ]
            );
        }
    }
}
