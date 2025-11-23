<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * ===============================
     *  CUSTOMER REGISTER FORM
     * ===============================
     */
    public function createCustomer()
    {
        return view('auth.register-customer');
    }

    /**
     * ===============================
     *  STORE CUSTOMER
     * ===============================
     */
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|string|max:20',
            'address'   => 'required|string|max:255',
            'password'  => 'required|confirmed|min:6',
        ]);

        // Buat user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        // Buat profile customer
        CustomerProfile::create([
            'user_id' => $user->id,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        event(new Registered($user));

        auth()->login($user);

        return redirect()->route('dashboard');
    }

    /**
     * ===============================
     *  VENDOR REGISTER FORM
     * ===============================
     */
    public function createVendor()
    {
        return view('auth.register-vendor');
    }

    /**
     * ===============================
     *  STORE VENDOR
     * ===============================
     */
    public function storeVendor(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'store_name'  => 'required|string|max:255',
            'address'     => 'required|string|max:255',
            'password'    => 'required|confirmed|min:6',
        ]);

        // Buat user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'vendor',
        ]);

        // Buat profile vendor
        VendorProfile::create([
            'user_id'    => $user->id,
            'store_name' => $request->store_name,
            'address'    => $request->address,
        ]);

        event(new Registered($user));

        auth()->login($user);

        return redirect()->route('vendor.dashboard');
    }
}
