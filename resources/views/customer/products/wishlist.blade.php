@extends('dashboard')

@section('cust_content')
    <div class="p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">My Wishlist</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse ($wishlists as $product)
                <div class="p-4 border rounded shadow">
                    <img src="{{ asset('storage/' . $product->image) }}" class="h-40 w-full object-cover rounded mb-2">

                    <h3 class="font-bold">{{ $product->name }}</h3>
                    <p class="text-sm">Rp {{ number_format($product->price) }}</p>

                    <form action="{{ route('customer.wishlist.toggle', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 mt-2 hover:bg-red-600 text-white px-3 py-1 rounded">
                            Remove
                        </button>
                    </form>
                </div>
            @empty
                <p>No products in wishlist</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $wishlists->links() }}
        </div>
    </div>
@endsection
