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
        // 🔥 Option 2: Check if table already exists to avoid duplicate error
        if (!Schema::hasTable('queues')) {
            Schema::create('queues', function (Blueprint $table) {
                $table->id();

                $table->string('queue_number');        // MIS-001
                $table->string('prefix')->default('MIS');
                $table->string('status')->default('Waiting'); 
                $table->unsignedTinyInteger('window_number')->nullable(); // 1, 2
                $table->string('served_by')->nullable(); // Sir Jhune, Sir Reymar

                $table->timestamp('called_at')->nullable();
                $table->timestamp('served_at')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('queues')) {
            Schema::dropIfExists('queues');
        }
    }
};
