<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ecommerce.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address' => '123 Admin Street',
            'city' => 'Admin City',
            'state' => 'Admin State',
            'zip_code' => '12345',
            'country' => 'United States',
            'gender' => 'male',
            'status' => 'active',
        ]);

        // Create Demo Customer
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'phone' => '+1987654321',
            'address' => '456 Customer Avenue',
            'city' => 'Customer City',
            'state' => 'Customer State',
            'zip_code' => '67890',
            'country' => 'United States',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'status' => 'active',
        ]);

        // Create Demo Female Customer
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'phone' => '+1555666777',
            'address' => '789 Demo Boulevard',
            'city' => 'Demo City',
            'state' => 'Demo State',
            'zip_code' => '54321',
            'country' => 'United States',
            'date_of_birth' => '1992-05-15',
            'gender' => 'female',
            'status' => 'active',
        ]);
    }
}
