<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        $table->string('slug')->unique();
        $table->string('size')->nullable();
        $table->string('temperament')->nullable();
        $table->string('lifespan')->nullable();
        $table->string('energy')->nullable();
        $table->unsignedTinyInteger('friendliness')->default(3);
        $table->unsignedTinyInteger('trainability')->default(3);
        $table->unsignedTinyInteger('exerciseNeeds')->default(3);
        $table->unsignedTinyInteger('grooming')->default(3);
        $table->json('colors')->nullable();
        $table->text('description')->nullable();

        // NEW:
        $table->enum('category', ['cat', 'dog'])->default('dog');
        $table->string('image')->nullable(); // stores image path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
