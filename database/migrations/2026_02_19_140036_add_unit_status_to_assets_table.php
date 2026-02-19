<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            // Adds the column. Placing it after ID for high visibility.
            $table->string('unit_status')->default('Active')->after('id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('unit_status');
        });
    }
};