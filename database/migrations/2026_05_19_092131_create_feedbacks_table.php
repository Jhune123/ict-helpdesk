<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('feedbacks');

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // 👇 NEW: Added Client Info Fields 👇
            $table->string('client_name')->nullable(); // Optional
            $table->string('office_visited')->nullable();
            $table->string('services_received')->nullable();
            $table->string('staff_assisted')->nullable();
            $table->string('other_staff')->nullable(); // Optional

            // Client Type & Demographics
            $table->string('client_type')->nullable(); 
            $table->string('agency_name')->nullable();
            $table->string('sex')->nullable(); 
            $table->integer('age')->nullable();
            
            // CC and SQD
            $table->integer('cc1')->nullable();
            $table->integer('cc2')->nullable();
            $table->integer('cc3')->nullable();
            $table->integer('sqd0')->nullable();
            $table->integer('sqd1')->nullable();
            $table->integer('sqd2')->nullable();
            $table->integer('sqd3')->nullable();
            $table->integer('sqd4')->nullable();
            $table->integer('sqd5')->nullable();
            $table->integer('sqd6')->nullable();
            $table->integer('sqd7')->nullable();
            $table->integer('sqd8')->nullable();

            $table->text('suggestions')->nullable();
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};