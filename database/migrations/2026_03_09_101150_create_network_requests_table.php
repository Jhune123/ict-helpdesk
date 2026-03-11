<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('network_requests', function (Blueprint $table) {
            $table->id();
            // Link to the main ticket and the user
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // 1. Requestor Information (Name/Email are pulled from users table)
            $table->string('office');
            $table->string('contact_number');
            
            // 2. Request Details
            $table->string('request_type');
            $table->string('request_type_others')->nullable();
            $table->string('location')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('device');
            $table->string('device_others')->nullable();
            
            // 3. Project Request Timeline
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->text('remarks')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('network_requests');
    }
};
