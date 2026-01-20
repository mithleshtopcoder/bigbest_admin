<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get departments
        $salesDept = Department::where('code', 'DEPT001')->first();
        $operationsDept = Department::where('code', 'DEPT002')->first();
        $inventoryDept = Department::where('code', 'DEPT003')->first();
        $customerServiceDept = Department::where('code', 'DEPT004')->first();
        $adminDept = Department::where('code', 'DEPT005')->first();
        $financeDept = Department::where('code', 'DEPT006')->first();
        $hrDept = Department::where('code', 'DEPT007')->first();
        $marketingDept = Department::where('code', 'DEPT008')->first();
        $itDept = Department::where('code', 'DEPT009')->first();
        $qaDept = Department::where('code', 'DEPT010')->first();

        $designations = [
            // Sales Department
            [
                'name' => 'Sales Manager',
                'code' => 'DESG001',
                'description' => 'Manages sales team and sales operations',
                'department_id' => $salesDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Sales Executive',
                'code' => 'DESG002',
                'description' => 'Handles direct sales and customer interactions',
                'department_id' => $salesDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Sales Associate',
                'code' => 'DESG003',
                'description' => 'Assists customers and supports sales activities',
                'department_id' => $salesDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'POS Operator',
                'code' => 'DESG004',
                'description' => 'Operates point of sale system and processes transactions',
                'department_id' => $salesDept->id ?? null,
                'status' => true,
            ],

            // Operations Department
            [
                'name' => 'Operations Manager',
                'code' => 'DESG005',
                'description' => 'Oversees store operations and logistics',
                'department_id' => $operationsDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Store Manager',
                'code' => 'DESG006',
                'description' => 'Manages store operations and staff',
                'department_id' => $operationsDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Assistant Store Manager',
                'code' => 'DESG007',
                'description' => 'Assists store manager in daily operations',
                'department_id' => $operationsDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Operations Executive',
                'code' => 'DESG008',
                'description' => 'Handles operational tasks and coordination',
                'department_id' => $operationsDept->id ?? null,
                'status' => true,
            ],

            // Inventory Management Department
            [
                'name' => 'Inventory Manager',
                'code' => 'DESG009',
                'description' => 'Manages inventory and stock control',
                'department_id' => $inventoryDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Stock Keeper',
                'code' => 'DESG010',
                'description' => 'Maintains stock records and warehouse management',
                'department_id' => $inventoryDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Procurement Executive',
                'code' => 'DESG011',
                'description' => 'Handles purchasing and vendor management',
                'department_id' => $inventoryDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Warehouse Supervisor',
                'code' => 'DESG012',
                'description' => 'Supervises warehouse operations and staff',
                'department_id' => $inventoryDept->id ?? null,
                'status' => true,
            ],

            // Customer Service Department
            [
                'name' => 'Customer Service Manager',
                'code' => 'DESG013',
                'description' => 'Manages customer service team and operations',
                'department_id' => $customerServiceDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Customer Service Executive',
                'code' => 'DESG014',
                'description' => 'Handles customer inquiries and support',
                'department_id' => $customerServiceDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Customer Support Associate',
                'code' => 'DESG015',
                'description' => 'Provides customer support and assistance',
                'department_id' => $customerServiceDept->id ?? null,
                'status' => true,
            ],

            // Administration Department
            [
                'name' => 'Administration Manager',
                'code' => 'DESG016',
                'description' => 'Manages administrative operations',
                'department_id' => $adminDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Administrative Assistant',
                'code' => 'DESG017',
                'description' => 'Provides administrative support',
                'department_id' => $adminDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Office Coordinator',
                'code' => 'DESG018',
                'description' => 'Coordinates office activities and facilities',
                'department_id' => $adminDept->id ?? null,
                'status' => true,
            ],

            // Finance & Accounts Department
            [
                'name' => 'Finance Manager',
                'code' => 'DESG019',
                'description' => 'Manages financial operations and planning',
                'department_id' => $financeDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Accountant',
                'code' => 'DESG020',
                'description' => 'Handles accounting and bookkeeping',
                'department_id' => $financeDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Accounts Executive',
                'code' => 'DESG021',
                'description' => 'Manages accounts payable and receivable',
                'department_id' => $financeDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Cashier',
                'code' => 'DESG022',
                'description' => 'Handles cash transactions and payments',
                'department_id' => $financeDept->id ?? null,
                'status' => true,
            ],

            // Human Resources Department
            [
                'name' => 'HR Manager',
                'code' => 'DESG023',
                'description' => 'Manages human resources and employee relations',
                'department_id' => $hrDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'HR Executive',
                'code' => 'DESG024',
                'description' => 'Handles recruitment and HR operations',
                'department_id' => $hrDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'HR Coordinator',
                'code' => 'DESG025',
                'description' => 'Coordinates HR activities and employee services',
                'department_id' => $hrDept->id ?? null,
                'status' => true,
            ],

            // Marketing Department
            [
                'name' => 'Marketing Manager',
                'code' => 'DESG026',
                'description' => 'Manages marketing strategies and campaigns',
                'department_id' => $marketingDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Marketing Executive',
                'code' => 'DESG027',
                'description' => 'Executes marketing campaigns and promotions',
                'department_id' => $marketingDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Digital Marketing Specialist',
                'code' => 'DESG028',
                'description' => 'Handles digital marketing and social media',
                'department_id' => $marketingDept->id ?? null,
                'status' => true,
            ],

            // IT & Technology Department
            [
                'name' => 'IT Manager',
                'code' => 'DESG029',
                'description' => 'Manages IT infrastructure and systems',
                'department_id' => $itDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'System Administrator',
                'code' => 'DESG030',
                'description' => 'Manages system administration and maintenance',
                'department_id' => $itDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'IT Support Executive',
                'code' => 'DESG031',
                'description' => 'Provides IT support and troubleshooting',
                'department_id' => $itDept->id ?? null,
                'status' => true,
            ],

            // Quality Assurance Department
            [
                'name' => 'Quality Assurance Manager',
                'code' => 'DESG032',
                'description' => 'Manages quality control and assurance',
                'department_id' => $qaDept->id ?? null,
                'status' => true,
            ],
            [
                'name' => 'Quality Inspector',
                'code' => 'DESG033',
                'description' => 'Inspects products for quality standards',
                'department_id' => $qaDept->id ?? null,
                'status' => true,
            ],
        ];

        foreach ($designations as $designation) {
            Designation::create($designation);
        }
    }
}
