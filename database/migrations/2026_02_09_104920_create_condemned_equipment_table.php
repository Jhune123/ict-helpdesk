<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. If the table doesn't exist at all, CREATE it from scratch
        if (!Schema::hasTable('condemned_equipments')) {
            Schema::create('condemned_equipments', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number')->nullable()->unique();
                $table->string('property_no')->nullable();
                $table->string('item_name')->nullable();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->string('attachment_path')->nullable();
                $table->string('equipment_type')->nullable();
                $table->string('brand_model')->nullable();
                $table->string('serial_no')->nullable();
                $table->string('category')->nullable();
                $table->string('department')->nullable();
                $table->string('it_personnel')->nullable();
                $table->string('client_name')->nullable();
                $table->string('priority')->nullable();
                $table->string('contact')->nullable();
                $table->string('status')->default('Condemned');
                $table->timestamp('date_submitted')->nullable();
                $table->timestamp('date_condemned')->nullable();
                $table->timestamp('date_finished')->nullable(); // Added to match Controller
                $table->timestamps();
            });
        } 
        
        // 2. If table DOES exist (from a partial run), safely inject missing columns
        else {
            Schema::table('condemned_equipments', function (Blueprint $table) {
                if (!Schema::hasColumn('condemned_equipments', 'ticket_number')) {
                    $table->string('ticket_number')->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('condemned_equipments', 'attachment_path')) {
                    $table->string('attachment_path')->nullable()->after('description');
                }
                if (!Schema::hasColumn('condemned_equipments', 'item_name')) {
                    $table->string('item_name')->nullable()->after('property_no');
                }
                if (!Schema::hasColumn('condemned_equipments', 'date_condemned')) {
                    $table->timestamp('date_condemned')->nullable()->after('date_submitted');
                }
                if (!Schema::hasColumn('condemned_equipments', 'date_finished')) {
                    $table->timestamp('date_finished')->nullable()->after('date_condemned');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('condemned_equipments');
    }
};