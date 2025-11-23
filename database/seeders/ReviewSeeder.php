<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $chunkSize = 200;

        collect(range(1, 1000))->chunk($chunkSize)->each(function ($chunk) {
            Review::factory(count($chunk))->create();
        });
    }
}
