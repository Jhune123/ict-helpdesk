<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Office of the President',
            'Office of the Vice President for Academic Affairs',
            'Office of the Vice President for Administration and Finance',
            'Office of the Vice President for Research and Extension',
            'Registrar’s Office',
            'Human Resource Management Office',
            'Finance Office',
            'Accounting Office',
            'Cashier’s Office',
            'Library',
            'ICT Office',
            'Quality Assurance Office',
            'Student Affairs and Services Office',
            'Guidance and Counseling Office',
            'Medical and Dental Clinic',
            'Extension Services',
            'Research and Development Office',
            'Graduate School',
            'College of Teacher Education',
            'College of Engineering',
            'College of Information Technology',
            'College of Business and Accountancy',
            'College of Agriculture',
            'College of Arts and Sciences',
            'College of Nursing',
            'College of Law',
            'College of Criminal Justice Education',
            'Campus Director’s Office',
            'Other' // ✅ Fallback for unlisted departments
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->insert([
                'name' => $dept,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
