<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sudah diamankan oleh middleware role:admin
    }

    public function rules(): array
    {
        $vendorId = $this->route('vendor'); // untuk pengecualian unik pada update

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $vendorId,
            'password' => $this->isMethod('post')
                ? 'required|min:6'
                : 'nullable|min:6',

            // Vendor Profile
            'store_name' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
        ];
    }
}
