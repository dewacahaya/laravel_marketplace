<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $total = 500;
        $chunkSize = 100;

        collect(range(1, $total))->chunk($chunkSize)->each(function ($chunk) {
            Product::factory(count($chunk))->create();
        });
    }
}
