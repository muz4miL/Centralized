<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@school.com',
            'role' => 'admin',
        ]);

        // Create test principal and teacher accounts
        User::factory()->create([
            'name' => 'Dr. Sarah Principal',
            'email' => 'principal@school.com',
            'role' => 'principal',
        ]);

        User::factory()->create([
            'name' => 'Mr. Ahmed Teacher',
            'email' => 'teacher@school.com',
            'role' => 'teacher',
        ]);

        // Seed dashboard summary data
        $this->call(DashboardSummarySeeder::class);
    }
}
