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
    Schema::table('categories', function (Blueprint $table) {
        // Add slug for the sidebar links
        $table->string('slug')->nullable()->unique()->after('name');
        // Add is_active to manage category visibility
        $table->boolean('is_active')->default(true)->after('description');
    });
}

public function down(): void
{
    Schema::table('categories', function (Blueprint $table) {
        $table->dropColumn(['slug', 'is_active']);
    });
}
};
