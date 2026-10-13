<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredVehicles = Vehicle::where('status', 'available')
            ->orderBy('price_per_day')
            ->take(6)
            ->get();

        $stats = [
            'total_vehicles'  => Vehicle::count(),
            'available'       => Vehicle::where('status', 'available')->count(),
            'vehicle_types'   => Vehicle::distinct('type')->count('type'),
        ];

        return view('home', compact('featuredVehicles', 'stats'));
    }

    public function dashboard()
    {
        $user     = auth()->user();
        $bookings = $user->bookings()->with('vehicle')->latest()->take(5)->get();
        $totalSpent = $user->payments()->where('status', 'completed')->sum('amount');
        $activeBooking = $user->bookings()->whereIn('status', ['approved', 'active'])->with('vehicle')->first();
        $notifications = $user->notifications()->latest()->take(5)->get();

        return view('dashboard', compact('user', 'bookings', 'totalSpent', 'activeBooking', 'notifications'));
    }
}
