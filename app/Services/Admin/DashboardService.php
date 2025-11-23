<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class DashboardService
{
    public function getDashboardData()
    {
        // Total Data
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        // Total Income (paid, shipped, completed)
        $totalIncome = Order::whereIn('status', ['paid', 'shipped', 'completed'])
            ->sum('total_price');

        // Income last 7 days (group by date)
        $incomeDaily = Order::whereIn('status', ['paid', 'shipped', 'completed'])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Order status stats
        $orderStatusCount = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'totalUsers'        => $totalUsers,
            'totalProducts'     => $totalProducts,
            'totalOrders'       => $totalOrders,
            'totalIncome'       => $totalIncome,
            'incomeDaily'       => $incomeDaily,
            'orderStatusCount'  => $orderStatusCount,
        ];
    }
}
