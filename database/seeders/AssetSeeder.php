<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets = [
            [
                'asset_code' => 'AST-001',
                'name' => 'Dell Laptop',
                'brand' => 'Dell',
                'model' => 'Latitude 5420',
                'serial_number' => 'DL5420SN001',
                'category' => 'Hardware',
                'location' => 'IT Office',
                'status' => 'Available',
                'purchase_date' => Carbon::parse('2023-01-15'),
                'cost' => 65000,
                'supplier' => 'Dell PH',
                'assigned_to' => null,
                'notes' => 'Company laptop for IT Staff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'asset_code' => 'AST-002',
                'name' => 'Canon Printer',
                'brand' => 'Canon',
                'model' => 'PIXMA G6020',
                'serial_number' => 'CN6020SN002',
                'category' => 'Hardware',
                'location' => 'Admin Office',
                'status' => 'In Use',
                'purchase_date' => Carbon::parse('2022-09-20'),
                'cost' => 12000,
                'supplier' => 'Canon PH',
                'assigned_to' => 'Admin',
                'notes' => 'Shared office printer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'asset_code' => 'AST-003',
                'name' => 'Microsoft Office 365',
                'brand' => 'Microsoft',
                'model' => 'Subscription 1 Year',
                'serial_number' => null,
                'category' => 'Software',
                'location' => 'IT Office',
                'status' => 'Available',
                'purchase_date' => Carbon::parse('2023-06-01'),
                'cost' => 4500,
                'supplier' => 'Microsoft PH',
                'assigned_to' => null,
                'notes' => 'Office suite subscription',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample assets as needed
        ];

        DB::table('assets')->insert($assets);
    }
}
