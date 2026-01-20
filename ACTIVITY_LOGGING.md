# Activity Logging System Documentation

This document explains how the automatic activity logging system works in the application.

## Overview

The application automatically logs:
1. **User Activity Logs** - All user actions (create, update, delete, view)
2. **Login History** - Successful login attempts with device and IP information
3. **Failed Login History** - Failed login attempts with reasons

## Components

### 1. Event Listeners

#### LogSuccessfulLogin
- Listens to: `Illuminate\Auth\Events\Login`
- Logs: User ID, email, IP address, device, platform, login time
- Location: `app/Listeners/LogSuccessfulLogin.php`

#### LogFailedLogin
- Listens to: `Illuminate\Auth\Events\Failed`
- Logs: Email, IP address, device, platform, reason, attempt time
- Location: `app/Listeners/LogFailedLogin.php`

#### LogUserLogout
- Listens to: `Illuminate\Auth\Events\Logout`
- Updates: Most recent login history record with logout time
- Location: `app/Listeners/LogUserLogout.php`

### 2. Middleware

#### LogUserActivity
- Automatically logs page views for authenticated users
- Excludes: login, logout, API routes, Livewire routes, Debugbar
- Location: `app/Http/Middleware/LogUserActivity.php`
- Registered in: `bootstrap/app.php`

### 3. Service

#### ActivityLogService
- Provides static methods for manual activity logging
- Methods:
  - `log()` - Generic logging method
  - `logCreate()` - Log creation actions
  - `logUpdate()` - Log update actions
  - `logDelete()` - Log deletion actions
  - `logView()` - Log view actions
- Location: `app/Services/ActivityLogService.php`

### 4. Trait

#### LogsActivity
- Can be used in Eloquent models to automatically log CRUD operations
- Automatically logs: created, updated, deleted events
- Location: `app/Traits/LogsActivity.php`

## Usage

### Automatic Logging

#### Login/Logout Events
These are automatically handled by Laravel's authentication events. No additional code needed.

#### Page Views
Page views are automatically logged by the `LogUserActivity` middleware for authenticated users.

### Manual Logging

#### Using ActivityLogService in Controllers

```php
use App\Services\ActivityLogService;

// Log a create action
ActivityLogService::logCreate('Products', $product, 'Created new product: ' . $product->name);

// Log an update action
ActivityLogService::logUpdate('Products', $product, $oldValues, $newValues, 'Updated product details');

// Log a delete action
ActivityLogService::logDelete('Products', $product, 'Deleted product: ' . $product->name);

// Log a view action
ActivityLogService::logView('Products', 'Viewed product list');

// Generic log
ActivityLogService::log('custom_action', 'Products', 'Custom action description', $product);
```

#### Using LogsActivity Trait in Models

```php
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use LogsActivity;
    
    // Optionally override to customize module name
    protected static function getModuleName(): string
    {
        return 'Products';
    }
}
```

When you use this trait, the model will automatically log:
- `created` events when a new record is created
- `updated` events when a record is updated (only logs changed fields)
- `deleted` events when a record is deleted

## Database Tables

### activity_logs
- `user_id` - User who performed the action
- `action` - Action type (created, updated, deleted, viewed)
- `module` - Module name (Products, Orders, etc.)
- `details` - Description of the action
- `ip_address` - User's IP address
- `user_agent` - Browser user agent
- `url` - Full URL of the request
- `old_values` - JSON of old values (for updates)
- `new_values` - JSON of new values (for creates/updates)
- `model_type` - Model class name
- `model_id` - Model ID

### login_history
- `user_id` - User who logged in
- `email` - Email used for login
- `ip_address` - IP address
- `user_agent` - Browser user agent
- `device` - Device type (Chrome, Firefox, iPhone, etc.)
- `platform` - Platform (Windows, macOS, Linux, Android, iOS)
- `status` - Login status (success/failed)
- `login_at` - Login timestamp
- `logout_at` - Logout timestamp (nullable)

### failed_login_history
- `email` - Email used in failed attempt
- `ip_address` - IP address
- `user_agent` - Browser user agent
- `device` - Device type
- `platform` - Platform
- `reason` - Reason for failure (Invalid Password, User Not Found, Account Locked)
- `attempted_at` - Attempt timestamp

## Viewing Logs

All logs can be viewed in the admin panel:
- **User Logs**: `/logs-audit/user-logs`
- **Login History**: `/logs-audit/login-history`
- **Failed Login History**: `/logs-audit/failed-login-history`

All pages support:
- Search functionality
- Filtering by various criteria
- Date range filtering
- DataTables with server-side processing

## Configuration

### Excluding Routes from Activity Logging

Edit `app/Http/Middleware/LogUserActivity.php` and add routes to the `$except` array:

```php
protected $except = [
    'login',
    'logout',
    'api/*',
    'livewire/*',
    '_debugbar/*',
    'your-route/*', // Add your routes here
];
```

### Customizing Module Names

Override the `getModuleName()` method in your model when using the `LogsActivity` trait:

```php
protected static function getModuleName(): string
{
    return 'Custom Module Name';
}
```

## Notes

- Activity logging only works for authenticated users
- Failed login attempts are logged even for non-existent users
- The middleware only logs GET requests (not AJAX/JSON requests)
- Page views are logged automatically, but CRUD operations should be logged manually in controllers or using the trait
- All timestamps are stored in UTC
