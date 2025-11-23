@extends('dashboard')

@section('vendor_content')
    <div class="container mx-auto px-4 py-8 md:py-12">

        <h2 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-2">🚚 Pesanan Masuk</h2>

        @if ($orders->isEmpty())
            <div class="text-center py-10 border border-dashed border-gray-300 rounded-lg bg-gray-50 mt-10">
                <p class="text-xl text-gray-500">Belum ada pesanan yang berisi produk Anda. 😥</p>
            </div>
        @else
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID
                            </th>
                            <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Pesan
                            </th>
                            <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                Customer</th>
                            <th class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Item
                                Anda</th>
                            <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                                Order</th>
                            <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                <td class="p-4 whitespace-nowrap text-sm font-semibold text-indigo-600">
                                    #{{ $order->id }}
                                </td>
                                <td class="p-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td class="p-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $order->user->name ?? 'User Dihapus' }}
                                </td>
                                <td class="p-4 whitespace-nowrap text-base font-bold text-right text-gray-900">
                                    {{ $order->items->sum('quantity') }}
                                </td>
                                <td class="p-4 whitespace-nowrap text-center">
                                    @php
                                        $statusClass = match ($order->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processed' => 'bg-blue-100 text-blue-800',
                                            // ... tambahkan status lain
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusClass }} capitalize">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="p-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('vendor.orders.show', $order->id) }}"
                                        class="text-green-600 hover:text-green-900 font-semibold transition duration-150">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection


