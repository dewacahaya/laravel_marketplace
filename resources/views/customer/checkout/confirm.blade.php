@extends('dashboard')

@section('cust_content')
<div class="container mx-auto px-4 py-8 md:py-4">
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('customer.checkout.process') }}" method="POST">
        @csrf
        <div class="flex flex-col lg:flex-row gap-8">

            <div class="w-full lg:w-2/3">
                <div class="bg-white shadow-xl rounded-lg p-6">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Your Order Details</h3>

                    <div class="space-y-4">
                        @foreach ($cart as $item)
                            <div class="flex justify-between items-start border-b pb-2 last:border-b-0">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900">{{ $item['name'] }}</span>
                                    <span class="text-sm text-gray-500">{{ $item['quantity'] }} x Rp {{ number_format($item['price']) }}</span>
                                </div>
                                <span class="font-bold text-indigo-600">Rp {{ number_format($item['subtotal']) }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Total Harga --}}
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center text-xl font-bold text-gray-800">
                            <span>GRAND TOTAL:</span>
                            <span class="text-3xl text-indigo-700">Rp {{ number_format($total) }}</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="bg-white shadow-xl rounded-lg p-6">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Shipping Information</h3>

                    {{-- Input Nama (Diambil dari Auth User, tidak disimpan di Profile) --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                        {{-- Menggunakan Auth::user()->name untuk default --}}
                        <input type="text" name="name" id="name" required
                            class="mt-1 text-black block w-full border border-gray-300 rounded-md shadow-sm p-2"
                            value="{{ old('name', Auth::user()->name) }}">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Input Phone (Diambil dari Profile) --}}
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone" id="phone" required
                            class="mt-1 text-black block w-full border border-gray-300 rounded-md shadow-sm p-2"
                            value="{{ old('phone', $profile->phone ?? '') }}">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Input Alamat (Diambil dari Profile) --}}
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address" id="address" rows="3" required
                            class="mt-1 text-black block w-full border border-gray-300 rounded-md shadow-sm p-2 resize-none">{{ old('address', $profile->address ?? '') }}</textarea>
                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Input Catatan --}}
                    {{-- <div class="mb-6">
                        <label for="note" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                        <textarea name="note" id="note" rows="2"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 resize-none">{{ old('note') }}</textarea>
                        @error('note')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div> --}}

                    {{-- Tombol Submit --}}
                    <button type="submit"
                        class="w-full py-3 bg-green-600 text-white font-semibold text-lg rounded-xl hover:bg-green-700 transition duration-200 ease-in-out shadow-lg transform hover:scale-[1.01]">
                        Confirm Order
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
