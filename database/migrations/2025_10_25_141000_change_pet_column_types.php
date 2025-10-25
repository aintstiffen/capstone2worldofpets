<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Update several pet columns to VARCHAR so admin can store manual text values.
     * Uses raw ALTER TABLE statements for MySQL to avoid requiring doctrine/dbal.
     */
    public function up()
    {
        $table = 'pets';

        // Only apply raw ALTER statements when using MySQL (safe path for your environment)
        $defaultConnection = config('database.default');
        $driver = config("database.connections.{$defaultConnection}.driver");

        // If not MySQL, still attempt the statements — adapt if your driver differs.
        if ($driver === 'mysql' || $driver === 'mysql2') {
            if (Schema::hasColumn($table, 'average_weight')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `average_weight` VARCHAR(255) NULL;");
            }

            if (Schema::hasColumn($table, 'price_range')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `price_range` VARCHAR(255) NULL;");
            }

            if (Schema::hasColumn($table, 'energy_level')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `energy_level` VARCHAR(255) NULL;");
            }

            if (Schema::hasColumn($table, 'origin')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `origin` VARCHAR(255) NULL;");
            }

            if (Schema::hasColumn($table, 'friendliness')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `friendliness` VARCHAR(255) NULL;");
            }

            if (Schema::hasColumn($table, 'exerciseNeeds')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `exerciseNeeds` VARCHAR(255) NULL;");
            }

            if (Schema::hasColumn($table, 'grooming')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `grooming` VARCHAR(255) NULL;");
            }
        } else {
            // Non-MySQL drivers: try a safer approach with schema builder, but note this may require doctrine/dbal.
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn('pets', 'average_weight')) {
                    $table->string('average_weight')->nullable()->change();
                }
                if (Schema::hasColumn('pets', 'price_range')) {
                    $table->string('price_range')->nullable()->change();
                }
                if (Schema::hasColumn('pets', 'energy_level')) {
                    $table->string('energy_level')->nullable()->change();
                }
                if (Schema::hasColumn('pets', 'origin')) {
                    $table->string('origin')->nullable()->change();
                }
                if (Schema::hasColumn('pets', 'friendliness')) {
                    $table->string('friendliness')->nullable()->change();
                }
                if (Schema::hasColumn('pets', 'exerciseNeeds')) {
                    $table->string('exerciseNeeds')->nullable()->change();
                }
                if (Schema::hasColumn('pets', 'grooming')) {
                    $table->string('grooming')->nullable()->change();
                }
            });
        }
    }

    public function down()
    {
        $table = 'pets';

        // Reverting types can be lossy; down() attempts to restore integer columns where sensible.
        $defaultConnection = config('database.default');
        $driver = config("database.connections.{$defaultConnection}.driver");

        if ($driver === 'mysql' || $driver === 'mysql2') {
            if (Schema::hasColumn($table, 'origin')) {
                // origin might have been textual; revert to VARCHAR (can't safely convert to int automatically)
                DB::statement("ALTER TABLE `{$table}` MODIFY `origin` VARCHAR(255) NULL;");
            }

            // Attempt to revert fields that were previously numeric back to INT. This will fail if non-numeric data exists.
            if (Schema::hasColumn($table, 'friendliness')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `friendliness` INT NULL;");
            }

            if (Schema::hasColumn($table, 'exerciseNeeds')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `exerciseNeeds` INT NULL;");
            }

            if (Schema::hasColumn($table, 'grooming')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `grooming` INT NULL;");
            }

            // Leave human-friendly text columns as VARCHAR in down(), to avoid data loss for average_weight/price_range/energy_level
            if (Schema::hasColumn($table, 'average_weight')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `average_weight` VARCHAR(255) NULL;");
            }
            if (Schema::hasColumn($table, 'price_range')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `price_range` VARCHAR(255) NULL;");
            }
            if (Schema::hasColumn($table, 'energy_level')) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `energy_level` VARCHAR(255) NULL;");
            }
        } else {
            // Non-MySQL fallback: try schema builder change (may require doctrine/dbal)
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn('pets', 'friendliness')) {
                    $table->integer('friendliness')->nullable()->change();
                }
                if (Schema::hasColumn('pets', 'exerciseNeeds')) {
                    $table->integer('exerciseNeeds')->nullable()->change();
                }
                if (Schema::hasColumn('pets', 'grooming')) {
                    $table->integer('grooming')->nullable()->change();
                }
            });
        }
    }
};
