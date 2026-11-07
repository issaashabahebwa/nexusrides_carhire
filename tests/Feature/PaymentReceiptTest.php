<?php

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createReceiptPayment(User $user, string $status = 'completed'): Payment
{
    $vehicle = Vehicle::create([
        'name' => 'Nexus Sedan',
        'brand' => 'Nexus',
        'model' => 'Sedan',
        'year' => 2025,
        'plate_number' => 'REC-' . strtoupper(uniqid()),
        'price_per_day' => 100000,
    ]);

    $booking = Booking::create([
        'booking_reference' => 'NR-' . strtoupper(uniqid()),
        'user_id' => $user->id,
        'vehicle_id' => $vehicle->id,
        'pickup_date' => '2026-11-10',
        'return_date' => '2026-11-12',
        'pickup_location' => 'Kampala',
        'days' => 2,
        'total_amount' => 200000,
        'status' => 'active',
    ]);

    return Payment::create([
        'booking_id' => $booking->id,
        'user_id' => $user->id,
        'amount' => 200000,
        'method' => 'mtn_momo',
        'transaction_id' => 'TXN-' . strtoupper(uniqid()),
        'status' => $status,
        'paid_at' => $status === 'completed' ? now() : null,
    ]);
}

it('lets the payment owner download a completed receipt PDF', function () {
    $user = User::factory()->create();
    $payment = createReceiptPayment($user);

    $this->actingAs($user)
        ->get(route('payments.receipt', $payment))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertHeader('content-disposition', 'attachment; filename=nexusrides-receipt-' . $payment->id . '.pdf')
        ->assertSee('%PDF', false);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Payment Receipts')
        ->assertSee(route('payments.receipt', $payment));
});

it('does not allow another user to download a receipt', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $payment = createReceiptPayment($owner);

    $this->actingAs($otherUser)
        ->get(route('payments.receipt', $payment))
        ->assertNotFound();
});

it('does not issue receipts for incomplete payments', function () {
    $user = User::factory()->create();
    $payment = createReceiptPayment($user, 'pending');

    $this->actingAs($user)
        ->get(route('payments.receipt', $payment))
        ->assertNotFound();
});
