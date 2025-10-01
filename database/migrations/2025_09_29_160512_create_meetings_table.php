<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('meetings', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // <--- make sure this is here
        $table->date('date');
        $table->time('start_time');
        $table->time('end_time');
        $table->string('location');
        $table->string('participants')->nullable();
        $table->text('remarks')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
