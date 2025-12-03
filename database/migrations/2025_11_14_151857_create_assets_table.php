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

            $table->string('entity_name');
            $table->string('fund_cluster');
            $table->string('par_no');

            // Auto-filled / optional fields
            $table->string('name')->nullable();
            $table->string('asset_code')->nullable();

            // Main fields
            $table->integer('quantity')->default(1);
            $table->string('unit');
            $table->text('description');

            $table->string('property_no')->nullable();
            $table->date('date_acquired')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('purpose')->nullable();
            $table->string('approved_for_issuance')->nullable();
            $table->string('received_from')->nullable();
            $table->string('received_by')->nullable();
            $table->date('date_counted')->nullable();

            // Removed department + category
            // $table->string('department')->nullable();
            // $table->string('category')->nullable();

            $table->string('status')->default('Available');
            $table->string('assigned_to')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
