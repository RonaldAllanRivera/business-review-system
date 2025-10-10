<?php

namespace Database\Factories;

use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Business> */
class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company();
        return [
            'name' => $name,
            'slug' => Str::slug($name.'-'.Str::random(6)),
            'description' => $this->faker->optional()->paragraph(),
            'rating' => $this->faker->randomFloat(2, 0, 5),
        ];
    }
}
