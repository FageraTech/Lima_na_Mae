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
        Schema::create('irrigation_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('water_source_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('type');
            $table->string('status')->default('planned');
            $table->date('installation_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('irrigation_systems');
    }
};
