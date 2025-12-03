<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('fund_cluster')->nullable();
            $table->string('par_no')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            $table->string('property_no')->nullable();
            $table->date('date_acquired')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('purpose')->nullable();
            $table->string('approved_for_issuance')->nullable();
            $table->string('received_from')->nullable();
            $table->string('received_by')->nullable();
            $table->date('date_counted')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'entity_name', 'fund_cluster', 'par_no', 'quantity', 'unit', 'description',
                'property_no', 'date_acquired', 'amount', 'purpose', 'approved_for_issuance',
                'received_from', 'received_by', 'date_counted'
            ]);
        });
    }
};
