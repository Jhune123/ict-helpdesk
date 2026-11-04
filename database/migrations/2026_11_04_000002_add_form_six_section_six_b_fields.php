<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->boolean('vacation_within_philippines')->default(false);
            $table->string('vacation_within_destination')->nullable();
            $table->boolean('vacation_abroad')->default(false);
            $table->string('vacation_abroad_destination')->nullable();
            $table->boolean('sick_in_hospital')->default(false);
            $table->string('sick_in_hospital_illness')->nullable();
            $table->boolean('sick_out_patient')->default(false);
            $table->string('sick_out_patient_illness')->nullable();
            $table->text('special_leave_additional_details')->nullable();
            $table->boolean('study_completion')->default(false);
            $table->boolean('study_bar_review')->default(false);
            $table->boolean('study_other_purpose_selected')->default(false);
            $table->boolean('study_monetization')->default(false);
            $table->boolean('study_terminal_leave')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn([
                'vacation_within_philippines',
                'vacation_within_destination',
                'vacation_abroad',
                'vacation_abroad_destination',
                'sick_in_hospital',
                'sick_in_hospital_illness',
                'sick_out_patient',
                'sick_out_patient_illness',
                'special_leave_additional_details',
                'study_completion',
                'study_bar_review',
                'study_other_purpose_selected',
                'study_monetization',
                'study_terminal_leave',
            ]);
        });
    }
};
