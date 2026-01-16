<?php

namespace Database\Seeders;

use App\Models\AccomodationToStay;
use App\Models\Activity;
use App\Models\CountryGuide;
use App\Models\KeyExperience;
use App\Models\NationalParkAndReserves;
use App\Models\Region;
use App\Models\Safari;
use App\Models\SafariGroupPricing;
use App\Models\SafariImage;
use App\Models\SafariJourney;
use App\Models\SafariType;
use App\Models\SafariWildlifeSight;
use App\Models\User;
use App\Models\WildLife;
use Illuminate\Database\Seeder;

class SafariSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get operators (users with SAFARI_OPERATOR role) or create test operators
        $operators = User::role('SAFARI_OPERATOR')->get();

        if ($operators->isEmpty()) {
            // Create test operators
            $operators = User::factory(3)->create()->each(function ($user) {
                $user->assignRole('SAFARI_OPERATOR');
            });
        }

        // Get reference data
        $regions = Region::all();
        $countries = CountryGuide::all();
        $parks = NationalParkAndReserves::all();
        $safariTypes = SafariType::all();
        $accommodations = AccomodationToStay::all();
        $activities = Activity::all();
        $keyExperiences = KeyExperience::all();
        $wildlife = WildLife::all();

        // Check if we have required data
        if ($regions->isEmpty() || $countries->isEmpty() || $safariTypes->isEmpty() || $accommodations->isEmpty()) {
            $this->command->warn('Required reference data is missing. Please run foundation seeders first.');

            return;
        }

        $safariData = [
            ['title' => 'Classic Maasai Mara Safari Adventure', 'days' => 5, 'price' => 2500, 'featured' => true],
            ['title' => 'Serengeti Migration Safari', 'days' => 7, 'price' => 3500, 'featured' => true],
            ['title' => 'Luxury Big Five Experience', 'days' => 6, 'price' => 5000, 'featured' => true, 'luxury' => true],
            ['title' => 'Budget Tanzania Safari', 'days' => 4, 'price' => 1200, 'budget' => true],
            ['title' => 'Family Safari in Kruger', 'days' => 5, 'price' => 2800],
            ['title' => 'Photography Safari in Botswana', 'days' => 8, 'price' => 4500],
            ['title' => 'Walking Safari in Zambia', 'days' => 4, 'price' => 2200],
            ['title' => 'Okavango Delta Explorer', 'days' => 6, 'price' => 4000, 'featured' => true],
            ['title' => 'Ngorongoro Crater Discovery', 'days' => 3, 'price' => 1800],
            ['title' => 'Kenya Wildlife Explorer', 'days' => 7, 'price' => 3000],
            ['title' => 'South Africa Safari Circuit', 'days' => 10, 'price' => 4200],
            ['title' => 'Ultimate East Africa Safari', 'days' => 12, 'price' => 5500, 'luxury' => true],
            ['title' => 'Namibia Desert Safari', 'days' => 8, 'price' => 3800],
            ['title' => 'Chobe River Safari', 'days' => 4, 'price' => 2400],
            ['title' => 'Last Minute Mara Deal', 'days' => 3, 'price' => 1500, 'last_minute' => true],
            ['title' => 'Budget Serengeti Adventure', 'days' => 5, 'price' => 1400, 'budget' => true],
            ['title' => 'Exclusive Kruger Experience', 'days' => 6, 'price' => 4800, 'luxury' => true],
            ['title' => 'Amboseli Elephant Safari', 'days' => 4, 'price' => 2100],
        ];

        foreach ($safariData as $index => $data) {
            $operator = $operators->random();
            $country = $countries->random();
            $park = $parks->where('country_id', $country->id)->first() ?? $parks->random();
            $region = $regions->first();

            $safari = Safari::create([
                'user_id' => $operator->id,
                'title' => $data['title'],
                'description' => $this->generateDescription($data['title']),
                'short_description' => 'Experience the best of African wildlife on this incredible ' . $data['days'] . '-day adventure.',
                'duration' => $data['days'] . ' days / ' . ($data['days'] - 1) . ' nights',
                'day_duration' => $data['days'],
                'night_duration' => $data['days'] - 1,
                'location' => $country->name . ', Africa',
                'country_id' => $country->id,
                'region_id' => $region->id,
                'national_park_id' => $park->id,
                'price_for_adult' => $data['price'],
                'price_for_child' => round($data['price'] * 0.7, 2),
                'price_for_pet' => 0,
                'lat' => $park->lat ?? -1.9403,
                'long' => $park->long ?? 29.8739,
                'best_visit_time' => 'June - October',
                'health_and_safety' => 'All safety protocols are followed. Vehicles are sanitized regularly.',
                'travel_access_info' => 'Airport pickup included. All ground transportation provided.',
                'pace_activity_level' => collect(['Relaxed', 'Moderate', 'Active'])->random(),
                'vehicle_types' => '4x4 Safari Vehicle',
                'travel_season' => 'Dry Season',
                'status' => true,
                'is_approved' => true,
                'is_draft' => false,
                'accomodation_id' => $accommodations->random()->id,
                'safari_type_id' => $safariTypes->random()->id,
                'thumbnail' => null,
                'banner_image' => null,
                'destination' => collect(['Countries', 'Regions', 'Parks/Reserves'])->random(),
                'environment' => json_encode(['Savanna', 'Grassland']),
                'activity_level' => collect(['Relaxed', 'Moderate', 'Active'])->random(),
                'no_of_adult' => rand(2, 6),
                'no_of_child' => rand(0, 3),
                'availability_tag' => isset($data['last_minute']) ? 'Last-minute available' : null,
                'impact' => 'A portion of your safari cost goes directly to wildlife conservation.',
                'imapact_image' => null,
                'today_highlights' => 'Game drives, wildlife photography, sundowner experiences.',
                'safari_included' => json_encode([
                    'All game drives',
                    'Park entry fees',
                    'Accommodation',
                    'Meals as specified',
                    'Professional guide',
                    'Airport transfers',
                ]),
                'safari_not_included' => json_encode([
                    'International flights',
                    'Visa fees',
                    'Travel insurance',
                    'Personal expenses',
                    'Tips and gratuities',
                ]),
                'add_ons' => json_encode([
                    ['name' => 'Hot Air Balloon Safari', 'price' => 450],
                    ['name' => 'Bush Dinner', 'price' => 150],
                ]),
                'number_of_groups' => rand(1, 5),
                'group_type' => collect(['Private', 'Group', 'Both'])->random(),
                'low_season_start_date' => now()->addMonths(1)->toDateString(),
                'low_season_end_date' => now()->addMonths(3)->toDateString(),
                'high_season_start_date' => now()->addMonths(4)->toDateString(),
                'high_season_end_date' => now()->addMonths(8)->toDateString(),
                'low_season_adult_price' => round($data['price'] * 0.8, 2),
                'high_season_adult_price' => $data['price'],
                'low_season_child_price' => round($data['price'] * 0.7 * 0.8, 2),
                'high_season_child_price' => round($data['price'] * 0.7, 2),
                'available_start_date' => now()->toDateString(),
                'available_end_date' => now()->addYear()->toDateString(),
                'per_date_group_limit' => rand(1, 3),
                'total_slots' => rand(20, 50),
                'is_featured' => isset($data['featured']),
                'step_saved_status' => json_encode([
                    'step1' => true,
                    'step2' => true,
                    'step3' => true,
                    'step4' => true,
                ]),
            ]);

            // Create safari journeys (itinerary)
            for ($day = 1; $day <= $data['days']; $day++) {
                SafariJourney::create([
                    'safari_id' => $safari->id,
                    'day_number' => $day,
                    'heading' => $this->getDayHeading($day, $data['days']),
                    'subtext' => $this->getDayDescription($day, $data['days']),
                    'accommodation' => $accommodations->random()->title,
                    'meal' => 'Breakfast, Lunch, Dinner',
                    'included' => 'Game drives, Park fees, Guide services',
                    'wildlife_location' => $parks->isNotEmpty() ? $parks->random()->id : null,
                    'wildlife_highlights' => 'Big Five sightings possible',
                    'today_highlights' => $this->getDayHighlights($day, $data['days']),
                    'status' => true,
                    'no_accommodation_included' => false,
                ]);
            }

            // Create safari images (3-6 per safari)
            $imageCount = rand(3, 6);
            for ($i = 0; $i < $imageCount; $i++) {
                SafariImage::create([
                    'safari_id' => $safari->id,
                    'images' => null,
                    'thumbnail' => null,
                ]);
            }

            // Create group pricing
            foreach (['ADULT', 'CHILD'] as $personType) {
                foreach (['high_season', 'low_season'] as $season) {
                    for ($count = 1; $count <= 4; $count++) {
                        $basePrice = $personType === 'ADULT' ? $data['price'] : $data['price'] * 0.7;
                        $seasonMultiplier = $season === 'high_season' ? 1.0 : 0.8;
                        $groupDiscount = 1 - ($count - 1) * 0.05;

                        SafariGroupPricing::create([
                            'safari_id' => $safari->id,
                            'person_type' => $personType,
                            'count' => $count,
                            'season' => $season,
                            'net_price' => round($basePrice * $seasonMultiplier * $groupDiscount, 2),
                        ]);
                    }
                }
            }

            // Create wildlife sightings
            if ($wildlife->isNotEmpty()) {
                $selectedWildlife = $wildlife->random(min(5, $wildlife->count()));
                foreach ($selectedWildlife as $animal) {
                    SafariWildlifeSight::create([
                        'safari_id' => $safari->id,
                        'animal_id' => $animal->id,
                        'probability' => collect(['Very High', 'High', 'Moderate', 'Low'])->random(),
                        'note' => 'Commonly spotted during game drives.',
                    ]);
                }
            }

            // Attach activities
            if ($activities->isNotEmpty()) {
                $safari->activity()->attach(
                    $activities->random(min(4, $activities->count()))->pluck('id')
                );
            }

            // Attach key experiences
            if ($keyExperiences->isNotEmpty()) {
                $safari->key_experiences()->attach(
                    $keyExperiences->random(min(3, $keyExperiences->count()))->pluck('id')
                );
            }
        }

        $this->command->info('Created ' . count($safariData) . ' safaris with full relationships.');
    }

    private function generateDescription(string $title): string
    {
        return "Embark on an unforgettable {$title}. This carefully crafted itinerary takes you through some of Africa's most spectacular wilderness areas, where you'll encounter incredible wildlife, stunning landscapes, and authentic cultural experiences.\n\nOur expert guides will ensure you have the best possible safari experience, from early morning game drives to relaxing sundowner experiences. Stay in handpicked accommodations that blend comfort with authentic safari atmosphere.\n\nWhether you're a first-time safari-goer or a seasoned Africa traveler, this safari promises memories that will last a lifetime.";
    }

    private function getDayHeading(int $day, int $totalDays): string
    {
        if ($day === 1) {
            return 'Arrival and Welcome';
        }
        if ($day === $totalDays) {
            return 'Final Safari and Departure';
        }

        $headings = [
            'Morning Safari Adventure',
            'Full Day Game Drive',
            'Exploration and Discovery',
            'Wildlife Encounters',
            'Bush Experience',
        ];

        return $headings[($day - 2) % count($headings)];
    }

    private function getDayDescription(int $day, int $totalDays): string
    {
        if ($day === 1) {
            return 'Arrive at the airport where you will be met by your guide. Transfer to your lodge and enjoy an afternoon game drive to begin your safari adventure.';
        }
        if ($day === $totalDays) {
            return 'Enjoy a final early morning game drive before breakfast. Check out and transfer to the airport for your onward journey, taking with you memories of an incredible safari.';
        }

        return 'Wake early for a sunrise game drive when wildlife is most active. Return to camp for breakfast and relaxation. Afternoon game drive continues until sunset, followed by sundowners in the bush.';
    }

    private function getDayHighlights(int $day, int $totalDays): string
    {
        if ($day === 1) {
            return 'Airport pickup, lodge check-in, welcome briefing, afternoon game drive';
        }
        if ($day === $totalDays) {
            return 'Sunrise game drive, bush breakfast, lodge checkout, airport transfer';
        }

        $highlights = [
            'Early morning game drive, Big Five tracking, sundowner experience',
            'Full day safari, picnic lunch in the bush, cultural visit',
            'Walking safari, bird watching, night game drive',
            'Photography session, conservation project visit, bush dinner',
        ];

        return $highlights[($day - 2) % count($highlights)];
    }
}
