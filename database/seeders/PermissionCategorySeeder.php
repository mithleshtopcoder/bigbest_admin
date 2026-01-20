<?php

namespace Database\Seeders;

use App\Models\PermissionCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Dashboard',
            'POS Orders',
            'Online Orders',
            'Order status accepted',
            'Order status preparing',
            'Order status ready to ship',
            'Order status shipped',
            'Order status out for delivery',
            'Order status completed',
            'Order status cancelled',
            'Product Master',
            'Product Category',
            'Product Sub Category',
            'Brand',
            'Coupon',
            'Discount',
            'Store Wise Inventory',
            'Stock In Stock Out',
            'Stock Adjustment',
            'Transfer Request',
            'Transfer Approval',
            'Transfer In Transit Stock',
            'Customer Master',
            'Customer Address',
            'Customer Feedback',
            'Supplier Master',
            'Vendor Master',
            'Purchase Order',
            'Purchase Invoice',
            'GRN Master',
            'Purchase Return',
            'Manage Store',
            'Service Radius',
            'Employee Master',
            'Salary Structure',
            'Payroll Processing',
            'Employee Attendance',
            'Employee Leave',
            'Employee Holiday',
            'Employee Shift',
            'Manage Shift',
            'Assign Shift',
            'Candidate Management',
            'Interview Scheduling',
            'Onboarding Process',
            'Document Collection',
            'Training Assignments',
            'Training Modules',
            'Job Openings',
            'Joining Checklist',
            'Checklist Templates',
            'Store Ledger',
            'Customer Ledger',
            'Supplier Ledger',
            'Expenses',
            'Payments Receipts',
            'Sales Reports',
            'POS Reports',
            'App Orders Reports',
            'Inventory Reports',
            'Expense Reports',
            'Employee Sales Reports',
            'About Us',
            'Contact Us',
            'Manage Policies',
            'Banners',
            'Social Media',
            'FAQs',
            'Roles & Permissions',
            'Manage User',
            'Manage Role',
            'Option Master',
            'Company Setup',
            'Tax Settings',
            'User Logs',
            'Login History',
            'Failed Login History',
            'Support Tickets',
        ];

        foreach ($categories as $index => $name) {
            PermissionCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $name . ' permission category',
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
