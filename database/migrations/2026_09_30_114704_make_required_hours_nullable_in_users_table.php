<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->decimal('required_hours', 8, 2)->nullable()->default(0)->change();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->decimal('required_hours', 8, 2)->nullable(false)->change();
    });
}
};
