@extends('dashboard')

@section('cust_content')
    <div class="container mx-auto px-4 py-8">

        <form method="GET" class="mb-6 max-w-lg mx-auto md:mx-0">
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                class="w-full p-3 text-black border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                placeholder="Search product...">
        </form>

        <h2 class="text-3xl font-extrabold text-gray-800 mb-6 dark:text-white">🛍️ Shop Product List</h2>

        @if ($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 flex flex-col">
                        {{-- Product Image --}}
                        <a href="{{ route('customer.product.detail', $product->slug) }}">
                            <div class="relative overflow-hidden rounded-t-xl">
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    class="w-full object-cover h-48 sm:h-56 lg:h-48" alt="Product Image">
                            </div>
                        </a>

                        {{-- Product Details --}}
                        <div class="p-4 flex-grow">
                            <h5 class="text-xl font-semibold text-gray-900 mb-1 truncate">{{ $product->name }}</h5>
                            <p class="text-sm text-gray-500 mb-2">{{ $product->category->name }}</p>
                            <p class="text-lg font-bold text-blue-600">Rp {{ number_format($product->price) }}</p>
                        </div>

                        {{-- Add to Cart Button --}}
                        <div class="p-4 pt-0">
                            <a href="#"
                                class="block w-full text-center py-2 px-4 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                                Add to Cart
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <p class="text-center text-xl text-gray-500 py-10">No products found.</p>
        @endif
    </div>
@endsection
