<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Review> */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'user_id' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->optional()->sentence(6),
            'body' => $this->faker->optional()->paragraph(),
            'status' => Review::STATUS_APPROVED,
        ];
    }
}
