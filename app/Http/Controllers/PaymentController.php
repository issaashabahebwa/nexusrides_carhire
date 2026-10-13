<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        if ($booking->status !== 'approved') {
            return back()->with('error', 'Payment is only allowed for approved bookings.');
        }
        if ($booking->payment && $booking->payment->status === 'completed') {
            return back()->with('info', 'This booking is already paid.');
        }
        return view('payments.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        if ($booking->status !== 'approved') {
            return back()->with('error', 'Payment is only allowed for approved bookings.');
        }

        $data = $request->validate([
            'method'       => 'required|in:mtn_momo,airtel_money,card,cash',
            'phone_number' => 'required_if:method,mtn_momo,airtel_money|nullable|string|max:20',
            'card_number'  => 'required_if:method,card|nullable|string',
            'card_name'    => 'required_if:method,card|nullable|string',
        ]);

        $transactionId = 'TXN-' . strtoupper(uniqid());

        $payment = Payment::create([
            'booking_id'     => $booking->id,
            'user_id'        => Auth::id(),
            'amount'         => $booking->total_amount,
            'method'         => $data['method'],
            'transaction_id' => $transactionId,
            'phone_number'   => $data['phone_number'] ?? null,
            'status'         => 'completed',
            'paid_at'        => now(),
        ]);

        $booking->update(['status' => 'active']);

        Notification::send(
            Auth::id(),
            'Payment Successful',
            "Payment of UGX " . number_format($payment->amount, 0) . " received for booking #{$booking->booking_reference}. Transaction ID: {$transactionId}",
            'payment',
            route('bookings.show', $booking)
        );

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Payment successful! Transaction ID: ' . $transactionId);
    }
}
