<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('user', 'vehicle');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('booking_reference', 'like', "%$s%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$s%"))
                  ->orWhereHas('vehicle', fn($v) => $v->where('name', 'like', "%$s%"));
            });
        }
        if ($request->filled('date_from')) {
            $query->where('pickup_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('pickup_date', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load('user', 'vehicle', 'payment', 'approvedBy');
        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be approved.');
        }

        $booking->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        Notification::send(
            $booking->user_id,
            'Booking Approved!',
            "Your booking #{$booking->booking_reference} for {$booking->vehicle->name} has been approved. Please proceed with payment.",
            'booking',
            route('bookings.show', $booking)
        );

        return back()->with('success', 'Booking approved successfully.');
    }

    public function decline(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be declined.');
        }

        $request->validate(['admin_notes' => 'nullable|string|max:500']);

        $booking->update([
            'status'      => 'declined',
            'admin_notes' => $request->admin_notes,
        ]);

        Notification::send(
            $booking->user_id,
            'Booking Declined',
            "Your booking #{$booking->booking_reference} has been declined. Reason: " . ($request->admin_notes ?? 'No reason provided.'),
            'booking'
        );

        return back()->with('success', 'Booking declined.');
    }

    public function complete(Booking $booking)
    {
        if ($booking->status !== 'active') {
            return back()->with('error', 'Only active bookings can be marked as completed.');
        }

        $booking->update(['status' => 'completed']);

        // Free the vehicle
        $booking->vehicle->update(['status' => 'available']);

        Notification::send(
            $booking->user_id,
            'Booking Completed',
            "Your rental of {$booking->vehicle->name} (Booking #{$booking->booking_reference}) has been completed. Thank you for choosing NexusRides!",
            'booking'
        );

        return back()->with('success', 'Booking marked as completed.');
    }
}
