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
            $table->string('service_type')->nullable()->after('queue_number');
            $table->string('served_by')->nullable()->after('window_number');
            $table->timestamp('called_at')->nullable()->after('served_by');
            $table->timestamp('served_at')->nullable()->after('called_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn(['service_type', 'served_by', 'called_at', 'served_at']);
        });
    }
};
