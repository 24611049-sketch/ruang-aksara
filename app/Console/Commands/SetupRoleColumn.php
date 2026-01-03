<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetupRoleColumn extends Command
{
    protected $signature = 'setup:roles';
    protected $description = 'Add role column to users table and setup default admin/owner';

    public function handle()
    {
        $this->info('🔧 Setting up role column...');

        try {
            // Check if role column exists
            if (!Schema::hasColumn('users', 'role')) {
                $this->info('📝 Adding role column...');
                DB::statement("ALTER TABLE users ADD COLUMN role ENUM('user', 'admin', 'owner') NOT NULL DEFAULT 'user' AFTER email_verified_at");
                $this->info('✅ role column added');
            } else {
                $this->info('✅ role column already exists');
            }

            // Add points column
            if (!Schema::hasColumn('users', 'points')) {
                $this->info('📝 Adding points column...');
                DB::statement("ALTER TABLE users ADD COLUMN points DECIMAL(12,2) NOT NULL DEFAULT 0");
                $this->info('✅ points column added');
            }

            // Add alamat column
            if (!Schema::hasColumn('users', 'alamat')) {
                $this->info('📝 Adding alamat column...');
                DB::statement("ALTER TABLE users ADD COLUMN alamat VARCHAR(255) NULL");
                $this->info('✅ alamat column added');
            }

            // Add telepon column
            if (!Schema::hasColumn('users', 'telepon')) {
                $this->info('📝 Adding telepon column...');
                DB::statement("ALTER TABLE users ADD COLUMN telepon VARCHAR(20) NULL");
                $this->info('✅ telepon column added');
            }

            // Set first user as admin if not set
            $firstUser = DB::table('users')->first();
            if ($firstUser && DB::table('users')->where('id', $firstUser->id)->value('role') === 'user') {
                $this->info('🔐 Setting first user as admin...');
                DB::table('users')->where('id', $firstUser->id)->update(['role' => 'admin']);
                $this->info('✅ First user set as admin');
            }

            // Show all users
            $this->info('\n📋 Current users:');
            $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
            foreach ($users as $user) {
                $this->line("   ID: {$user->id} | {$user->name} ({$user->email}) | Role: {$user->role}");
            }

            $this->info('\n✨ Setup complete!');
            
            if ($this->confirm('Do you want to set an admin user?')) {
                $userId = $this->ask('Enter user ID to make admin');
                if ($userId) {
                    DB::table('users')->where('id', $userId)->update(['role' => 'admin']);
                    $this->info("✅ User {$userId} set as admin");
                }
            }

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
