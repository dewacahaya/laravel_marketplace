<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->where('role', 'customer')->value('id'),
            'total_price' => 0,
            'status' => $this->faker->randomElement(['Pending', 'Paid', 'Shipped', 'Completed']),
        ];
    }
}
