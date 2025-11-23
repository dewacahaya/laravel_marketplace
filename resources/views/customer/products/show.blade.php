@extends('dashboard')

@section('cust_content')
    <div class="container mx-auto px-4 py-8 md:py-12">

        <div class="flex flex-col md:flex-row gap-8 lg:gap-12 bg-white p-6 md:p-10 rounded-xl shadow-2xl">

            {{-- Product Image Section (Left Column) --}}
            <div class="w-full md:w-5/12">
                <img src="{{ asset('storage/' . $product->image) }}"
                    class="w-full h-80 object-cover rounded-xl shadow-lg transform hover:scale-[1.02] transition duration-300 ease-in-out"
                    alt="Product Image">
            </div>

            {{-- Product Details Section (Right Column) --}}
            <div class="w-full md:w-7/12">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2 leading-tight">
                    {{ $product->name }}
                </h1>
                <div class="flex flex-row">
                    <p class="text-lg text-gray-500 mb-3 font-medium">
                        {{ $product->category->name }}
                    </p>
                    <p class="ms-4 text-lg text-gray-500 font-medium">{{ $product->vendor->name }}</p>
                </div>
                @if ($averageRating > 0)
                    <p class="mb-2 text-yellow-400">
                        ⭐ {{ $averageRating }} / 10
                        <span class="text-black">({{ $product->reviews->count() }} reviews)</span>
                    </p>
                @else
                    <p class="mb-2 text-gray-700">O rating</p>
                @endif

                <div class="border-t border-gray-200 pt-4 mb-6">
                    <h3 class="text-4xl font-bold text-green-600">
                        Rp {{ number_format($product->price) }}
                    </h3>
                </div>

                <h4 class="text-xl font-semibold text-gray-800 mb-2">Description:</h4>
                <p class="mb-8 text-gray-700 leading-relaxed">
                    {{ $product->description }}
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <form action="{{ route('customer.cart.add', $product->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto flex items-center justify-center space-x-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span>Add to Cart</span>
                        </button>
                    </form>
                    <form action="{{ route('customer.wishlist.toggle', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto flex items-center justify-center space-x-2 px-6 py-3 border border-red-500 text-red-500 font-semibold rounded-lg hover:bg-red-50 transition duration-150 ease-in-out">
                            {{ auth()->user()->wishlist->contains($product->id) ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection
