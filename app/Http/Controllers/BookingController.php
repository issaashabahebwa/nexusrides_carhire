<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with('vehicle', 'payment')
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $vehicle = null;
        if ($request->filled('vehicle_id')) {
            $vehicle = Vehicle::findOrFail($request->vehicle_id);
        }
        return view('bookings.create', compact('vehicle'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id'      => 'required|exists:vehicles,id',
            'pickup_date'     => 'required|date|after_or_equal:today',
            'return_date'     => 'required|date|after:pickup_date',
            'pickup_location' => 'required|string|max:255',
            'return_location' => 'nullable|string|max:255',
            'notes'           => 'nullable|string|max:500',
        ]);

        $vehicle = Vehicle::findOrFail($data['vehicle_id']);

        if (!$vehicle->isAvailableForDates($data['pickup_date'], $data['return_date'])) {
            return back()->withErrors(['vehicle_id' => 'This vehicle is not available for the selected dates.'])->withInput();
        }

        $days   = (int) \Carbon\Carbon::parse($data['pickup_date'])->diffInDays($data['return_date']);
        $days   = max(1, $days);
        $total  = $days * $vehicle->price_per_day;

        $booking = Booking::create([
            'booking_reference' => Booking::generateReference(),
            'user_id'           => Auth::id(),
            'vehicle_id'        => $vehicle->id,
            'pickup_date'       => $data['pickup_date'],
            'return_date'       => $data['return_date'],
            'pickup_location'   => $data['pickup_location'],
            'return_location'   => $data['return_location'] ?? $data['pickup_location'],
            'days'              => $days,
            'total_amount'      => $total,
            'notes'             => $data['notes'] ?? null,
            'status'            => 'pending',
        ]);

        // Notify user
        Notification::send(
            Auth::id(),
            'Booking Submitted',
            "Your booking #{$booking->booking_reference} for {$vehicle->name} has been submitted and is awaiting approval.",
            'booking',
            route('bookings.show', $booking)
        );

        return redirect()->route('bookings.show', $booking)
            ->with('success', "Booking #{$booking->booking_reference} submitted successfully! Awaiting approval.");
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $booking->load('vehicle', 'payment', 'user');
        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        if (!in_array($booking->status, ['pending', 'approved'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $wasApproved = $booking->status === 'approved';
        $booking->update(['status' => 'cancelled']);

        // Free the vehicle back to available if booking was approved
        if ($wasApproved) {
            $booking->vehicle->update(['status' => 'available']);
        }

        Notification::send(
            Auth::id(),
            'Booking Cancelled',
            "Your booking #{$booking->booking_reference} has been cancelled.",
            'booking'
        );

        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }
}
