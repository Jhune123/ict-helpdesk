<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. If the table doesn't exist, CREATE it
        if (!Schema::hasTable('condemned_equipments')) {
            Schema::create('condemned_equipments', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number')->nullable()->unique(); // New column
                $table->string('property_no')->nullable();
                $table->string('item_name')->nullable();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->string('attachment_path')->nullable(); // New column
                $table->string('equipment_type')->nullable();
                $table->string('brand_model')->nullable();
                $table->string('serial_no')->nullable();
                $table->string('category')->nullable();
                $table->string('department')->nullable();
                $table->string('it_personnel')->nullable();
                $table->string('client_name')->nullable();
                $table->string('priority')->nullable();
                $table->string('contact')->nullable();
                $table->string('status')->default('Pending');
                $table->timestamp('date_submitted')->nullable();
                $table->timestamp('date_condemned')->nullable();
                $table->timestamps();
            });
        } 
        
        // 2. If table DOES exist (from a partial run), ensure new columns are added
        else {
            Schema::table('condemned_equipments', function (Blueprint $table) {
                if (!Schema::hasColumn('condemned_equipments', 'ticket_number')) {
                    $table->string('ticket_number')->nullable()->unique();
                }
                if (!Schema::hasColumn('condemned_equipments', 'attachment_path')) {
                    $table->string('attachment_path')->nullable();
                }
                if (!Schema::hasColumn('condemned_equipments', 'item_name')) {
                    $table->string('item_name')->nullable();
                }
                // Ensure date columns exist
                if (!Schema::hasColumn('condemned_equipments', 'date_condemned')) {
                    $table->timestamp('date_condemned')->nullable();
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('condemned_equipments');
    }
};