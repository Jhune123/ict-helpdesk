<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('equipment_type')->nullable()->after('description');
            $table->string('brand_model')->nullable()->after('equipment_type');
            $table->string('serial_no')->nullable()->after('brand_model');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'equipment_type',
                'brand_model',
                'serial_no',
            ]);
        });
    }
};
