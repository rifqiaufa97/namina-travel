<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorTracking;
use App\Models\Order;
use App\Models\Destination;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $startDate = now()->subDays(30);
        $endDate = now();

        // Visitor Statistics
        $visitorStats = $this->getVisitorStats($startDate, $endDate);
        
        // Revenue Statistics
        $revenueStats = $this->getRevenueStats($startDate, $endDate);
        
        // Booking Statistics
        $bookingStats = $this->getBookingStats($startDate, $endDate);
        
        // User Statistics
        $userStats = $this->getUserStats($startDate, $endDate);
        
        // Popular Destinations
        $popularDestinations = $this->getPopularDestinations($startDate, $endDate);
        
        // Visitor Locations
        $visitorLocations = $this->getVisitorLocations($startDate, $endDate);
        
        return view('admin.analytics.index', compact(
            'visitorStats',
            'revenueStats',
            'bookingStats',
            'userStats',
            'popularDestinations',
            'visitorLocations',
            'startDate',
            'endDate'
        ));
    }
    
    public function filter(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->subDays(30);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now();
        
        // Ensure end date is after start date
        if ($endDate->lt($startDate)) {
            $endDate = $startDate->copy()->addDay();
        }
        
        // Get all stats with the filtered date range
        $visitorStats = $this->getVisitorStats($startDate, $endDate);
        $revenueStats = $this->getRevenueStats($startDate, $endDate);
        $bookingStats = $this->getBookingStats($startDate, $endDate);
        $userStats = $this->getUserStats($startDate, $endDate);
        $popularDestinations = $this->getPopularDestinations($startDate, $endDate);
        $visitorLocations = $this->getVisitorLocations($startDate, $endDate);
        
        if ($request->ajax()) {
            return response()->json([
                'visitorStats' => $visitorStats,
                'revenueStats' => $revenueStats,
                'bookingStats' => $bookingStats,
                'userStats' => $userStats,
                'popularDestinations' => $popularDestinations,
                'visitorLocations' => $visitorLocations,
                'dateRange' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ]
            ]);
        }
        
        return view('admin.analytics.index', compact(
            'visitorStats',
            'revenueStats',
            'bookingStats',
            'userStats',
            'popularDestinations',
            'visitorLocations',
            'startDate',
            'endDate'
        ));
    }
    
    private function getVisitorStats($startDate, $endDate)
    {
        $totalVisitors = VisitorTracking::whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $uniqueVisitors = VisitorTracking::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('ip_address')
            ->count('ip_address');
            
        $visitorsByDevice = VisitorTracking::select('device_type', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('device_type')
            ->pluck('total', 'device_type')
            ->toArray();
            
        $visitorsByBrowser = VisitorTracking::select('browser', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'browser')
            ->toArray();
            
        $visitorsByDay = VisitorTracking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total'),
                DB::raw('count(DISTINCT ip_address) as unique_visitors')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $visitorData = [];
        $uniqueVisitorData = [];
        $labels = [];
        
        foreach ($visitorsByDay as $day) {
            $labels[] = Carbon::parse($day->date)->format('M d');
            $visitorData[] = $day->total;
            $uniqueVisitorData[] = $day->unique_visitors;
        }
        
        return [
            'total_visitors' => $totalVisitors,
            'unique_visitors' => $uniqueVisitors,
            'by_device' => $visitorsByDevice,
            'by_browser' => $visitorsByBrowser,
            'chart_data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Total Kunjungan',
                        'data' => $visitorData,
                        'borderColor' => 'rgba(59, 130, 246, 1)',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ],
                    [
                        'label' => 'Pengunjung Unik',
                        'data' => $uniqueVisitorData,
                        'borderColor' => 'rgba(16, 185, 129, 1)',
                        'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ]
                ]
            ]
        ];
    }
    
    private function getRevenueStats($startDate, $endDate)
    {
        $totalRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');
            
        $revenueByMonth = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total')
            )
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        $revenueByPaymentMethod = Order::select(
                'payment_method',
                DB::raw('SUM(total) as total')
            )
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();
            
        $revenueData = [];
        $labels = [];
        
        foreach ($revenueByMonth as $month) {
            $date = Carbon::createFromDate($month->year, $month->month, 1);
            $labels[] = $date->format('M Y');
            $revenueData[] = $month->total;
        }
        
        return [
            'total_revenue' => $totalRevenue,
            'by_payment_method' => $revenueByPaymentMethod,
            'chart_data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Pendapatan',
                        'data' => $revenueData,
                        'borderColor' => 'rgba(139, 92, 246, 1)',
                        'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ]
                ]
            ]
        ];
    }
    
    private function getBookingStats($startDate, $endDate)
    {
        $totalBookings = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedBookings = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $cancelledBookings = Order::where('status', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $bookingsByStatus = Order::select(
                'status',
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
            
        $bookingsByMonth = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        $bookingData = [];
        $labels = [];
        
        foreach ($bookingsByMonth as $month) {
            $date = Carbon::createFromDate($month->year, $month->month, 1);
            $labels[] = $date->format('M Y');
            $bookingData[] = $month->total;
        }
        
        return [
            'total_bookings' => $totalBookings,
            'completed_bookings' => $completedBookings,
            'cancelled_bookings' => $cancelledBookings,
            'by_status' => $bookingsByStatus,
            'chart_data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Pemesanan',
                        'data' => $bookingData,
                        'borderColor' => 'rgba(59, 130, 246, 1)',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ]
                ]
            ]
        ];
    }
    
    private function getUserStats($startDate, $endDate)
    {
        $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Count active users (logged in within last 30 days)
        $activeUsers = User::where(function($query) use ($startDate, $endDate) {
                $query->where('last_login_at', '>=', now()->subDays(30))
                      ->orWhereNull('last_login_at');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $usersByMonth = User::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        $userData = [];
        $labels = [];
        
        foreach ($usersByMonth as $month) {
            $date = Carbon::createFromDate($month->year, $month->month, 1);
            $labels[] = $date->format('M Y');
            $userData[] = $month->total;
        }
        
        return [
            'total_users' => $totalUsers,
            'new_users' => $newUsers,
            'active_users' => $activeUsers,
            'chart_data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Pengguna Baru',
                        'data' => $userData,
                        'borderColor' => 'rgba(16, 185, 129, 1)',
                        'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                        'tension' => 0.4,
                        'fill' => true,
                    ]
                ]
            ]
        ];
    }
    
    private function getPopularDestinations($startDate, $endDate)
    {
        return Order::select(
                'destinations.id',
                'destinations.name',
                'destinations.slug',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total) as total_revenue')
            )
            ->join('destinations', 'orders.destination_id', '=', 'destinations.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('destinations.id', 'destinations.name', 'destinations.slug')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();
    }
    
    private function getVisitorLocations($startDate, $endDate)
    {
        return VisitorTracking::select(
                'country',
                DB::raw('COUNT(*) as total_visitors'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_visitors')
            )
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('country')
            ->orderByDesc('total_visitors')
            ->limit(10)
            ->get();
    }
}
