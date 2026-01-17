<?php

namespace Database\Factories;

use App\Models\Safari;
use App\Models\SafariDate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SafariDate>
 */
class SafariDateFactory extends Factory
{
    protected $model = SafariDate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'safari_id' => Safari::factory(),
            'available_start_date' => now()->toDateString(),
            'available_end_date' => now()->addYear()->toDateString(),
            'blocked_start_date' => null,
            'blocked_end_date' => null,
        ];
    }

    /**
     * Associate with a specific safari.
     */
    public function forSafari(Safari $safari): static
    {
        return $this->state(fn (array $attributes) => [
            'safari_id' => $safari->id,
        ]);
    }

    /**
     * Set custom date range.
     */
    public function withDateRange(string $start, string $end): static
    {
        return $this->state(fn (array $attributes) => [
            'available_start_date' => $start,
            'available_end_date' => $end,
        ]);
    }

    /**
     * Set blocked dates.
     */
    public function withBlockedDates(string $start, string $end): static
    {
        return $this->state(fn (array $attributes) => [
            'blocked_start_date' => $start,
            'blocked_end_date' => $end,
        ]);
    }
}
