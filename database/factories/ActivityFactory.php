<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
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

        return [
            'title' => $this->faker->unique()->randomElement($activities),
            'icon' => null,
            'added_by' => null,
        ];
    }

    /**
     * Indicate that the activity was added by a specific user.
     */
    public function addedBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'added_by' => $user->id,
        ]);
    }
}
