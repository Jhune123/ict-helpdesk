<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Check if the table exists first
        if (!Schema::hasTable('maintenance_schedules')) {
            Schema::create('maintenance_schedules', function (Blueprint $table) {
                $table->id();
                $table->string('title'); 
                $table->string('office_college'); 
                $table->text('description'); 
                $table->string('frequency'); 
                $table->date('next_run_date'); 
                $table->unsignedBigInteger('assigned_to')->nullable(); 
                $table->string('priority')->default('Normal');
                $table->string('device_model')->nullable();    
                $table->string('property_number')->nullable(); 
                $table->string('serial_number')->nullable();   
                $table->string('category')->default('Maintenance'); 
                $table->timestamps();

                $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            });
        } else {
            // 2. If table exists, check for the missing 'office_college' column specifically
            Schema::table('maintenance_schedules', function (Blueprint $table) {
                if (!Schema::hasColumn('maintenance_schedules', 'office_college')) {
                    $table->string('office_college')->after('title')->nullable();
                }
                
                // Add checks for other newer columns if needed
                if (!Schema::hasColumn('maintenance_schedules', 'device_model')) {
                    $table->string('device_model')->nullable();
                }
                if (!Schema::hasColumn('maintenance_schedules', 'property_number')) {
                    $table->string('property_number')->nullable();
                }
                if (!Schema::hasColumn('maintenance_schedules', 'serial_number')) {
                    $table->string('serial_number')->nullable();
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};