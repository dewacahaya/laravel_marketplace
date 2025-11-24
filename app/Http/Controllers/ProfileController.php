<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Memuat relasi yang sesuai berdasarkan peran (role)
        if ($user->role === 'vendor') {
            $user->load('vendorProfile');
        } else {
            $user->load('customerProfile');
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // 1. SIMPAN DATA USER (Name & Email)
        $userData = $request->only(['name', 'email']);
        $user->fill($userData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // 2. SIMPAN DATA PROFIL KHUSUS BERDASARKAN ROLE
        if ($user->role === 'customer') {
            $profileData = $request->only(['phone', 'address']);
            $user->customerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        } elseif ($user->role === 'vendor') {
            $profileData = $request->only(['store_name', 'address']);
            $user->vendorProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
