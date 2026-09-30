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
        Schema::create('water_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('water_project_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
            $table->string('location');
            $table->decimal('capacity', 12, 2)->nullable();
            $table->string('capacity_unit')->default('litres');
            $table->boolean('is_operational')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_sources');
    }
};
