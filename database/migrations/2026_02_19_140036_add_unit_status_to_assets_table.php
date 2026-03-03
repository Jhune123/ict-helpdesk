<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Check if column doesn't exist before adding to prevent errors
            if (!Schema::hasColumn('assets', 'unit_status')) {
                $table->string('unit_status')->default('Active')->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('unit_status');
        });
    }
};