@extends('dashboard')

@section('admin_content')
    <div class="p-6 md:p-8 lg:p-10">

        {{-- Title --}}
        <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-8">Admin Dashboard</h2>

        {{-- TOP CARDS (Statistik dengan Ikon) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            {{-- Total Users Card (Darker Orange) --}}
            <div
                class="bg-white dark:bg-gray-800 p-6 shadow-xl rounded-xl border-l-4 border-orange-500 transition duration-300 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Users</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $totalUsers }}</h3>
                    </div>
                    <div
                        class="h-10 w-10 flex items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/50 text-orange-600 dark:text-orange-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.68-2.35M12 21a9.38 9.38 0 002.68-2.35M12 21V9m-3 9.128a9.38 9.38 0 01-2.68-2.35M15 9h2.25a2.25 2.25 0 010 4.5h-2.25M12 9V7.5M12 9H9.75M12 9v1.5M12 9V7.5m0 3V9.75M9.75 9h2.25a2.25 2.25 0 000 4.5h2.25M12 9V7.5m-3 9.128a9.38 9.38 0 00-2.68-2.35" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Products Card (Sky Blue) --}}
            <div
                class="bg-white dark:bg-gray-800 p-6 shadow-xl rounded-xl border-l-4 border-sky-500 transition duration-300 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Products</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $totalProducts }}</h3>
                    </div>
                    <div
                        class="h-10 w-10 flex items-center justify-center rounded-full bg-sky-100 dark:bg-sky-900/50 text-sky-600 dark:text-sky-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 21v-7.5m0 0l3-3m-3 3l-3-3m-1.5 6.75C10.125 17.5 7.75 16.5 6 15c-1.75 1.5-4 2.5-6 2.5V3.75a2.25 2.25 0 012.25-2.25h13.5a2.25 2.25 0 012.25 2.25v9.75c0 1.284-.66 2.455-1.758 3.14M12 21V7.5" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Orders Card (Teal/Emerald Green) --}}
            <div
                class="bg-white dark:bg-gray-800 p-6 shadow-xl rounded-xl border-l-4 border-emerald-500 transition duration-300 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Orders</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $totalOrders }}</h3>
                    </div>
                    <div
                        class="h-10 w-10 flex items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4.5v15m7.5-7.5h-15m7.5 7.5a9 9 0 000-15h0a9 9 0 000 15z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Income Card (Red/Primary Action) --}}
            <div
                class="bg-white dark:bg-gray-800 p-6 shadow-xl rounded-xl border-l-4 border-indigo-600 transition duration-300 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Income</p>
                        <h3 class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400 mt-1">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div
                        class="h-10 w-10 flex items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a4.5 4.5 0 004.5 4.5h11.25a4.5 4.5 0 004.5-4.5v-10.5a4.5 4.5 0 00-4.5-4.5H6.75a4.5 4.5 0 00-4.5 4.5v10.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4.5m-3-1.5h6" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHART ROW --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- Income Chart --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow-xl rounded-xl border dark:border-gray-700">
                <h3 class="text-xl text-gray-800 dark:text-white font-semibold mb-4 border-b pb-3 dark:border-gray-700">📈
                    Recently Income
                </h3>
                <div class="h-80"> {{-- Wrapper untuk kontrol tinggi grafik --}}
                    <canvas id="incomeChart" height="130"></canvas>
                </div>
            </div>

            {{-- Order Status Chart --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow-xl rounded-xl border dark:border-gray-700">
                <h3 class="text-xl text-gray-800 dark:text-white font-semibold mb-4 border-b pb-3 dark:border-gray-700">📦
                    Status Order</h3>
                <div class="h-80"> {{-- Wrapper untuk kontrol tinggi grafik --}}
                    <canvas id="statusChart" height="130"></canvas>
                </div>
            </div>

        </div>

    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Warna tema utama: Indigo (Primary) dan Teal/Emerald (Success)
        const PRIMARY_COLOR = '#4f46e5'; // Indigo-600
        const SECONDARY_COLOR = '#10b981'; // Emerald-500

        // -------------------------------------------
        // Income Chart
        // -------------------------------------------
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');

        const incomeLabels = @json($incomeDaily->pluck('date'));
        const incomeData = @json($incomeDaily->pluck('total'));

        new Chart(incomeCtx, {
            type: 'line',
            data: {
                labels: incomeLabels,
                datasets: [{
                    label: 'Pendapatan',
                    data: incomeData,
                    borderWidth: 3,
                    borderColor: PRIMARY_COLOR,
                    backgroundColor: PRIMARY_COLOR + '33', // 33 for 20% opacity
                    tension: 0.4, // Sedikit lebih halus
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Penting untuk kontrol tinggi
                plugins: {
                    legend: {
                        display: false // Sering disembunyikan di dashboard kecil
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(200, 200, 200, 0.2)'
                        } // Garis grid lebih terang
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // -------------------------------------------
        // Order Status Chart
        // -------------------------------------------
        const statusCtx = document.getElementById('statusChart').getContext('2d');

        const statusLabels = @json($orderStatusCount->keys());
        const statusCounts = @json($orderStatusCount->values());

        // Warna yang lebih konsisten dengan tema Tailwind (Indigo, Emerald, Yellow, Blue, Red)
        const colorPalette = [
            '#4f46e5', // Indigo-600
            '#10b981', // Emerald-500
            '#f59e0b', // Amber-500
            '#3b82f6', // Blue-500
            '#ef4444' // Red-500
        ];

        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    label: 'Jumlah Order',
                    data: statusCounts,
                    borderWidth: 0, // Batas dihilangkan
                    backgroundColor: colorPalette,
                    borderRadius: 4, // Bar sedikit melengkung
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Penting untuk kontrol tinggi
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }, // Pastikan hanya bilangan bulat
                        grid: {
                            color: 'rgba(200, 200, 200, 0.2)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endsection
