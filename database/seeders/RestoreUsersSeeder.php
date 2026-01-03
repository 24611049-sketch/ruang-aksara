<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestoreUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Owner
        User::create([
            'name' => 'Ruang Aksara',
            'email' => 'ruangg.aksara@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);
        echo "✅ Owner created: ruangg.aksara@gmail.com\n";

        // Admin
        User::create([
            'name' => 'Admin Ruang Aksara',
            'email' => 'admin@ruangaksara.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        echo "✅ Admin created: admin@ruangaksara.com\n";

        // Regular User - Kipli
        User::create([
            'name' => 'Kipli',
            'email' => 'kipli@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
        echo "✅ User created: kipli@example.com\n";
    }
}
