<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ojt_attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('ojt_attendances', 'date')) {
                $table->date('date')->after('user_id');
            }
            if (!Schema::hasColumn('ojt_attendances', 'department_id')) {
                $table->foreignId('department_id')->nullable()->after('date')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('ojt_attendances', 'am_in')) {
                $table->time('am_in')->nullable()->after('department_id');
            }
            if (!Schema::hasColumn('ojt_attendances', 'am_out')) {
                $table->time('am_out')->nullable()->after('am_in');
            }
            if (!Schema::hasColumn('ojt_attendances', 'pm_in')) {
                $table->time('pm_in')->nullable()->after('am_out');
            }
            if (!Schema::hasColumn('ojt_attendances', 'pm_out')) {
                $table->time('pm_out')->nullable()->after('pm_in');
            }
            if (!Schema::hasColumn('ojt_attendances', 'status')) {
                $table->string('status')->default('Not Checked In')->after('pm_out');
            }
            if (!Schema::hasColumn('ojt_attendances', 'proof_photo')) {
                $table->string('proof_photo')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ojt_attendances', function (Blueprint $table) {
            $table->dropColumn(['date', 'department_id', 'am_in', 'am_out', 'pm_in', 'pm_out', 'status', 'proof_photo']);
        });
    }
};