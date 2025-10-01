<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddProfilePictureColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasColumn('users', 'profile_picture')) {
            DB::statement('ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) NULL');
        }
    }
}