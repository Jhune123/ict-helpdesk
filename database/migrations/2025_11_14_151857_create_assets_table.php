<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            // Core identification fields
            $table->string('asset_code')->nullable();
            $table->string('entity_name')->nullable();
            $table->string('fund_cluster')->nullable();
            $table->string('par_no')->nullable();

            // Asset details
            $table->string('name')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('category')->nullable();
            $table->string('location')->nullable();

            // Inventory and quantity
            $table->integer('quantity')->default(1)->nullable();
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            $table->string('property_no')->nullable();

            // Financial and acquisition details
            $table->date('date_acquired')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('supplier')->nullable();

            // Status and assignment
            $table->string('status')->default('Available');
            $table->string('assigned_to')->nullable();

            // Purpose and approval
            $table->text('purpose')->nullable();
            $table->string('approved_for_issuance')->nullable();

            // Receipt information
            $table->string('received_from')->nullable();
            $table->string('received_by')->nullable();
            $table->date('date_counted')->nullable();

            // Additional notes
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
