<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('office_department')->nullable();
            $table->string('position')->nullable();
            $table->string('designation')->nullable();
            $table->decimal('monthly_basic_salary', 12, 2)->nullable();
            $table->date('filed_at');
            $table->string('leave_type');
            $table->text('details');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('working_days');
            $table->boolean('commutation_requested')->default(false);
            $table->string('status')->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'start_date', 'end_date']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('vacation_days', 8, 2)->nullable();
            $table->decimal('sick_days', 8, 2)->nullable();
            $table->decimal('teachers_days', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
        Schema::dropIfExists('leave_applications');
    }
};
