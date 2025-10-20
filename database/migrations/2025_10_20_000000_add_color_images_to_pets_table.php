<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pets', function (Blueprint $table) {
            if (!Schema::hasColumn('pets', 'color_images')) {
                $table->json('color_images')->nullable()->after('colors');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pets', function (Blueprint $table) {
            if (Schema::hasColumn('pets', 'color_images')) {
                $table->dropColumn('color_images');
            }
        });
    }
};
