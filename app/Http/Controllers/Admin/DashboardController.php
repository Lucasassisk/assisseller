<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $revenue       = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders   = Order::count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::where('active', true)->count();
        $avgTicket     = $totalOrders > 0 ? $revenue / $totalOrders : 0;

        $recentOrders = Order::with('customer')
            ->latest()
            ->limit(10)
            ->get();

        $revenueChart = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $settings = Setting::allKeyed();

        return view('admin.dashboard', compact(
            'revenue', 'totalOrders', 'totalCustomers', 'totalProducts',
            'avgTicket', 'recentOrders', 'revenueChart', 'settings'
        ));
    }
}
