<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tasks', 'requested_by')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->string('requested_by')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tasks', 'requested_by')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('requested_by');
            });
        }
    }
};
