<?php

namespace App\Traits;

use App\Services\ActivityLogService;

trait LogsActivity
{
    /**
     * Log activity after model is created
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            ActivityLogService::logCreate(
                static::getModuleName(),
                $model
            );
        });

        static::updated(function ($model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();
            
            // Remove timestamps from comparison
            unset($oldValues['updated_at'], $newValues['updated_at']);
            
            if (!empty($newValues)) {
                ActivityLogService::logUpdate(
                    static::getModuleName(),
                    $model,
                    $oldValues,
                    $newValues
                );
            }
        });

        static::deleted(function ($model) {
            ActivityLogService::logDelete(
                static::getModuleName(),
                $model
            );
        });
    }

    /**
     * Get the module name for logging
     * Override this method in your model if needed
     */
    protected static function getModuleName(): string
    {
        $className = class_basename(static::class);
        return str_replace('_', ' ', $className);
    }
}
