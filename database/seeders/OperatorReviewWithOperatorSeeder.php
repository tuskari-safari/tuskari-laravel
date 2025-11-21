<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OperatorReview;
use App\Models\User;

class OperatorReviewWithOperatorSeeder extends Seeder
{
    public function run()
    {
        // Get first 3 operators
        $operators = User::where('role', 'OPERATOR')->take(3)->get();
        
        if ($operators->count() > 0) {
            $reviews = [
                [
                    'operator_id' => $operators[0]->id,
                    'review_text' => 'Excellent safari experience! The guide was knowledgeable and we saw all the Big Five. Highly recommended for anyone visiting Kenya.',
                    'source' => 'From Google',
                    'status' => true,
                ],
                [
                    'operator_id' => $operators->count() > 1 ? $operators[1]->id : $operators[0]->id,
                    'review_text' => 'Amazing wildlife photography tour. The operator was professional and the accommodations were top-notch. Will definitely book again!',
                    'source' => 'From TripAdvisor',
                    'status' => true,
                ],
                [
                    'operator_id' => $operators->count() > 2 ? $operators[2]->id : $operators[0]->id,
                    'review_text' => 'Great value for money. The safari was well organized and our guide was fantastic. Saw lions, elephants, and rhinos up close.',
                    'source' => 'From Facebook',
                    'status' => true,
                ],
            ];

            foreach ($reviews as $review) {
                OperatorReview::create($review);
            }
        }
    }
}