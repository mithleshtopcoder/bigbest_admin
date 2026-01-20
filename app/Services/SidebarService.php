<?php

namespace App\Services;

use App\Models\PermissionCategory;
use Illuminate\Support\Str;

class SidebarService
{
    public static function build()
    {
        return PermissionCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($category) {

                return [
                    'label'      => $category->name,
                    'icon'       => 'bi bi-folder', // default
                    'route'      => null,           // parent menu
                    'permission' => null,
                    'children'   => $category->permissions
                        ->where('is_active', true)
                        ->map(function ($permission) {
                            return [
                                'label'      => Str::title(str_replace('.', ' ', $permission->slug)),
                                'route'      => $permission->slug,
                                'permission' => $permission->slug,
                            ];
                        })
                        ->values()
                        ->toArray(),
                ];
            })
            ->values()
            ->toArray();
    }
}