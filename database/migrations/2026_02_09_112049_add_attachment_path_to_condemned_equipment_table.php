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
    // We are adding the 'attachment_path' column to the 'condemned_equipments' table
    Schema::table('condemned_equipments', function (Blueprint $table) {
        $table->string('attachment_path')->nullable()->after('description');
    });
}

public function down()
{
    Schema::table('condemned_equipments', function (Blueprint $table) {
        $table->dropColumn('attachment_path');
    });
}
};
