<?php

namespace Database\Factories;

use App\Models\AccomodationToStay;
use App\Models\CountryGuide;
use App\Models\NationalParkAndReserves;
use App\Models\Region;
use App\Models\Safari;
use App\Models\SafariType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Safari>
 */
class SafariFactory extends Factory
{
    protected $model = Safari::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $safariTitles = [
            'Classic Maasai Mara Safari Adventure',
            'Serengeti Migration Safari',
            'Luxury Big Five Experience',
            'Budget Tanzania Safari',
            'Family Safari in Kruger',
            'Photography Safari in Botswana',
            'Walking Safari in Zambia',
            'Gorilla Trekking Adventure',
            'Okavango Delta Explorer',
            'Ngorongoro Crater Discovery',
            'Kenya Wildlife Explorer',
            'South Africa Safari Circuit',
            'Ultimate East Africa Safari',
            'Namibia Desert Safari',
            'Chobe River Safari',
        ];

        $dayDuration = $this->faker->numberBetween(3, 14);
        $nightDuration = $dayDuration - 1;
        $adultPrice = $this->faker->randomElement([1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000]);
        $childPrice = round($adultPrice * 0.7, 2);

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->unique()->randomElement($safariTitles),
            'description' => $this->faker->paragraphs(4, true),
            'short_description' => $this->faker->paragraph(),
            'duration' => $dayDuration . ' days / ' . $nightDuration . ' nights',
            'day_duration' => $dayDuration,
            'night_duration' => $nightDuration,
            'location' => $this->faker->city() . ', Africa',
            'country_id' => CountryGuide::factory(),
            'region_id' => Region::factory(),
            'national_park_id' => NationalParkAndReserves::factory(),
            'price_for_adult' => $adultPrice,
            'price_for_child' => $childPrice,
            'price_for_pet' => 0,
            'lat' => $this->faker->latitude(-35, 5),
            'long' => $this->faker->longitude(15, 45),
            'best_visit_time' => $this->faker->randomElement(['June - October', 'July - September', 'December - March']),
            'health_and_safety' => $this->faker->paragraph(),
            'travel_access_info' => $this->faker->paragraph(),
            'pace_activity_level' => $this->faker->randomElement(['Relaxed', 'Moderate', 'Active']),
            'vehicle_types' => $this->faker->randomElement(['4x4 Safari Vehicle', 'Land Cruiser', 'Open Safari Jeep']),
            'travel_season' => $this->faker->randomElement(['Dry Season', 'Green Season', 'All Year']),
            'status' => true,
            'is_approved' => true,
            'is_draft' => false,
            'accomodation_id' => AccomodationToStay::factory(),
            'safari_type_id' => SafariType::factory(),
            'thumbnail' => null,
            'banner_image' => null,
            'destination' => $this->faker->randomElement(['Countries', 'Regions', 'Parks/Reserves']),
            'environment' => json_encode(['Savanna', 'Grassland']),
            'activity_level' => $this->faker->randomElement(['Relaxed', 'Moderate', 'Active']),
            'no_of_adult' => $this->faker->numberBetween(2, 8),
            'no_of_child' => $this->faker->numberBetween(0, 4),
            'availability_tag' => $this->faker->randomElement(['Last-minute available', 'Limited spaces', null]),
            'impact' => $this->faker->paragraph(),
            'imapact_image' => null,
            'today_highlights' => $this->faker->paragraph(),
            'safari_included' => json_encode([
                'All game drives',
                'Park entry fees',
                'Accommodation',
                'Meals as specified',
                'Professional guide',
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
            'number_of_groups' => $this->faker->numberBetween(1, 5),
            'group_type' => $this->faker->randomElement(['Private', 'Group', 'Both']),
            'low_season_start_date' => now()->addMonths(1)->toDateString(),
            'low_season_end_date' => now()->addMonths(3)->toDateString(),
            'high_season_start_date' => now()->addMonths(4)->toDateString(),
            'high_season_end_date' => now()->addMonths(8)->toDateString(),
            'low_season_adult_price' => round($adultPrice * 0.8, 2),
            'high_season_adult_price' => $adultPrice,
            'low_season_child_price' => round($childPrice * 0.8, 2),
            'high_season_child_price' => $childPrice,
            'available_start_date' => now()->toDateString(),
            'available_end_date' => now()->addYear()->toDateString(),
            'blocked_start_date' => null,
            'blocked_end_date' => null,
            'per_date_group_limit' => $this->faker->numberBetween(1, 3),
            'total_slots' => $this->faker->numberBetween(10, 50),
            'is_featured' => $this->faker->boolean(20),
            'step_saved_status' => json_encode([
                'step1' => true,
                'step2' => true,
                'step3' => true,
                'step4' => true,
            ]),
        ];
    }

    /**
     * Indicate that the safari is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_draft' => true,
            'is_approved' => false,
            'status' => false,
        ]);
    }

    /**
     * Indicate that the safari is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'is_approved' => true,
            'status' => true,
        ]);
    }

    /**
     * Indicate that the safari is pending approval.
     */
    public function pendingApproval(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
            'is_draft' => false,
            'status' => true,
        ]);
    }

    /**
     * Indicate that the safari is a last-minute offer.
     */
    public function lastMinuteOffer(): static
    {
        return $this->state(fn (array $attributes) => [
            'availability_tag' => 'Last-minute available',
            'is_approved' => true,
            'status' => true,
        ]);
    }

    /**
     * Indicate that the safari is a luxury safari.
     */
    public function luxury(): static
    {
        return $this->state(fn (array $attributes) => [
            'price_for_adult' => $this->faker->randomElement([5000, 6000, 7000, 8000, 10000]),
            'price_for_child' => $this->faker->randomElement([3500, 4200, 4900, 5600, 7000]),
            'pace_activity_level' => 'Relaxed',
        ]);
    }

    /**
     * Indicate that the safari is a budget safari.
     */
    public function budget(): static
    {
        return $this->state(fn (array $attributes) => [
            'price_for_adult' => $this->faker->randomElement([500, 750, 1000, 1250]),
            'price_for_child' => $this->faker->randomElement([350, 525, 700, 875]),
        ]);
    }

    /**
     * Associate with a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Associate with a specific country.
     */
    public function forCountry(CountryGuide $country): static
    {
        return $this->state(fn (array $attributes) => [
            'country_id' => $country->id,
        ]);
    }

    /**
     * Associate with a specific region.
     */
    public function forRegion(Region $region): static
    {
        return $this->state(fn (array $attributes) => [
            'region_id' => $region->id,
        ]);
    }

    /**
     * Associate with a specific national park.
     */
    public function forNationalPark(NationalParkAndReserves $park): static
    {
        return $this->state(fn (array $attributes) => [
            'national_park_id' => $park->id,
        ]);
    }

    /**
     * Associate with a specific safari type.
     */
    public function forSafariType(SafariType $safariType): static
    {
        return $this->state(fn (array $attributes) => [
            'safari_type_id' => $safariType->id,
        ]);
    }

    /**
     * Associate with a specific accommodation.
     */
    public function forAccommodation(AccomodationToStay $accommodation): static
    {
        return $this->state(fn (array $attributes) => [
            'accomodation_id' => $accommodation->id,
        ]);
    }
}
