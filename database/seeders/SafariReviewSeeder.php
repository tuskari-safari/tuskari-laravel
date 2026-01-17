<?php

namespace Database\Seeders;

use App\Models\SafariBooking;
use App\Models\SafariReview;
use Illuminate\Database\Seeder;

class SafariReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get completed bookings that don't have reviews yet
        $completedBookings = SafariBooking::where('status', 'COMPLETED')
            ->get();

        if ($completedBookings->isEmpty()) {
            $this->command->warn('No completed bookings found. Please run SafariBookingSeeder first.');

            return;
        }

        $reviewTemplates = [
            [
                'heading' => 'Absolutely incredible experience!',
                'details' => 'This safari exceeded all our expectations. The guides were incredibly knowledgeable and passionate about wildlife conservation. We saw the Big Five within the first two days! The accommodations were luxurious and the food was outstanding. Would highly recommend to anyone looking for an authentic African experience.',
                'rating' => 5.0,
            ],
            [
                'heading' => 'Unforgettable adventure',
                'details' => 'From the moment we arrived, everything was perfectly organized. Our guide knew exactly where to find the wildlife and shared fascinating insights about animal behavior. The sunrise game drives were magical. This was our honeymoon trip and it couldn\'t have been more perfect.',
                'rating' => 5.0,
            ],
            [
                'heading' => 'Best family vacation ever',
                'details' => 'We traveled with our two kids (ages 8 and 12) and this safari was perfect for families. The guides were patient and educational, making it fun for the children. Seeing elephants up close was the highlight for all of us. Already planning our return trip!',
                'rating' => 5.0,
            ],
            [
                'heading' => 'A dream come true',
                'details' => 'I\'ve dreamed of an African safari since childhood, and this experience was everything I hoped for and more. The attention to detail, the quality of the vehicles, and the expertise of our guide made it truly special. Saw leopards, which was on my bucket list!',
                'rating' => 5.0,
            ],
            [
                'heading' => 'Exceptional service throughout',
                'details' => 'The team went above and beyond at every turn. From airport pickup to the final farewell, we felt cared for. The lodges were stunning with incredible views. Game viewing was excellent - we even witnessed a lion hunt! Worth every penny.',
                'rating' => 4.9,
            ],
            [
                'heading' => 'Magical African experience',
                'details' => 'The bush breakfast overlooking the savanna will stay with me forever. Our guide was a true expert who could track animals and share their stories. The accommodations blended luxury with authentic safari atmosphere. Highly recommended!',
                'rating' => 4.8,
            ],
            [
                'heading' => 'Outstanding safari adventure',
                'details' => 'This was our third safari in Africa and by far the best organized. The itinerary was well-paced with excellent wildlife viewing opportunities. Photography conditions were ideal. Our guide\'s knowledge of the ecosystem was impressive.',
                'rating' => 4.8,
            ],
            [
                'heading' => 'Wonderful wildlife encounters',
                'details' => 'We had amazing close encounters with elephants, giraffes, and a pride of lions with cubs. The hot air balloon ride was an unforgettable add-on. The staff at every lodge made us feel welcome and well-cared for.',
                'rating' => 4.7,
            ],
            [
                'heading' => 'Great value safari',
                'details' => 'For the price, this safari offered incredible value. We saw abundant wildlife, stayed in comfortable accommodations, and had a knowledgeable guide. Some of the lodges were better than others, but overall a fantastic experience.',
                'rating' => 4.5,
            ],
            [
                'heading' => 'Very good overall experience',
                'details' => 'Good safari with plenty of wildlife sightings. The guides were friendly and professional. Accommodations were comfortable. A few logistical hiccups on transfer days but nothing major. Would recommend with minor reservations.',
                'rating' => 4.3,
            ],
            [
                'heading' => 'Solid safari experience',
                'details' => 'The game viewing was excellent and we saw all of the Big Five. The lodges were nice though not as luxurious as expected. Food was good. Our guide was very good on some days but seemed rushed on others. Overall positive.',
                'rating' => 4.0,
            ],
            [
                'heading' => 'Good but room for improvement',
                'details' => 'Wildlife sightings were great - that\'s what matters most! However, some organizational aspects could be better. Communication before the trip was slow and one of the transfers was delayed. The actual safari experience was good though.',
                'rating' => 3.8,
            ],
        ];

        $reviewCount = 0;

        foreach ($completedBookings as $booking) {
            // 80% chance of leaving a review
            if (rand(1, 100) > 80) {
                continue;
            }

            // Check if review already exists
            $existingReview = SafariReview::where('safari_id', $booking->safari_id)
                ->where('user_id', $booking->traveler_id)
                ->exists();

            if ($existingReview) {
                continue;
            }

            $template = $reviewTemplates[array_rand($reviewTemplates)];
            $user = $booking->traveler;

            SafariReview::create([
                'safari_id' => $booking->safari_id,
                'user_id' => $booking->traveler_id,
                'username' => $user->name ?? ($user->first_name . ' ' . $user->last_name),
                'user_image' => $user->profile_image ?? null,
                'heading' => $template['heading'],
                'details' => $template['details'],
                'rating' => $template['rating'] - (rand(0, 3) * 0.1), // Slight variation
                'status' => true,
            ]);

            $reviewCount++;
        }

        $this->command->info("Created {$reviewCount} safari reviews.");
    }
}
