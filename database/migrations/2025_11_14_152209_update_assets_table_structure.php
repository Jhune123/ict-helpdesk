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
        Schema::table('assets', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('assets', 'asset_code')) {
                $table->string('asset_code')->unique()->after('id');
            }
            if (!Schema::hasColumn('assets', 'brand')) {
                $table->string('brand')->nullable()->after('name');
            }
            if (!Schema::hasColumn('assets', 'model')) {
                $table->string('model')->nullable()->after('brand');
            }
            if (!Schema::hasColumn('assets', 'serial_number')) {
                $table->string('serial_number')->nullable()->after('model');
            }
            if (!Schema::hasColumn('assets', 'category')) {
                $table->string('category')->nullable()->after('serial_number');
            }
            if (!Schema::hasColumn('assets', 'location')) {
                $table->string('location')->nullable()->after('category');
            }
            if (!Schema::hasColumn('assets', 'status')) {
                $table->string('status')->default('Available')->after('location');
            }
            if (!Schema::hasColumn('assets', 'purchase_date')) {
                $table->date('purchase_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('assets', 'cost')) {
                $table->decimal('cost', 12, 2)->nullable()->after('purchase_date');
            }
            if (!Schema::hasColumn('assets', 'supplier')) {
                $table->string('supplier')->nullable()->after('cost');
            }
            if (!Schema::hasColumn('assets', 'assigned_to')) {
                $table->string('assigned_to')->nullable()->after('supplier');
            }
            if (!Schema::hasColumn('assets', 'notes')) {
                $table->text('notes')->nullable()->after('assigned_to');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Remove the added columns on rollback
            $columns = [
                'asset_code', 'brand', 'model', 'serial_number', 'category',
                'location', 'status', 'purchase_date', 'cost', 'supplier',
                'assigned_to', 'notes'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('assets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
