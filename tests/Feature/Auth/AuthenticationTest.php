<?php

use App\Models\User;

it('login form does not expose a role selector', function () {
    $response = $this->get('/login');

    $response->assertOk();
    $response->assertDontSee('Login as');
    $response->assertDontSee('login_role');
});

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('admin users are redirected to the admin dashboard after login', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    $response->assertRedirect(
        route('admin.dashboard', absolute: false)
    );
});

test('staff users are redirected to the staff dashboard after login', function () {
    $user = User::factory()->create([
        'role' => 'staff',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    $response->assertRedirect(
        route('staff.dashboard', absolute: false)
    );
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('authenticated users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();

    $response->assertRedirect(
        route('login', absolute: false)
    );
});