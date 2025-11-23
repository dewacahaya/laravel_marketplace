<x-app-layout>
    <div class="flex min-h-[calc(100vh-4rem)]"> {{-- kurangi tinggi biar ga bentrok sama header breeze --}}

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-white dark:bg-gray-900 shadow-lg border-r hidden md:block">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-indigo-600 dark:text-indigo-400">MARKETPLACE</h2>
            </div>

            <nav class="mt-4 space-y-1 px-3">
                @php $role = auth()->user()->role ?? 'customer'; @endphp

                @if ($role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="block py-2 px-3 rounded dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : '' }}">🏠
                        Dashboard</a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="block py-2 px-3 rounded dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-900 font-semibold' : '' }}">
                        📂 Categories
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                        class="block py-2 px-3 rounded dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('admin.products.*') ? 'bg-indigo-900 font-semibold' : '' }}">🛒
                        Products</a>
                    <a href="{{ route('admin.orders.index') }}"
                        class="block py-2 px-3 rounded dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-800">📦
                        Orders</a>
                    <a href="{{ route('admin.vendors.index') }}"
                        class="block py-2 px-3 rounded dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('admin.vendors.*') ? 'bg-indigo-900 font-semibold' : '' }}">👥
                        Users</a>
                @elseif ($role === 'vendor')
                    <a href="{{ route('vendor.dashboard') }}"
                        class="block py-2 px-3 dark:text-white rounded hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : '' }}">🏠 Dashboard</a>
                    <a href="{{ route('vendor.products.index') }}" class="block py-2 px-3 dark:text-white rounded hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('vendor.products') ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : '' }}">🛒 My
                        Products</a>
                    <a href="#" class="block py-2 px-3 dark:text-white rounded hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('vendor.orders') ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : '' }}">📦 My
                        Orders</a>
                @else
                    <a href="{{ route('customer.dashboard') }}"
                        class="block py-2 px-3 rounded dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('customer.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : '' }}">🏠 Dashboard</a>
                    <a href="{{ route('customer.cart.index') }}" class="block py-2 px-3 rounded dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('customer.cart.index') ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : '' }}">🛒
                        Cart</a>
                    <a href="{{ route('customer.wishlist') }}" class="block py-2 px-3 rounded dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('customer.wishlist') ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : '' }}">❤️
                        Wishlist</a>
                    <a href="{{ route('customer.orders.index') }}" class="block py-2 px-3 rounded dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-800 {{ request()->routeIs('customer.orders.index') ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : '' }}">🧾
                        Orders</a>
                @endif
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-6 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100">

            @php $role = auth()->user()->role ?? 'customer'; @endphp

            @if ($role === 'admin')
                @yield('admin_content')
            @elseif ($role === 'vendor')
                @yield('vendor_content')
            @elseif ($role === 'customer')
                @yield('cust_content')
            @endif
        </main>
    </div>
</x-app-layout>
