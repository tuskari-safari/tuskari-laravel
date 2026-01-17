<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            'Game Drive',
            'Walking Safari',
            'Night Safari',
            'Bird Watching',
            'Photography Tour',
            'Cultural Visit',
            'Hot Air Balloon',
            'Boat Safari',
            'Horseback Safari',
            'Mountain Hiking',
            'Fishing Excursion',
            'Stargazing',
        ];

        foreach ($activities as $activity) {
            Activity::firstOrCreate(
                ['title' => $activity],
                [
                    'title' => $activity,
                    'icon' => null,
                    'added_by' => null,
                ]
            );
        }
    }
}
