<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * We've decided not to add these columns
     */
    public function up(): void
    {
        // Removed location columns implementation
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need for down migration as we're not adding columns
    }
};