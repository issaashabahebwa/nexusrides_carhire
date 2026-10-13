<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_vehicles'    => Vehicle::count(),
            'available_vehicles'=> Vehicle::where('status', 'available')->count(),
            'total_bookings'    => Booking::count(),
            'pending_bookings'  => Booking::where('status', 'pending')->count(),
            'active_bookings'   => Booking::where('status', 'active')->count(),
            'total_customers'   => User::where('role', 'customer')->count(),
            'total_revenue'     => Payment::where('status', 'completed')->sum('amount'),
            'monthly_revenue'   => Payment::where('status', 'completed')
                                        ->whereMonth('paid_at', now()->month)
                                        ->whereYear('paid_at', now()->year)
                                        ->sum('amount'),
        ];

        $recentBookings = Booking::with('user', 'vehicle')
            ->latest()
            ->take(8)
            ->get();

        $recentPayments = Payment::with('user', 'booking')
            ->latest()
            ->take(5)
            ->get();

        // Monthly revenue for chart (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'month'   => $month->format('M Y'),
                'revenue' => Payment::where('status', 'completed')
                                ->whereMonth('paid_at', $month->month)
                                ->whereYear('paid_at', $month->year)
                                ->sum('amount'),
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentPayments', 'monthlyData'));
    }
}
