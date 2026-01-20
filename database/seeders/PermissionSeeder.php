<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $actions = [
            'list'   => 'View list of records',
            'view'   => 'View record details',
            'create' => 'Create new record',
            'edit'   => 'Edit existing record',
            'status' => 'Change record status',
            'delete' => 'Delete record',
        ];

        $categories = PermissionCategory::where('is_active', true)->get();

        foreach ($categories as $category) {

            // product-category
            $resource = Str::slug($category->name);

            foreach ($actions as $action => $description) {

                // ✅ THIS IS THE PERMISSION STRING USED EVERYWHERE
                // Example: product-category.list
                $permissionName = "{$resource}.{$action}";

                Permission::updateOrCreate(
                    [
                        'name' => $permissionName, // ✅ IMPORTANT
                        'guard_name' => 'web',
                    ],
                    [
                        'permission_category_id' => $category->id,
                        'slug' => Str::slug($permissionName),
                    ]
                );
            }
        }
        
        // Clear Spatie permission cache after seeding
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
