<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('login form does not expose a role selector', function () {
    $response = $this->get('/login');

    $response->assertOk();
    $response->assertDontSee('Login as');
    $response->assertDontSee('login_role');
});

it('redirects admin and staff users to their role dashboard after login', function () {
    $admin = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]);

    $staff = User::factory()->create([
        'name' => 'Staff User',
        'email' => 'staff@example.com',
        'password' => Hash::make('password123'),
        'role' => 'staff',
    ]);

    $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password123',
    ])->assertRedirect('/admin/dashboard');

    auth()->logout();

    $this->post('/login', [
        'email' => $staff->email,
        'password' => 'password123',
    ])->assertRedirect('/staff/dashboard');
});
