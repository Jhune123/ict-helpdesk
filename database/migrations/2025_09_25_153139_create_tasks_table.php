<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->date('date');
    $table->string('description');
    $table->string('requested_by');
    $table->string('location');
    $table->time('start_time')->nullable();   // Start
    $table->time('end_time')->nullable();     // End
    $table->string('remarks')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
