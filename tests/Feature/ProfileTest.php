<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('requires authentication to view the profile', function () {
    $this->get(route('profile.edit'))
        ->assertRedirect(route('login'));
});

it('shows and updates all editable profile details', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Personal information')
        ->assertSee('Change password')
        ->assertSee('National ID / passport number');

    $this->put(route('profile.update'), [
        'name' => 'Amina Example',
        'email' => 'amina@example.com',
        'phone' => '+256700123456',
        'address' => 'Kampala, Uganda',
        'id_number' => 'CM123456',
    ])->assertRedirect();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Amina Example',
        'email' => 'amina@example.com',
        'phone' => '+256700123456',
        'address' => 'Kampala, Uganda',
        'id_number' => 'CM123456',
    ]);
});

it('changes the password only when the current password is correct', function () {
    $user = User::factory()->create(['password' => Hash::make('old-password')]);

    $this->actingAs($user)
        ->put(route('profile.password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])
        ->assertSessionHasErrors('current_password');

    $this->assertTrue(Hash::check('old-password', $user->fresh()->password));

    $this->put(route('profile.password.update'), [
        'current_password' => 'old-password',
        'password' => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ])->assertSessionHasNoErrors();

    $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
});