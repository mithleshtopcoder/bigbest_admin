<?php

namespace App\Providers;

use App\Listeners\LogFailedLogin;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogUserLogout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register MyHelper class alias
        $this->app->alias(\App\Helpers\MyHelper::class, 'MyHelper');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Register event listeners for authentication events
        Event::listen(Login::class, LogSuccessfulLogin::class);
        Event::listen(Failed::class, LogFailedLogin::class);
        Event::listen(Logout::class, LogUserLogout::class);

        // Register Gates for permissions
        // This allows @can and @canany directives to work with permission slugs
        Gate::before(function ($user, $ability) {
            // Super Admin has access to everything
            $superAdminQuery = $user->roles()->where('name', 'Super Admin');
            
            // Only filter by is_active if the column exists
            if (\Schema::hasColumn('roles', 'is_active')) {
                $superAdminQuery->where('is_active', true);
            }
            
            if ($superAdminQuery->exists()) {
                return true;
            }
            
            // Check if user has the permission
            return $user->hasPermission($ability) ? true : null;
        });
    }
}