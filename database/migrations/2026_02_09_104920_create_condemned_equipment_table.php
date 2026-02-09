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
        Schema::create('condemned_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // TIC-XXXXX
            $table->string('property_no');
            $table->string('item_name');
            $table->string('title');
            $table->text('description')->nullable();
            
            // ✅ This is the new column you asked for
            $table->string('attachment_path')->nullable(); 

            $table->string('equipment_type'); // e.g., Monitor, CPU
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Low');
            $table->enum('status', ['Open', 'In Progress', 'Finished', 'Closed', 'Condemned'])->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condemned_equipment');
    }
};