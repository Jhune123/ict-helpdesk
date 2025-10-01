<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Department;

class CategoryAndDepartmentSeeder extends Seeder
{
    public function run()
    {
        // Categories
        $categories = [
            'Desktop / Laptop',
            'Printer / Scanner',
            'Network Equipment',
            'Projector / Multimedia',
            'Operating System',
            'Office Suite / Productivity Apps',
            'Educational Software',
            'Antivirus / Security Software',
            'Internet Issue',
            'Wi-Fi Access',
            'VPN / Remote Access',
            'Server / Hosting',
            'Account / Login Issue',
            'Data Backup / Recovery',
            'Email / Communication Tools',
            'Maintenance / Upgrades',
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }

        // Departments
        $departments = [
            'Administration',
            'Academic Affairs',
            'Finance / Accounting',
            'Human Resources',
            'Library / Learning Resource Center',
            'Student Affairs / Guidance',
            'Research & Development',
            'ICT / IT Services',
            'Facilities / Maintenance',
            'Registrar',
            'Extension / Outreach Programs',
            'Admissions & Records',
            'College of Engineering',
            'College of Science',
            'College of Arts & Social Sciences',
            'College of Education',
            'College of Agriculture',
            'College of Business / Economics',
            'Laboratory / Labs',
            'Campus Security / Safety Office',
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept]);
        }
    }
}
