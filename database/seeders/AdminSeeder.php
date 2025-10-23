<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@petsofworld.test',
            'password' => Hash::make('password'),
        ]);
                Admin::create([
            'name' => 'Second Admin',
            'email' => 'admin2@petsofworld.test',
            'password' => Hash::make('another_password'),
        ]);
    }
}