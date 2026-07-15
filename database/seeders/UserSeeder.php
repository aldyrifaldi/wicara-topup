<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Level;
use App\Models\Refferal;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table.
     */
    public function run(): void
    {
        // --- Create Admin User ---
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@wicara.com',
            'password' => Hash::make('password123'),
            'role' => UserRole::ADMIN->value,
            'phone' => '081234567890',
            'email_verified_at' => now(),
            'image' => null,
            'balance' => 0,
            'points' => 0,
            'status' => 'active',
        ]);

        // --- Create 10 Sample Customer Users ---
        $customers = [];
        $levels = Level::all();

        for ($i = 1; $i <= 10; $i++) {
            $customer = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'role' => UserRole::CUSTOMER->value,
                'phone' => fake()->numerify('08##########'),
                'email_verified_at' => now(),
                'image' => null,
                'balance' => fake()->randomElement([0, 50000, 100000, 250000, 500000]),
                'points' => fake()->numberBetween(0, 500),
                'status' => 'active',
            ]);

            $customers[] = $customer;

            // Assign random level to customer if levels exist
            if ($levels->isNotEmpty()) {
                $level = $levels->random();

                UserLevel::create([
                    'user_id' => $customer->id,
                    'level_id' => $level->id,
                ]);
            }
        }

        // --- Create Sample Referrals ---
        // Admin refers first 3 customers
        for ($i = 0; $i < min(3, count($customers)); $i++) {
            Refferal::create([
                'user_id' => $customers[$i]->id,
                'from_user_id' => $admin->id,
                'code' => 'ADMIN-' . strtoupper(uniqid()),
            ]);
        }

        // Customers refer other customers
        for ($i = 3; $i < count($customers); $i++) {
            $referrer = $customers[$i - 3] ?? $customers[0];

            Refferal::create([
                'user_id' => $customers[$i]->id,
                'from_user_id' => $referrer->id,
                'code' => strtoupper(substr($referrer->name, 0, 4)) . '-' . strtoupper(uniqid()),
            ]);
        }
    }
}
