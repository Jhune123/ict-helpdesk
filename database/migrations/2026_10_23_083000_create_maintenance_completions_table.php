<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_schedule_id')
                ->nullable()
                ->constrained('maintenance_schedules')
                ->nullOnDelete();
            $table->dateTime('completed_at');
            $table->json('snapshot');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_completions');
    }
};
