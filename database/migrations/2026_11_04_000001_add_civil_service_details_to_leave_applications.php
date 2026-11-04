<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->string('applicant_last_name')->nullable()->after('user_id');
            $table->string('applicant_first_name')->nullable()->after('applicant_last_name');
            $table->string('applicant_middle_name')->nullable()->after('applicant_first_name');
            $table->string('vacation_location')->nullable()->after('details');
            $table->string('vacation_destination')->nullable()->after('vacation_location');
            $table->string('sick_treatment')->nullable()->after('vacation_destination');
            $table->string('sick_illness')->nullable()->after('sick_treatment');
            $table->string('special_leave_illness')->nullable()->after('sick_illness');
            $table->string('study_purpose')->nullable()->after('special_leave_illness');
            $table->string('other_leave_type')->nullable()->after('study_purpose');
            $table->text('other_purpose')->nullable()->after('other_leave_type');
        });
    }

    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_last_name',
                'applicant_first_name',
                'applicant_middle_name',
                'vacation_location',
                'vacation_destination',
                'sick_treatment',
                'sick_illness',
                'special_leave_illness',
                'study_purpose',
                'other_leave_type',
                'other_purpose',
            ]);
        });
    }
};
