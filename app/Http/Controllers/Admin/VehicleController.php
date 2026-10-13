<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('brand', 'like', "%$s%")
                  ->orWhere('plate_number', 'like', "%$s%");
            });
        }
        $vehicles = $query->latest()->paginate(15)->withQueryString();
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('admin.vehicles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'brand'         => 'required|string|max:100',
            'model'         => 'required|string|max:100',
            'year'          => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'plate_number'  => 'required|string|unique:vehicles',
            'type'          => 'required|in:sedan,suv,van,truck,bus,convertible,pickup',
            'seats'         => 'required|integer|min:1|max:50',
            'price_per_day' => 'required|numeric|min:0',
            'description'   => 'nullable|string',
            'color'         => 'nullable|string|max:50',
            'transmission'  => 'required|in:automatic,manual',
            'fuel_type'     => 'required|string|max:50',
            'status'        => 'required|in:available,hired,maintenance,retired',
            'location'      => 'nullable|string|max:255',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/vehicles'), $filename);
            $data['image'] = $filename;
        }

        Vehicle::create($data);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle added successfully.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'brand'         => 'required|string|max:100',
            'model'         => 'required|string|max:100',
            'year'          => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'plate_number'  => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'type'          => 'required|in:sedan,suv,van,truck,bus,convertible,pickup',
            'seats'         => 'required|integer|min:1|max:50',
            'price_per_day' => 'required|numeric|min:0',
            'description'   => 'nullable|string',
            'color'         => 'nullable|string|max:50',
            'transmission'  => 'required|in:automatic,manual',
            'fuel_type'     => 'required|string|max:50',
            'status'        => 'required|in:available,hired,maintenance,retired',
            'location'      => 'nullable|string|max:255',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            // Remove old image
            if ($vehicle->image) {
                $oldPath = public_path('assets/img/vehicles/' . $vehicle->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/vehicles'), $filename);
            $data['image'] = $filename;
        }

        $vehicle->update($data);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->bookings()->whereIn('status', ['pending', 'approved', 'active'])->exists()) {
            return back()->with('error', 'Cannot delete vehicle with active bookings.');
        }
        if ($vehicle->image) {
            $path = public_path('assets/img/vehicles/' . $vehicle->image);
            if (file_exists($path)) unlink($path);
        }
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
