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
        Schema::table('queues', function (Blueprint $table) {
            if (!Schema::hasColumn('queues', 'service_type')) {
                $table->string('service_type')->nullable()->after('queue_number');
            }
            if (!Schema::hasColumn('queues', 'served_by')) {
                $table->string('served_by')->nullable()->after('window_number');
            }
            if (!Schema::hasColumn('queues', 'called_at')) {
                $table->timestamp('called_at')->nullable()->after('served_by');
            }
            if (!Schema::hasColumn('queues', 'served_at')) {
                $table->timestamp('served_at')->nullable()->after('called_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('queues', 'service_type')) {
                $columnsToDrop[] = 'service_type';
            }
            if (Schema::hasColumn('queues', 'served_by')) {
                $columnsToDrop[] = 'served_by';
            }
            if (Schema::hasColumn('queues', 'called_at')) {
                $columnsToDrop[] = 'called_at';
            }
            if (Schema::hasColumn('queues', 'served_at')) {
                $columnsToDrop[] = 'served_at';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};