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
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            // Add Office/College after the Title
            $table->string('office_college')->nullable()->after('title');

            // Add Device details after Priority (or Assigned To)
            $table->string('device_model')->nullable()->after('priority');
            $table->string('property_number')->nullable()->after('device_model');
            $table->string('serial_number')->nullable()->after('property_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            $table->dropColumn([
                'office_college',
                'device_model',
                'property_number',
                'serial_number'
            ]);
        });
    }
};