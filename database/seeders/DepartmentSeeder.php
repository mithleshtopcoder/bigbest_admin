<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Sales',
                'code' => 'DEPT001',
                'description' => 'Handles all sales activities, customer acquisition, and revenue generation',
                'status' => true,
            ],
            [
                'name' => 'Operations',
                'code' => 'DEPT002',
                'description' => 'Manages day-to-day store operations, logistics, and supply chain',
                'status' => true,
            ],
            [
                'name' => 'Inventory Management',
                'code' => 'DEPT003',
                'description' => 'Oversees stock management, procurement, and inventory control',
                'status' => true,
            ],
            [
                'name' => 'Customer Service',
                'code' => 'DEPT004',
                'description' => 'Handles customer inquiries, complaints, and support services',
                'status' => true,
            ],
            [
                'name' => 'Administration',
                'code' => 'DEPT005',
                'description' => 'Manages administrative tasks, documentation, and office management',
                'status' => true,
            ],
            [
                'name' => 'Finance & Accounts',
                'code' => 'DEPT006',
                'description' => 'Handles financial transactions, accounting, and budgeting',
                'status' => true,
            ],
            [
                'name' => 'Human Resources',
                'code' => 'DEPT007',
                'description' => 'Manages recruitment, employee relations, and HR policies',
                'status' => true,
            ],
            [
                'name' => 'Marketing',
                'code' => 'DEPT008',
                'description' => 'Handles marketing campaigns, promotions, and brand management',
                'status' => true,
            ],
            [
                'name' => 'IT & Technology',
                'code' => 'DEPT009',
                'description' => 'Manages IT infrastructure, software systems, and technical support',
                'status' => true,
            ],
            [
                'name' => 'Quality Assurance',
                'code' => 'DEPT010',
                'description' => 'Ensures product quality, compliance, and quality standards',
                'status' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
