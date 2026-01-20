<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN – FULL ACCESS
        |--------------------------------------------------------------------------
        */
        $superAdmin = Role::updateOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'web'],
            [
                'slug' => Str::slug('Super Admin'),
                'description' => 'Super Admin role with full access',
                'is_active' => true,
            ]
        );

        // Give ALL permissions (by NAME)
        $superAdmin->syncPermissions(
            Permission::pluck('name')->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | MANAGER – LIMITED ACCESS
        |--------------------------------------------------------------------------
        */
        $manager = Role::updateOrCreate(
            ['name' => 'Manager', 'guard_name' => 'web'],
            [
                'slug' => Str::slug('Manager'),
                'description' => 'Manager role with limited access',
                'is_active' => true,
            ]
        );

        $manager->syncPermissions([
            // Dashboard
            'dashboard.view',

            // Orders
            'pos-orders.list',
            'online-orders.list',

            // Products
            'product-master.list',
            'product-master.view',
            'product-master.create',
            'product-master.edit',

            // Inventory
            'store-wise-inventory.list',
            'stock-in-stock-out.list',
            'stock-adjustment.list',

            // Customers
            'customer-master.list',
            'customer-master.view',

            // Reports
            'sales-reports.list',
            'inventory-reports.list',
        ]);
        
        // Clear Spatie permission cache after seeding
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
