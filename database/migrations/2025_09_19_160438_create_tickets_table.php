<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->default('Pending');
            $table->string('priority')->default('Normal');
            $table->string('department')->nullable();

            // Foreign keys
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();

            // Client information
            $table->string('client_name')->nullable();
            $table->string('contact_number')->nullable();

            // Dates
            $table->datetime('date_submitted')->nullable();
            $table->datetime('date_finished')->nullable();

            // User assignments
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
