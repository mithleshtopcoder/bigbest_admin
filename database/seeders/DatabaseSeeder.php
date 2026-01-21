<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed permission categories first
        $this->call([
            PermissionCategorySeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            SuperAdminSeeder::class,
        ]);

        // Seed e-commerce data
        $this->call([
            AppSettingSeeder::class, // App settings (logo, company info, etc.)
            PolicySeeder::class, // Policies (terms, privacy, etc.)
            CategorySeeder::class,
            BrandSeeder::class,
            StoreSeeder::class, // Must be before ProductSeeder
            DepartmentSeeder::class, // Must be before DesignationSeeder
            DesignationSeeder::class, // Requires departments
            // ProductSeeder::class, // Requires stores for stock creation
            CouponSeeder::class, // Requires categories and brands
            OfferSeeder::class, // Requires categories, brands, and products
            ComboOfferSeeder::class, // Requires products and variants
            BannerSeeder::class,
            SocialMediaSeeder::class,
            FaqSeeder::class,
            OptionMasterSeeder::class,
        ]);

        // Seed customer data
        $this->call([
            CustomerSeeder::class, // Must be before NotificationSeeder
            NotificationSeeder::class, // Requires customers to exist
        ]);
    }
}