<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\VendorProfile;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // === 10 VENDOR ===
        $vendors = User::factory()
            ->count(10)
            ->state(['role' => 'vendor'])
            ->create();

        foreach ($vendors as $vendor) {
            $data = VendorProfile::factory()->make()->toArray();
            $data['user_id'] = $vendor->id;
            VendorProfile::create($data);
        }

        // === 100 CUSTOMER ===
        $customers = User::factory()
            ->count(100)
            ->state(['role' => 'customer'])
            ->create();

        foreach ($customers as $customer) {
            $data = CustomerProfile::factory()->make()->toArray();
            $data['user_id'] = $customer->id;
            CustomerProfile::create($data);
        }

        // === ADMIN ===
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
    }
}
