<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'fund_cluster')) {
                $table->string('fund_cluster')->after('entity_name');
            }
            if (!Schema::hasColumn('assets', 'par_no')) {
                $table->string('par_no')->after('fund_cluster');
            }
            if (!Schema::hasColumn('assets', 'quantity')) {
                $table->integer('quantity')->default(1)->after('par_no');
            }
            if (!Schema::hasColumn('assets', 'unit')) {
                $table->string('unit')->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('assets', 'description')) {
                $table->text('description')->nullable()->after('unit');
            }
            if (!Schema::hasColumn('assets', 'property_no')) {
                $table->string('property_no')->nullable()->after('description');
            }
            if (!Schema::hasColumn('assets', 'date_acquired')) {
                $table->date('date_acquired')->nullable()->after('property_no');
            }
            if (!Schema::hasColumn('assets', 'amount')) {
                $table->decimal('amount', 15, 2)->nullable()->after('date_acquired');
            }
            if (!Schema::hasColumn('assets', 'purpose')) {
                $table->string('purpose')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('assets', 'approved_for_issuance')) {
                $table->string('approved_for_issuance')->nullable()->after('purpose');
            }
            if (!Schema::hasColumn('assets', 'received_from')) {
                $table->string('received_from')->nullable()->after('approved_for_issuance');
            }
            if (!Schema::hasColumn('assets', 'received_by')) {
                $table->string('received_by')->nullable()->after('received_from');
            }
            if (!Schema::hasColumn('assets', 'date_counted')) {
                $table->date('date_counted')->nullable()->after('received_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $columns = [
                'fund_cluster', 'par_no', 'quantity', 'unit', 'description',
                'property_no', 'date_acquired', 'amount', 'purpose',
                'approved_for_issuance', 'received_from', 'received_by', 'date_counted'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('assets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
