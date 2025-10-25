<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Note: Renaming columns may require the doctrine/dbal package for some database drivers.
     * If you receive errors running this migration, run:
     *   composer require doctrine/dbal
     *
     * This migration renames legacy columns to the new names used by the Filament resource:
     * - size -> average_weight
     * - temperament -> price_range
     * - energy -> energy_level
     * - trainability -> origin
     */
    public function up()
    {
        Schema::table('pets', function (Blueprint $table) {
            if (Schema::hasColumn('pets', 'size') && ! Schema::hasColumn('pets', 'average_weight')) {
                $table->renameColumn('size', 'average_weight');
            }

            if (Schema::hasColumn('pets', 'temperament') && ! Schema::hasColumn('pets', 'price_range')) {
                $table->renameColumn('temperament', 'price_range');
            }

            if (Schema::hasColumn('pets', 'energy') && ! Schema::hasColumn('pets', 'energy_level')) {
                $table->renameColumn('energy', 'energy_level');
            }

            if (Schema::hasColumn('pets', 'trainability') && ! Schema::hasColumn('pets', 'origin')) {
                $table->renameColumn('trainability', 'origin');
            }
        });
    }

    public function down()
    {
        Schema::table('pets', function (Blueprint $table) {
            if (Schema::hasColumn('pets', 'average_weight') && ! Schema::hasColumn('pets', 'size')) {
                $table->renameColumn('average_weight', 'size');
            }

            if (Schema::hasColumn('pets', 'price_range') && ! Schema::hasColumn('pets', 'temperament')) {
                $table->renameColumn('price_range', 'temperament');
            }

            if (Schema::hasColumn('pets', 'energy_level') && ! Schema::hasColumn('pets', 'energy')) {
                $table->renameColumn('energy_level', 'energy');
            }

            if (Schema::hasColumn('pets', 'origin') && ! Schema::hasColumn('pets', 'trainability')) {
                $table->renameColumn('origin', 'trainability');
            }
        });
    }
};
