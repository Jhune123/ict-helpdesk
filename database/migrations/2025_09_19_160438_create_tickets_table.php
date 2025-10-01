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
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('Pending');
            $table->string('priority')->default('Normal');

            // ✅ Correct: foreign key to categories
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            $table->string('client_name')->nullable();
            $table->string('department')->nullable();
            $table->date('date_submitted')->nullable();
            $table->date('date_finished')->nullable();
            $table->string('contact_number')->nullable();

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
