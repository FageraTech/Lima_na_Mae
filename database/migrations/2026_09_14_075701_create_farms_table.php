<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('location');
            $table->decimal('size', 10, 2)->nullable();
            $table->string('size_unit')->default('acres');
            $table->string('primary_crop')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farms');
    }
};
