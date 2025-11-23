<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Product;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->where('role', 'customer')->value('id'),
            'product_id' => Product::inRandomOrder()->value('id'),
            'rating' => $this->faker->numberBetween(1, 10),
            'comment' => $this->faker->sentence(12),
        ];
    }
}
