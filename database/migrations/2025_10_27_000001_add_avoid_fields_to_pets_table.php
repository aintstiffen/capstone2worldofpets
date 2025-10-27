<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->string('avoid_title')->nullable()->after('description');
            $table->text('avoid_description')->nullable()->after('avoid_title');
        });
    }

    public function down()
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['avoid_title', 'avoid_description']);
        });
    }
};
