<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeBasicOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $optionMasters = [

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
        ];

        foreach ($optionMasters as $masterName => $options) {

            // Insert / Update Option Master
            DB::table('option_masters')->updateOrInsert(
                ['name' => $masterName],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            $optionMasterId = DB::table('option_masters')
                ->where('name', $masterName)
                ->value('id');

            // Insert / Update Options
            foreach ($options as $option) {
                DB::table('options')->updateOrInsert(
                    [
                        'option_master_id' => $optionMasterId,
                        'value'            => $option['value'],
                    ],
                    [
                        'name'             => $option['name'],
                        'value'            => $option['value'],
                        'option_master_id' => $optionMasterId,
                        'default'          => $option['default'] ?? false,
                        'status'           => 1,
                        'display_image'    => null,
                        'created_at'       => $now,
                        'updated_at'       => $now,
                    ]
                );
            }
        }
    }
}