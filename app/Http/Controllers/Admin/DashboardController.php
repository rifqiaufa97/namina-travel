<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Destination;

class DashboardController extends BaseController
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_packages' => Destination::count(),
            'revenue' => Order::where('payment_status', Order::PAYMENT_PAID)->sum('total'),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentOrders'));
    }
}
