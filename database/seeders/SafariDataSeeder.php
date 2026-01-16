<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SafariDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds all safari-related data in the correct dependency order.
     */
    public function run(): void
    {
        $this->command->info('Starting Safari Platform Data Seeding...');
        $this->command->newLine();

        // Phase 1: Foundation data (no dependencies)
        $this->command->info('Phase 1: Seeding foundation data...');
        $this->call([
            RegionSeeder::class,
            SafariTypeSeeder::class,
            AccomodationTypeSeeder::class,
            AccomodationToStaySeeder::class,
            KeyExperienceSeeder::class,
            WildLifeSeeder::class,
            ActivitySeeder::class,
        ]);
        $this->command->newLine();

        // Phase 2: Location data (depends on regions)
        $this->command->info('Phase 2: Seeding location data...');
        $this->call([
            CountryGuideSeeder::class,
            NationalParkSeeder::class,
        ]);
        $this->command->newLine();

        // Phase 3: Safari data (depends on locations and foundation data)
        $this->command->info('Phase 3: Seeding safari data...');
        $this->call([
            SafariSeeder::class,
        ]);
        $this->command->newLine();

        // Phase 4: Booking and review data (depends on safaris and users)
        $this->command->info('Phase 4: Seeding booking and review data...');
        $this->call([
            SafariBookingSeeder::class,
            SafariReviewSeeder::class,
        ]);
        $this->command->newLine();

        $this->command->info('Safari Platform Data Seeding Complete!');
        $this->command->newLine();

        // Display summary
        $this->displaySummary();
    }

    /**
     * Display a summary of seeded data.
     */
    private function displaySummary(): void
    {
        $this->command->info('=== Seeding Summary ===');
        $this->command->table(
            ['Entity', 'Count'],
            [
                ['Regions', \App\Models\Region::count()],
                ['Safari Types', \App\Models\SafariType::count()],
                ['Accommodation Types', \App\Models\AccomodationType::count()],
                ['Accommodations to Stay', \App\Models\AccomodationToStay::count()],
                ['Key Experiences', \App\Models\KeyExperience::count()],
                ['Wildlife Animals', \App\Models\WildLife::count()],
                ['Activities', \App\Models\Activity::count()],
                ['Country Guides', \App\Models\CountryGuide::count()],
                ['National Parks', \App\Models\NationalParkAndReserves::count()],
                ['Safaris', \App\Models\Safari::count()],
                ['Safari Journeys', \App\Models\SafariJourney::count()],
                ['Safari Images', \App\Models\SafariImage::count()],
                ['Safari Group Pricing', \App\Models\SafariGroupPricing::count()],
                ['Wildlife Sightings', \App\Models\SafariWildlifeSight::count()],
                ['Bookings', \App\Models\SafariBooking::count()],
                ['Payments', \App\Models\Payment::count()],
                ['Reviews', \App\Models\SafariReview::count()],
            ]
        );
    }
}
