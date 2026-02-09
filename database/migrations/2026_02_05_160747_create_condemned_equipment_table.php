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
        Schema::table('condemned_equipments', function (Blueprint $table) {
            // Added item_name to fix the "Field doesn't have a default value" error
            $table->string('item_name')->nullable()->after('id');
            
            // Added property_no because it is required in your Controller/Form
            $table->string('property_no')->nullable()->after('item_name');

            // Remaining fields
            $table->string('ticket_number')->nullable()->after('property_no');
            $table->string('title')->nullable()->after('ticket_number');
            $table->text('description')->nullable()->after('title');
            $table->string('equipment_type')->nullable()->after('description');
            $table->string('brand_model')->nullable()->after('equipment_type');
            $table->string('serial_no')->nullable()->after('brand_model');
            $table->string('category')->nullable()->after('serial_no');
            $table->string('department')->nullable()->after('category');
            $table->string('it_personnel')->nullable()->after('department');
            $table->string('client_name')->nullable()->after('it_personnel');
            $table->string('priority')->nullable()->after('client_name');
            $table->string('contact')->nullable()->after('priority');
            $table->string('status')->nullable()->after('contact');
            $table->dateTime('date_submitted')->nullable()->after('status');
            $table->dateTime('date_finished')->nullable()->after('date_submitted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('condemned_equipments', function (Blueprint $table) {
            $table->dropColumn([
                'item_name',
                'property_no',
                'ticket_number', 'title', 'description', 'equipment_type', 'brand_model',
                'serial_no', 'category', 'department', 'it_personnel', 'client_name',
                'priority', 'contact', 'status', 'date_submitted', 'date_finished'
            ]);
        });
    }
};