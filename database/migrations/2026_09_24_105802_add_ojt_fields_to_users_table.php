<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_ojt')) {
                $table->boolean('is_ojt')->default(false)->after('email');
            }
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->nullable()->after('is_ojt');
            }
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete()->after('student_id');
            }
            if (!Schema::hasColumn('users', 'required_hours')) {
                $table->integer('required_hours')->default(486)->after('department_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = array_filter(['is_ojt', 'student_id', 'department_id', 'required_hours'], fn($col) => Schema::hasColumn('users', $col));
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};