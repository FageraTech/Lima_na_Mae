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
        Schema::table('water_sources', function (Blueprint $table) {
            $table->string('lifecycle_status')->default('operational')->after('is_operational');
            $table->decimal('latitude', 10, 7)->nullable()->after('location');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->unsignedInteger('estimated_users')->nullable()->after('longitude');
            $table->timestamp('last_checked_at')->nullable()->after('estimated_users');
            $table->timestamp('verified_at')->nullable()->after('last_checked_at');
            $table->timestamp('decommissioned_at')->nullable()->after('verified_at');

            $table->index(['lifecycle_status', 'last_checked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('water_sources', function (Blueprint $table) {
            $table->dropIndex(['water_sources_lifecycle_status_last_checked_at_index']);
            $table->dropColumn([
                'lifecycle_status',
                'latitude',
                'longitude',
                'estimated_users',
                'last_checked_at',
                'verified_at',
                'decommissioned_at',
            ]);
        });
    }
};
