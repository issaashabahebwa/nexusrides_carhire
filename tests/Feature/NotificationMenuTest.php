<?php

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows recent notifications belonging to the signed-in user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Notification::create([
        'user_id' => $user->id,
        'title' => 'Booking update',
        'message' => 'Your vehicle booking is ready to review.',
        'type' => 'booking',
        'link' => '/bookings/1',
    ]);
    Notification::create([
        'user_id' => $otherUser->id,
        'title' => 'Private notification',
        'message' => 'This message belongs to someone else.',
        'type' => 'system',
    ]);

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('notificationPanel')
        ->assertSee('Booking update')
        ->assertSee('Your vehicle booking is ready to review.')
        ->assertDontSee('Private notification')
        ->assertDontSee('This message belongs to someone else.');
});
