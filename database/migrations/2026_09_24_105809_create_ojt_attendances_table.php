<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ojt_attendances')) {
            Schema::create('ojt_attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
                $table->date('date');
                $table->time('am_in')->nullable();
                $table->time('am_out')->nullable();
                $table->time('pm_in')->nullable();
                $table->time('pm_out')->nullable();
                $table->decimal('total_hours', 5, 2)->default(0.00);
                $table->string('proof_photo')->nullable();
                $table->string('status')->default('Not Checked In');
                $table->timestamps();

                $table->unique(['user_id', 'date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ojt_attendances');
    }
};