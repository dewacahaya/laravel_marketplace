<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class ProductFactory extends Factory
{
    public function definition(): array
    {

        $name = $this->faker->words(2, true);
        $slug = Str::slug($name . '-' . $this->faker->unique()->numberBetween(1, 9999));

        return [
            'vendor_id' => User::inRandomOrder()->where('role', 'vendor')->value('id'),
            'category_id' => Category::inRandomOrder()->value('id'),
            'name' => $name,
            'slug' => $slug,
            'price' => $this->faker->numberBetween(10000, 2000000),
            'stock' => $this->faker->numberBetween(0, 100),
            'description' => $this->faker->paragraph(2),
            'status' => $this->faker->randomElement(['active', 'draft', 'out_of_stock']),
            'image' => 'products/default.png',
        ];
    }
}
