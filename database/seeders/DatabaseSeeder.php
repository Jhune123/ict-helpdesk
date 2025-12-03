<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔹 Admin User
        User::firstOrCreate(
            ['email' => 'admin@ksu.edu.ph'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 🔹 IT Staff Accounts
        $itStaff = [
            ['name' => 'Bryan',  'email' => 'bryan@ksu.edu.ph'],
            ['name' => 'Jhune',  'email' => 'jhune@ksu.edu.ph'],
            ['name' => 'Reymar', 'email' => 'reymar@ksu.edu.ph'],
            ['name' => 'Walid',  'email' => 'walid@ksu.edu.ph'],
        ];

        foreach ($itStaff as $staff) {
            User::firstOrCreate(
                ['email' => $staff['email']],
                [
                    'name' => $staff['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'it_staff',
                ]
            );
        }

        // 🔹 Normal Client
        User::firstOrCreate(
            ['email' => 'client@ksu.edu.ph'],
            [
                'name' => 'Test Client',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );

        // 🔹 Default Categories
        $categories = [
            ['name' => 'Hardware', 'description' => 'Desktop, Laptop, Printers, Peripherals'],
            ['name' => 'Software', 'description' => 'Applications, OS, Licensing, Updates'],
            ['name' => 'Network', 'description' => 'Internet, LAN, Wi-Fi, Router, Switches'],
            ['name' => 'Email & Accounts', 'description' => 'Password reset, account creation, access issues'],
            ['name' => 'Website & Online Services', 'description' => 'KSU portal, LMS, website issues'],
            ['name' => 'Printing & Scanning', 'description' => 'Printers, photocopiers, scanners'],
            ['name' => 'Multimedia Equipment', 'description' => 'Projectors, smart TVs, AV equipment'],
            ['name' => 'Server & Storage', 'description' => 'File server, backups, databases'],
            ['name' => 'Security', 'description' => 'Antivirus, malware, unauthorized access'],
            ['name' => 'Others', 'description' => 'General ICT-related concerns not listed above'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description']]
            );
        }

        // 🔹 University Departments
        $departments = [
            ['name' => 'Office of the President'],
            ['name' => 'Office of the Vice President for Academic Affairs'],
            ['name' => 'Office of the Vice President for Administration and Finance'],
            ['name' => 'Office of the Vice President for Research, Extension and Development'],
            ['name' => 'Registrar'],
            ['name' => 'Finance/Accounting Office'],
            ['name' => 'Human Resource Management Office (HRMO)'],
            ['name' => 'Supply/Procurement Office'],
            ['name' => 'Library'],
            ['name' => 'ICT Office'],
            ['name' => 'Research and Extension Office'],
            ['name' => 'Guidance and Counseling'],
            ['name' => 'Medical and Dental Clinic'],
            ['name' => 'Quality Assurance Office'],
            ['name' => 'Graduate School'],
            ['name' => 'College of Engineering'],
            ['name' => 'College of Information Technology'],
            ['name' => 'College of Business and Management'],
            ['name' => 'College of Education'],
            ['name' => 'College of Nursing'],
            ['name' => 'College of Arts and Sciences'],
            ['name' => 'College of Agriculture'],
            ['name' => 'Laboratory High School'],
            ['name' => 'Security Office'],
            ['name' => 'General Services Office'],
            ['name' => 'Records Office'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']]);
        }

        // 🔹 Sample Assets for ICTO Assets & Equipment Module
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
        ];

        DB::table('assets')->insert($assets);
    }
}
