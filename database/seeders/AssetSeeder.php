<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                'entity_name' => 'Kalinga State University',
                'fund_cluster' => '101',
                'par_no' => 'PAR-001',
                'quantity' => 10,
                'unit' => 'pcs',
                'description' => 'Desktop computers for IT Lab',
                'property_no' => 'PROP-001',
                'date_acquired' => Carbon::parse('2022-01-15'),
                'purchase_date' => Carbon::parse('2022-01-10'),
                'amount' => 500000,
                'purpose' => 'Academic purposes',
                'approved_for_issuance' => 'Yes',
                'received_from' => 'Supplier ABC',
                'received_by' => 'IT Office',
                'date_counted' => Carbon::parse('2022-01-20'),
                'status' => 'Available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'entity_name' => 'Kalinga State University',
                'fund_cluster' => '102',
                'par_no' => 'PAR-002',
                'quantity' => 5,
                'unit' => 'pcs',
                'description' => 'Printers for Admin Office',
                'property_no' => 'PROP-002',
                'date_acquired' => Carbon::parse('2023-03-12'),
                'purchase_date' => Carbon::parse('2023-03-10'),
                'amount' => 120000,
                'purpose' => 'Office administration',
                'approved_for_issuance' => 'Yes',
                'received_from' => 'Supplier XYZ',
                'received_by' => 'Admin Office',
                'date_counted' => Carbon::parse('2023-03-15'),
                'status' => 'In Use',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'entity_name' => 'Kalinga State University',
                'fund_cluster' => '103',
                'par_no' => 'PAR-003',
                'quantity' => 15,
                'unit' => 'pcs',
                'description' => 'Office chairs for faculty rooms',
                'property_no' => 'PROP-003',
                'date_acquired' => Carbon::parse('2023-06-05'),
                'purchase_date' => Carbon::parse('2023-06-01'),
                'amount' => 75000,
                'purpose' => 'Faculty comfort',
                'approved_for_issuance' => 'Yes',
                'received_from' => 'Furniture Supplier',
                'received_by' => 'Facilities Office',
                'date_counted' => Carbon::parse('2023-06-10'),
                'status' => 'Available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('assets')->insert($assets);
    }
}
