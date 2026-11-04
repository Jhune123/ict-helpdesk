<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            $table->decimal('mandatory_days', 8, 2)->nullable();
            $table->decimal('special_privilege_days', 8, 2)->nullable();
            $table->decimal('wellness_break_days', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            $table->dropColumn(['mandatory_days', 'special_privilege_days', 'wellness_break_days']);
        });
    }
};
