<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Support\Facades\Hash;

class VendorService
{
    public function getAll()
    {
        return User::where('role', 'vendor')
            ->with('vendorProfile')
            ->latest()
            ->paginate(10);
    }

    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'vendor',
            'password' => Hash::make($data['password']),
        ]);

        VendorProfile::create([
            'user_id' => $user->id,
            'store_name' => $data['store_name'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        return $user;
    }

    public function find($id)
    {
        return User::where('role', 'vendor')
            ->with('vendorProfile')
            ->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $vendor = $this->find($id);

        // update user
        $vendor->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => isset($data['password'])
                ? Hash::make($data['password'])
                : $vendor->password,
        ]);

        // update vendor profile
        $vendor->vendorProfile()->update([
            'store_name' => $data['store_name'] ?? $vendor->vendorProfile->store_name,
            'address' => $data['address'] ?? $vendor->vendorProfile->address,
        ]);

        return $vendor;
    }

    public function delete($id)
    {
        $vendor = $this->find($id);
        return $vendor->delete(); // profile ikut delete via cascade
    }
}
