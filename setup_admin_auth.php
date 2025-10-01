<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

echo "Setting up admin authentication...\n";

try {
    // Run the migration if it hasn't been run
    if (!Schema::hasTable('admins')) {
        echo "Creating admins table...\n";
        Artisan::call('migrate', ['--path' => 'database/migrations/2024_09_13_000000_create_admins_table.php']);
        echo "Admins table created successfully.\n";
    } else {
        echo "Admins table already exists.\n";
    }

    // Create admin user if it doesn't exist
    if (!Admin::where('email', 'admin@petsofworld.test')->exists()) {
        echo "Creating admin user...\n";
        Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@petsofworld.test',
            'password' => Hash::make('password'),
        ]);
        echo "Admin user created successfully.\n";
        echo "Email: admin@petsofworld.test\n";
        echo "Password: password\n";
    } else {
        echo "Admin user already exists.\n";
    }

    echo "\nSetup completed successfully!\n";
    echo "Regular users can now only log in via /login\n";
    echo "Admins can now only log in via /admin/login\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}