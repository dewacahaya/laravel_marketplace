@extends('dashboard')

@section('cust_content')
    <div class="container mx-auto px-4 py-8 md:py-12">

        <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-2 dark:text-white">🛒 Your Shopping Cart</h2>

        @if (count($cart) > 0)
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                Product</th>
                            <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Qty
                            </th>
                            <th class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Price
                            </th>
                            <th class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                Subtotal</th>
                            <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @foreach ($cart as $item)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                {{-- Product Name --}}
                                <td class="p-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item['name'] }}
                                </td>

                                {{-- Quantity (You might want to add update form here) --}}
                                <td class="p-4 whitespace-nowrap text-sm text-center text-gray-700">
                                    <form action="{{ route('customer.cart.update', $item['product_id']) }}" method="POST"
                                        id="update-form-{{ $item['product_id'] }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                            max="{{ $item['stock'] }}"
                                            class="w-20 text-center border border-gray-300 rounded-md p-1 text-sm focus:border-indigo-500 focus:ring-indigo-500 update-quantity-input"
                                            data-product-id="{{ $item['product_id'] }}" onchange="this.form.submit()">
                                    </form>
                                </td>

                                {{-- Price --}}
                                <td class="p-4 whitespace-nowrap text-sm text-right text-gray-700">
                                    Rp {{ number_format($item['price']) }}
                                </td>

                                {{-- Subtotal --}}
                                <td class="p-4 whitespace-nowrap text-base font-semibold text-right text-indigo-600">
                                    Rp {{ number_format($item['subtotal']) }}
                                </td>

                                {{-- Action Button --}}
                                <td class="p-4 whitespace-nowrap text-sm text-center">
                                    <form action="{{ route('customer.cart.remove', $item['product_id']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="remove-btn text-red-600 hover:underline"
                                            data-id="{{ $item['product_id'] }}">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($item['quantity'] >= $item['stock'])
                    <p class="text-red-600 text-sm">Max stock reached!</p>
                @endif
            </div>

            {{-- Summary and Checkout --}}
            <div class="flex justify-end mt-6">
                <div class="w-full md:w-1/3 p-6 bg-gray-100 rounded-lg shadow-inner">
                    <div class="flex justify-between items-center text-xl font-bold text-gray-800 border-b pb-3 mb-3">
                        <span>Grand Total:</span>
                        <span class="text-indigo-600">Rp {{ number_format($total) }}</span>
                    </div>

                    <a href="{{ route('customer.checkout.confirm') }}"
                        class="block w-full text-center mt-4 px-6 py-3 bg-indigo-600 text-white font-semibold text-lg rounded-xl hover:bg-indigo-700 transition duration-200 ease-in-out shadow-lg transform hover:scale-[1.01]">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-16 border border-dashed border-gray-300 rounded-lg bg-gray-50 mt-10">
                <p class="text-xl text-gray-500">Your cart is currently empty.</p>
                <a href="{{ route('customer.dashboard') ?? '#' }}"
                    class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium transition">
                    Start Shopping Now
                </a>
            </div>
        @endif
    </div>
@endsection
