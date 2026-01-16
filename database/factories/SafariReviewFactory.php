<?php

namespace Database\Factories;

use App\Models\Safari;
use App\Models\SafariReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SafariReview>
 */
class SafariReviewFactory extends Factory
{
    protected $model = SafariReview::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $headings = [
            'Amazing experience!',
            'Unforgettable safari adventure',
            'Best trip of my life',
            'Exceeded all expectations',
            'Incredible wildlife encounters',
            'A dream come true',
            'Perfect safari experience',
            'Magical African adventure',
            'Outstanding service and guides',
            'Worth every penny',
        ];

        $positiveDetails = [
            'Our safari was absolutely incredible. The guides were knowledgeable and passionate about wildlife conservation. We saw the Big Five within the first two days!',
            'From the moment we arrived, everything was perfectly organized. The lodges were luxurious and the game drives exceeded all our expectations.',
            'This was our third safari and by far the best. The attention to detail, the quality of the vehicles, and the expertise of our guide made it unforgettable.',
            'We had close encounters with lions, elephants, and even witnessed a leopard hunt. The photography opportunities were endless!',
            'The staff went above and beyond to make our trip special. We celebrated our anniversary with a bush dinner under the stars.',
        ];

        return [
            'safari_id' => Safari::factory(),
            'user_id' => User::factory(),
            'username' => $this->faker->name(),
            'user_image' => null,
            'heading' => $this->faker->randomElement($headings),
            'details' => $this->faker->randomElement($positiveDetails) . ' ' . $this->faker->paragraph(),
            'rating' => $this->faker->randomFloat(1, 4.0, 5.0),
            'status' => true,
        ];
    }

    /**
     * Create a 5-star review.
     */
    public function fiveStars(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 5.0,
            'heading' => 'Absolutely perfect!',
        ]);
    }

    /**
     * Create a 4-star review.
     */
    public function fourStars(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomFloat(1, 4.0, 4.9),
        ]);
    }

    /**
     * Create a 3-star review.
     */
    public function threeStars(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomFloat(1, 3.0, 3.9),
            'heading' => 'Good but could be better',
            'details' => 'The safari was good overall, but there were some areas that could be improved. ' . $this->faker->paragraph(),
        ]);
    }

    /**
     * Create a critical review.
     */
    public function critical(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomFloat(1, 1.0, 2.9),
            'heading' => 'Disappointing experience',
            'details' => 'Unfortunately, our experience did not meet our expectations. ' . $this->faker->paragraph(),
        ]);
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
     * Associate with a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
            'username' => $user->name ?? $user->first_name . ' ' . $user->last_name,
        ]);
    }

    /**
     * Indicate that the review is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}
