<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Machine;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@bubblesdrop.com',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
        ]);

        // Staff account
        User::create([
            'name' => 'Laundry Staff',
            'email' => 'staff@bubblesdrop.com',
            'password' => Hash::make('staff12345'),
            'role' => 'staff',
        ]);

        // System services
        Service::create([
            'service_name' => 'Wash, Dry, and Fold',
            'price' => 210,
            'description' => 'Complete wash, dry, and fold laundry service.',
        ]);

        Service::create([
            'service_name' => 'Self Service',
            'price' => 100,
            'description' => 'Customer-operated washing service.',
        ]);

        Service::create([
            'service_name' => 'Detergent',
            'price' => 10,
            'description' => 'Laundry detergent.',
        ]);

        Service::create([
            'service_name' => 'Fabric Conditioner',
            'price' => 15,
            'description' => 'Laundry fabric conditioner.',
        ]);

        Machine::create([
            'machine_name' => 'Machine 1',
            'status' => 'Available',
        ]);

        Machine::create([
            'machine_name' => 'Machine 2',
            'status' => 'Available',
        ]);

        Machine::create([
            'machine_name' => 'Machine 3',
            'status' => 'Available',
        ]);

        Machine::create([
            'machine_name' => 'Machine 4',
            'status' => 'Available',
        ]);
    }
}