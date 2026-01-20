<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OptionMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Define option masters with their corresponding options
        $optionMasters = [
            /* ================= COLLECTIONS ================= */
            'Collections' => [
                ['name' => 'Summer Collection', 'value' => 'summer', 'default' => false],
                ['name' => 'Winter Collection', 'value' => 'winter', 'default' => false],
                ['name' => 'Monsoon Collection', 'value' => 'monsoon', 'default' => false],
                ['name' => 'All Season', 'value' => 'all_season', 'default' => true],
            ],
            /* ================= SEASONS ================= */
            'Seasons' => [
                ['name' => 'Spring', 'value' => 'spring', 'default' => false],
                ['name' => 'Summer', 'value' => 'summer', 'default' => false],
                ['name' => 'Monsoon', 'value' => 'monsoon', 'default' => false],
                ['name' => 'Autumn', 'value' => 'autumn', 'default' => false],
                ['name' => 'Winter', 'value' => 'winter', 'default' => false],
            ],
            /* ================= ITEM TYPE ================= */
            'Item type' => [
                ['name' => 'Fresh', 'value' => 'fresh', 'default' => true],
                ['name' => 'Frozen', 'value' => 'frozen', 'default' => false],
                ['name' => 'Dried', 'value' => 'dried', 'default' => false],
                ['name' => 'Canned', 'value' => 'canned', 'default' => false],
                ['name' => 'Organic', 'value' => 'organic', 'default' => false],
                ['name' => 'Local', 'value' => 'local', 'default' => false],
                ['name' => 'Imported', 'value' => 'imported', 'default' => false],
            ],
            /* ================= DEGREE ================= */
            'Degree' => [
                ['name' => 'Bachelor of Science', 'value' => 'bachelor_of_science', 'default' => true],
                ['name' => 'Bachelor of Arts', 'value' => 'bachelor_of_arts', 'default' => false],
                ['name' => 'Master of Science', 'value' => 'master_of_science', 'default' => false],
                ['name' => 'Master of Arts', 'value' => 'master_of_arts', 'default' => false],
                ['name' => 'Doctor of Philosophy', 'value' => 'doctor_of_philosophy', 'default' => false],
                ['name' => 'Doctor of Engineering', 'value' => 'doctor_of_engineering', 'default' => false],
                ['name' => 'Doctor of Medicine', 'value' => 'doctor_of_medicine', 'default' => false],
                ['name' => 'Doctor of Law', 'value' => 'doctor_of_law', 'default' => false],
                ['name' => 'Doctor of Business Administration', 'value' => 'doctor_of_business_administration', 'default' => false],
                ['name' => 'Doctor of Education', 'value' => 'doctor_of_education', 'default' => false],
                ['name' => 'Doctor of Nursing', 'value' => 'doctor_of_nursing', 'default' => false],
                ['name' => 'Doctor of Pharmacy', 'value' => 'doctor_of_pharmacy', 'default' => false],
                ['name' => 'Doctor of Veterinary Science', 'value' => 'doctor_of_veterinary_science', 'default' => false],
                ['name' => 'Doctor of Pharmacy', 'value' => 'doctor_of_pharmacy', 'default' => false],
                ['name' => 'Doctor of Pharmacy', 'value' => 'doctor_of_pharmacy', 'default' => false],
            ],
            /* ================= EMPLOYEE TYPE ================= */
            'Employee Type' => [
                ['name' => 'Permanent', 'value' => 'permanent', 'default' => true],
                ['name' => 'Contract',  'value' => 'contract',  'default' => false],
                ['name' => 'Intern',    'value' => 'intern',    'default' => false],
                ['name' => 'Part Time', 'value' => 'part_time', 'default' => false],
            ],

            /* ================= MARITAL STATUS ================= */
            'Marital Status' => [
                ['name' => 'Single',   'value' => 'single',   'default' => true],
                ['name' => 'Married',  'value' => 'married',  'default' => false],
                ['name' => 'Divorced', 'value' => 'divorced', 'default' => false],
                ['name' => 'Widowed',  'value' => 'widowed',  'default' => false],
            ],

            /* ================= BLOOD GROUP ================= */
            'Blood Group' => [
                ['name' => 'A+',  'value' => 'a_positive',  'default' => false],
                ['name' => 'A-',  'value' => 'a_negative',  'default' => false],
                ['name' => 'B+',  'value' => 'b_positive',  'default' => false],
                ['name' => 'B-',  'value' => 'b_negative',  'default' => false],
                ['name' => 'O+',  'value' => 'o_positive',  'default' => true],
                ['name' => 'O-',  'value' => 'o_negative',  'default' => false],
                ['name' => 'AB+', 'value' => 'ab_positive', 'default' => false],
                ['name' => 'AB-', 'value' => 'ab_negative', 'default' => false],
            ],

            /* ================= EXPENSE CATEGORY ================= */
            'Expense Category' => [
                ['name' => 'Rent', 'value' => 'rent', 'default' => false],
                ['name' => 'Utilities', 'value' => 'utilities', 'default' => false],
                ['name' => 'Salary', 'value' => 'salary', 'default' => false],
                ['name' => 'Marketing', 'value' => 'marketing', 'default' => false],
                ['name' => 'Maintenance', 'value' => 'maintenance', 'default' => false],
                ['name' => 'Transportation', 'value' => 'transportation', 'default' => false],
                ['name' => 'Office Supplies', 'value' => 'office_supplies', 'default' => false],
                ['name' => 'Insurance', 'value' => 'insurance', 'default' => false],
                ['name' => 'Taxes', 'value' => 'taxes', 'default' => false],
                ['name' => 'Professional Services', 'value' => 'professional_services', 'default' => false],
                ['name' => 'Travel', 'value' => 'travel', 'default' => false],
                ['name' => 'Food & Beverages', 'value' => 'food_beverages', 'default' => false],
                ['name' => 'Equipment', 'value' => 'equipment', 'default' => false],
                ['name' => 'Other', 'value' => 'other', 'default' => false],
            ],

            /* ================= PAYMENT METHOD ================= */
            'Payment Method' => [
                ['name' => 'Cash', 'value' => 'cash', 'default' => true],
                ['name' => 'Bank Transfer', 'value' => 'bank_transfer', 'default' => false],
                ['name' => 'Credit Card', 'value' => 'credit_card', 'default' => false],
                ['name' => 'Debit Card', 'value' => 'debit_card', 'default' => false],
                ['name' => 'Cheque', 'value' => 'cheque', 'default' => false],
                ['name' => 'UPI', 'value' => 'upi', 'default' => false],
                ['name' => 'Net Banking', 'value' => 'net_banking', 'default' => false],
                ['name' => 'Wallet', 'value' => 'wallet', 'default' => false],
                ['name' => 'Other', 'value' => 'other', 'default' => false],
            ],

            /* ================= STATUS ================= */
            'Status' => [
                ['name' => 'Paid', 'value' => 'paid', 'default' => true],
                ['name' => 'Pending', 'value' => 'pending', 'default' => false],
                ['name' => 'Approved', 'value' => 'approved', 'default' => false],
                ['name' => 'Rejected', 'value' => 'rejected', 'default' => false],
                ['name' => 'Cancelled', 'value' => 'cancelled', 'default' => false],
            ],
        ];

        // Seed option masters and their options
        foreach ($optionMasters as $masterName => $options) {
            // Insert or update option master
            DB::table('option_masters')->updateOrInsert(
                ['name' => $masterName],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            // Get the option master ID
            $optionMaster = DB::table('option_masters')->where('name', $masterName)->first();
            $optionMasterId = $optionMaster->id;

            // Insert or update options for this master
            foreach ($options as $option) {
                DB::table('options')->updateOrInsert(
                    [
                        'option_master_id' => $optionMasterId,
                        'value' => $option['value'],
                    ],
                    [
                        'name' => $option['name'],
                        'value' => $option['value'],
                        'option_master_id' => $optionMasterId,
                        'default' => $option['default'] ?? false,
                        'status' => true,
                        'display_image' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                ]
            );
            }
        }
    }
}