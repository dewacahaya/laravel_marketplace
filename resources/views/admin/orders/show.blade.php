@extends('dashboard')

@section('admin_content')
    <div class="p-6 bg-white dark:bg-gray-900 rounded-lg shadow">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold">Order Detail #{{ $order->id }}</h2>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
                ← Back
            </a>
        </div>

        {{-- Order Info --}}
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Order Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                </div>

                <div>
                    <p>
                        <strong>Status:</strong>
                        <span class="px-2 py-1 rounded text-xs
                            @if ($order->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif ($order->status === 'paid') bg-green-100 text-green-700
                            @elseif ($order->status === 'shipped') bg-blue-100 text-blue-700
                            @elseif ($order->status === 'completed') bg-green-200 text-green-800
                            @elseif ($order->status === 'cancelled') bg-red-100 text-red-700
                            @endif
                        ">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>

                    <p>
                        <strong>Order Date:</strong>
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </p>
                </div>

            </div>
        </div>

        {{-- Order Items --}}
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-3">Order Items</h3>

            @if ($order->items->isEmpty())
                <p class="text-gray-500 italic">No items found in this order.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item->product->name }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item->quantity }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @endif
        </div>

        {{-- Total Price --}}
        <div class="mt-6 text-right">
            <h3 class="text-xl font-semibold">
                Total:
                <span class="text-green-600">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </h3>
        </div>

    </div>
@endsection
