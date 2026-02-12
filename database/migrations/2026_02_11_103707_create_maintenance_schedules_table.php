<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            
            // Core Details
            $table->string('title'); // e.g., "Monthly Server Checkup"
            $table->string('office_college'); // New: e.g., "College of Engineering"
            $table->text('description'); // e.g., "Check disk space, updates, and cabling."
            
            // Scheduling
            $table->string('frequency'); // daily, weekly, monthly, quarterly, yearly
            $table->date('next_run_date'); // When should the next ticket be generated?
            
            // Assignment & Priority
            $table->unsignedBigInteger('assigned_to')->nullable(); // Which IT Staff handles this?
            $table->string('priority')->default('Normal');
            
            // Asset / Device Details (New)
            $table->string('device_model')->nullable();    // e.g., Dell Inspiron 15
            $table->string('property_number')->nullable(); // e.g., KSU-ICT-2023-001
            $table->string('serial_number')->nullable();   // e.g., SN123456789
            
            $table->string('category')->default('Maintenance'); // Ensure 'Maintenance' is in your categories
            $table->timestamps();

            // Foreign key for assignee
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};