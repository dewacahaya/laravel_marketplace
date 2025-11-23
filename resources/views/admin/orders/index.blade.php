@extends('dashboard')

@section('admin_content')
    <div class="p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold">Order List</h2>
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-600 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        {{-- Jika tidak ada order --}}
        @if ($orders->isEmpty())
            <p class="text-gray-500 italic">No orders available.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer Name
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Price
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Transaction Date
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">

                        @foreach ($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    #{{ $order->id }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $order->user->name }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 rounded text-xs
                                        @if ($order->status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif ($order->status === 'paid') bg-green-100 text-green-700
                                        @elseif ($order->status === 'shipped') bg-blue-100 text-blue-700
                                        @elseif ($order->status === 'completed') bg-green-200 text-green-800
                                        @elseif ($order->status === 'cancelled') bg-red-100 text-red-700 @endif
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
