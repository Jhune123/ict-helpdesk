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
        // Schema::table modifies the existing table safely without deleting data
        Schema::table('feedbacks', function (Blueprint $table) {
            
            // We use if statements to prevent "column already exists" errors
            if (!Schema::hasColumn('feedbacks', 'client_name')) {
                $table->string('client_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('feedbacks', 'office_visited')) {
                $table->string('office_visited')->nullable()->after('client_name');
            }
            if (!Schema::hasColumn('feedbacks', 'services_received')) {
                $table->string('services_received')->nullable()->after('office_visited');
            }
            if (!Schema::hasColumn('feedbacks', 'staff_assisted')) {
                $table->string('staff_assisted')->nullable()->after('services_received');
            }
            if (!Schema::hasColumn('feedbacks', 'other_staff')) {
                $table->string('other_staff')->nullable()->after('staff_assisted');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn([
                'client_name', 
                'office_visited', 
                'services_received', 
                'staff_assisted', 
                'other_staff'
            ]);
        });
    }
};