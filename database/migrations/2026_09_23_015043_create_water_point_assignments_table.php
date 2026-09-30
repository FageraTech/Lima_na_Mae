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
        Schema::create('water_point_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('water_source_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('technician_name');
            $table->string('technician_phone')->nullable();
            $table->string('task');
            $table->string('status')->default('assigned');
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('critical')->default(false);
            $table->text('notes')->nullable();
            $table->index(['water_source_id', 'status']);
            $table->index('due_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_point_assignments');
    }
};
