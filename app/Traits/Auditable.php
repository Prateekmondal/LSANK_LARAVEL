<?php

// app/Traits/Auditable.php
namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::logChange($model, 'created');
        });

        static::updated(function (Model $model) {
            self::logChange($model, 'updated');
        });

        static::deleted(function (Model $model) {
            self::logChange($model, 'deleted');
        });
    }

    protected static function logChange(Model $model, string $event)
    {
        $changes = [];
        $oldValues = null;
        $newValues = null;

        if ($event === 'updated') {
            // For updates, compute only the changed fields
            foreach ($model->getAttributes() as $key => $newValue) {
                $oldValue = $model->getOriginal($key);
                if ($oldValue !== $newValue) {
                    $changes[$key] = ['old' => $oldValue, 'new' => $newValue];

                    $oldValues[$key] = $oldValue;
                    $newValues[$key] = $newValue;
                }
            }
            // Store changes in new_values; old_values can be null or omitted
            // $newValues = $changes;
        } elseif ($event === 'created') {
            // For creations, store all new values (or nothing if you prefer minimal logging)
            $newValues = array_diff_key($model->toArray(), array_flip(['password', 'remember_token']));
        } elseif ($event === 'deleted') {
            // For deletions, store the old values
            $oldValues = array_diff_key($model->getOriginal(), array_flip(['password', 'remember_token']));
        }

        // Skip logging if no changes (e.g., for updates with no actual changes)
        if (empty($changes) && $event === 'updated') {
            return;
        }


        AuditLog::create([
            'event'        => $event,
            'auditable_type' => get_class($model),
            'auditable_id'  => $model->id,
            'old_values'   => $oldValues,
            'new_values'   => $newValues,
            'url'         => request()?->fullUrl(),
            'ip_address'  => request()?->ip(),
            'user_agent'  => request()?->userAgent(),
            'user_id'     => Auth::id(),
        ]);
    }
}
