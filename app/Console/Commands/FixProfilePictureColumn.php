<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixProfilePictureColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:profile-column';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the profile_picture column in users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for profile_picture column...');
        
        if (!Schema::hasColumn('users', 'profile_picture')) {
            $this->info('Profile picture column does not exist. Adding it now...');
            
            try {
                DB::statement('ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) NULL');
                $this->info('Profile picture column added successfully!');
                
                // Register the migration as completed
                if (!DB::table('migrations')->where('migration', '2025_09_12_000000_add_profile_picture_to_users_table')->exists()) {
                    DB::table('migrations')->insert([
                        'migration' => '2025_09_12_000000_add_profile_picture_to_users_table',
                        'batch' => DB::table('migrations')->max('batch') + 1
                    ]);
                    $this->info('Migration record added to migrations table.');
                }
                
                return 0;
            } catch (\Exception $e) {
                $this->error('Error adding profile_picture column: ' . $e->getMessage());
                return 1;
            }
        } else {
            $this->info('Profile picture column already exists.');
            return 0;
        }
    }
}