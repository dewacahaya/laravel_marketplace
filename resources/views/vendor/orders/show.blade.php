@extends('dashboard')

@section('vendor_content')
    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="flex justify-between items-center mb-6 border-b pb-2">
            <h2 class="text-3xl font-extrabold text-gray-900">Order Detail #{{ $order->id }}</h2>
            <a href="{{ route('vendor.orders.index') }}"
                class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
                &larr; Back to Order List
            </a>
        </div>

        {{-- Detail Customer dan Pengiriman --}}
        <div
            class="bg-white shadow-xl rounded-lg p-6 mb-8 border-t-4 border-purple-600 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">Detail Customer</h3>
                <p class="text-lg font-medium text-gray-900">{{ $order->user->name ?? 'User Dihapus' }}</p>
                <p class="text-gray-700">Email: {{ $order->user->email ?? 'N/A' }}</p>
            </div>

            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">Shipping Detail</h3>
                <p class="font-medium text-gray-900">Address: {{ $customerProfile['address'] }}</p>
                <p class="text-gray-700">Telp: {{ $customerProfile['phone'] }}</p>
            </div>
        </div>

        {{-- Item Produk Vendor --}}
        <div class="bg-white shadow-xl rounded-lg overflow-hidden mt-8">
            <h3 class="text-xl font-semibold text-gray-800 p-4 border-b bg-gray-50">
                Detail Item ({{ $order->items->count() }} Item)
            </h3>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">
                            Product
                        </th>
                        <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Unit Price
                        </th>
                        <th class="p-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subtotal
                        </th>
                        <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $vendorSubtotal = 0; @endphp
                    @foreach ($order->items as $item)
                        @php $vendorSubtotal += ($item->price * $item->quantity); @endphp
                        <tr class="hover:bg-yellow-50">
                            <td class="p-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item->product->name ?? 'Produk Dihapus' }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-sm text-center text-gray-700">
                                {{ $item->quantity }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-sm text-right text-gray-700">
                                Rp {{ number_format($item->price) }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-base font-semibold text-right text-green-600">
                                Rp {{ number_format($item->price * $item->quantity) }}
                            </td>
                            {{-- Dropdown Status Order Utama --}}
                            <td class="p-4 whitespace-nowrap text-center text-sm">
                                <form action="{{ route('vendor.orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    {{-- Kita kirim status yang dipilih --}}
                                    <select name="status" onchange="this.form.submit()"
                                        class="px-2 py-1 border rounded text-xs font-semibold focus:ring-purple-500 focus:border-purple-500 dark:text-gray-900">

                                        {{-- Opsi Status (Menggunakan $order->status untuk selected) --}}
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid
                                        </option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                        </option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="p-4 text-right text-xl font-bold text-gray-800">Total Income:
                        </td>
                        <td colspan="2" class="p-4 text-left text-2xl font-extrabold text-green-700">
                            Rp {{ number_format($vendorSubtotal) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
