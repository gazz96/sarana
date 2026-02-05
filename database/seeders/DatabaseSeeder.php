<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create test users for each role
        $roles = \App\Models\Role::all();
        
        foreach ($roles as $role) {
            \App\Models\User::firstOrCreate(
                ['email' => strtolower($role->name) . '@test.com'],
                [
                    'name' => 'Test ' . ucfirst($role->name),
                    'role_id' => $role->id,
                    'password' => bcrypt('password123'),
                ]
            );
        }

        // Create sample location
        $location = \App\Models\Location::firstOrCreate(
            ['name' => 'Laboratorium Komputer'],
            [
                'building' => 'Gedung A',
                'floor' => '2',
                'description' => 'Lab komputer utama'
            ]
        );

        // Create sample goods
        \App\Models\Good::firstOrCreate(
            ['code' => 'BRG-001'],
            [
                'name' => 'Desktop PC',
                'location_id' => $location->id,
                'status' => 'AKTIF',
                'quantity' => 10,
                'description' => 'Komputer desktop untuk lab'
            ]
        );

        // Create sample problem
        $guru = \App\Models\User::where('email', 'guru@test.com')->first();
        if ($guru) {
            \App\Models\Problem::firstOrCreate(
                ['code' => 'PRB-TEST-001'],
                [
                    'user_id' => $guru->id,
                    'issue' => 'AC tidak berfungsi dengan baik',
                    'status' => '0',
                    'date' => now()
                ]
            );
        }
    }
}
