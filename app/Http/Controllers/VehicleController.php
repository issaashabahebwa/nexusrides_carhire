<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::where('status', 'available');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }
        if ($request->filled('seats')) {
            $query->where('seats', '>=', $request->seats);
        }
        if ($request->filled('transmission')) {
            $query->where('transmission', $request->transmission);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('brand', 'like', "%$s%")
                  ->orWhere('model', 'like', "%$s%");
            });
        }

        // Filter availability by dates if provided
        if ($request->filled('pickup_date') && $request->filled('return_date')) {
            $pickup  = $request->pickup_date;
            $return  = $request->return_date;
            $query->whereDoesntHave('bookings', function ($q) use ($pickup, $return) {
                $q->whereIn('status', ['approved', 'active'])
                  ->where(function ($q2) use ($pickup, $return) {
                      $q2->whereBetween('pickup_date', [$pickup, $return])
                         ->orWhereBetween('return_date', [$pickup, $return])
                         ->orWhere(function ($q3) use ($pickup, $return) {
                             $q3->where('pickup_date', '<=', $pickup)
                                ->where('return_date', '>=', $return);
                         });
                  });
            });
        }

        $vehicles = $query->orderBy('price_per_day')->paginate(9)->withQueryString();
        $types = ['sedan', 'suv', 'van', 'truck', 'bus', 'convertible', 'pickup'];

        return view('vehicles.index', compact('vehicles', 'types'));
    }

    public function show(Vehicle $vehicle)
    {
        $similarVehicles = Vehicle::where('type', $vehicle->type)
            ->where('id', '!=', $vehicle->id)
            ->where('status', 'available')
            ->take(3)
            ->get();

        return view('vehicles.show', compact('vehicle', 'similarVehicles'));
    }
}
