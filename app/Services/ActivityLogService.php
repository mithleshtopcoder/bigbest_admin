<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log user activity
     */
    public static function log(string $action, string $module, ?string $details = null, $model = null, array $oldValues = [], array $newValues = []): void
    {
        $request = request();
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'details' => $details,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'old_values' => !empty($oldValues) ? $oldValues : null,
            'new_values' => !empty($newValues) ? $newValues : null,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
        ]);
    }

    /**
     * Log create action
     */
    public static function logCreate(string $module, $model, ?string $details = null): void
    {
        self::log('created', $module, $details ?? "Created new {$module} record", $model);
    }

    /**
     * Log update action
     */
    public static function logUpdate(string $module, $model, array $oldValues, array $newValues, ?string $details = null): void
    {
        self::log('updated', $module, $details ?? "Updated {$module} record", $model, $oldValues, $newValues);
    }

    /**
     * Log delete action
     */
    public static function logDelete(string $module, $model, ?string $details = null): void
    {
        self::log('deleted', $module, $details ?? "Deleted {$module} record", $model);
    }

    /**
     * Log view action
     */
    public static function logView(string $module, ?string $details = null): void
    {
        self::log('viewed', $module, $details ?? "Viewed {$module}");
    }
}
