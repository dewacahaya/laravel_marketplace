@extends('dashboard')

@section('admin_content')
<div class="p-6">

    {{-- Title --}}
    <h2 class="text-2xl font-semibold mb-6">Admin Dashboard</h2>

    {{-- TOP CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        {{-- Total Users --}}
        <div class="bg-orange-400 p-5 shadow rounded-xl">
            <p class="text-sm font-bold text-gray-800">Total Users</p>
            <h3 class="text-2xl text-white font-bold mt-1">{{ $totalUsers }}</h3>
        </div>

        {{-- Total Products --}}
        <div class="bg-sky-400 p-5 shadow rounded-xl">
            <p class="text-sm font-bold text-gray-800">Total Products</p>
            <h3 class="text-2xl font-bold mt-1">{{ $totalProducts }}</h3>
        </div>

        {{-- Total Orders --}}
        <div class="bg-teal-300 p-5 shadow rounded-xl">
            <p class="text-sm font-bold text-gray-800">Total Orders</p>
            <h3 class="text-2xl text-black font-bold mt-1">{{ $totalOrders }}</h3>
        </div>

        {{-- Total Income --}}
        <div class="bg-red-600 p-5 shadow rounded-xl">
            <p class="text-sm font-bold text-gray-800">Total Income</p>
            <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- CHART ROW --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Income Chart --}}
        <div class="bg-white p-6 shadow rounded-xl border">
            <h3 class="text-lg text-black font-semibold mb-4">Pendapatan 7 Hari Terakhir</h3>
            <canvas id="incomeChart" height="130"></canvas>
        </div>

        {{-- Order Status Chart --}}
        <div class="bg-white p-6 shadow rounded-xl border">
            <h3 class="text-lg text-black font-semibold mb-4">Status Order</h3>
            <canvas id="statusChart" height="130"></canvas>
        </div>

    </div>

</div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
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
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.2)',
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // -------------------------------------------
    // Order Status Chart
    // -------------------------------------------
    const statusCtx = document.getElementById('statusChart').getContext('2d');

    const statusLabels = @json($orderStatusCount->keys());
    const statusCounts = @json($orderStatusCount->values());

    new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: statusLabels,
            datasets: [{
                label: 'Jumlah',
                data: statusCounts,
                borderWidth: 2,
                backgroundColor: [
                    '#4f46e5',
                    '#10b981',
                    '#f59e0b',
                    '#3b82f6',
                    '#ef4444'
                ]
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
