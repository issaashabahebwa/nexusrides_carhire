<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year  = $request->get('year', now()->year);
        $month = $request->get('month', null);

        // Revenue per month
        $revenueData = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueData[$m] = Payment::where('status', 'completed')
                ->whereMonth('paid_at', $m)
                ->whereYear('paid_at', $year)
                ->sum('amount');
        }

        // Booking stats
        $bookingStats = [
            'total'     => Booking::whereYear('created_at', $year)->count(),
            'pending'   => Booking::where('status', 'pending')->whereYear('created_at', $year)->count(),
            'approved'  => Booking::where('status', 'approved')->whereYear('created_at', $year)->count(),
            'active'    => Booking::where('status', 'active')->whereYear('created_at', $year)->count(),
            'completed' => Booking::where('status', 'completed')->whereYear('created_at', $year)->count(),
            'cancelled' => Booking::where('status', 'cancelled')->whereYear('created_at', $year)->count(),
            'declined'  => Booking::where('status', 'declined')->whereYear('created_at', $year)->count(),
        ];

        // Vehicle utilization
        $vehicleUtil = Vehicle::withCount([
            'bookings as completed_bookings' => fn($q) => $q->where('status', 'completed'),
        ])->orderByDesc('completed_bookings')->take(10)->get();

        // Top customers
        $topCustomers = User::where('role', 'customer')
            ->withCount('bookings')
            ->withSum(['payments as total_paid' => fn($q) => $q->where('status', 'completed')], 'amount')
            ->orderByDesc('total_paid')
            ->take(10)
            ->get();

        $totalRevenue = Payment::where('status', 'completed')->whereYear('paid_at', $year)->sum('amount');
        $years = range(now()->year, now()->year - 4);

        return view('admin.reports.index', compact(
            'revenueData', 'bookingStats', 'vehicleUtil',
            'topCustomers', 'totalRevenue', 'year', 'years'
        ));
    }
}
