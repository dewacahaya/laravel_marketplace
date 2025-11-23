<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(100)->customer()->create();
    }
}
