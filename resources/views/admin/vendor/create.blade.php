@extends('dashboard')

@section('admin_content')
    <div class="p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Add New Vendor</h2>

        <form action="{{ route('admin.vendors.store') }}" method="POST">
            @csrf

            {{-- Vendor Name --}}
            <div class="mb-4">
                <label class="block mb-1">Vendor Name</label>
                <input type="text" name="name" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700"
                    value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700"
                    value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="block mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700">
                @error('password')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Store Name --}}
            <div class="mb-4">
                <label class="block mb-1">Store Name</label>
                <input type="text" name="store_name"
                    class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700" value="{{ old('store_name') }}">
                @error('store_name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Address --}}
            <div class="mb-4">
                <label class="block mb-1">Address</label>
                <textarea name="address" rows="3" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                Save Vendor
            </button>
        </form>
    </div>
@endsection
