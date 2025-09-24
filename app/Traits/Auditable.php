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
        $original = $event === 'updated' ? $model->getOriginal() : null;
        
        $hiddenFields = ['password', 'remember_token']; // Add sensitive fields
        
        $oldValues = $original ? array_diff_key($original, array_flip($hiddenFields)) : null;
        $newValues = $event !== 'deleted' 
            ? array_diff_key($model->toArray(), array_flip($hiddenFields)) 
            : null;


        AuditLog::create([
            'event'        => $event,
            'auditable_type' => get_class($model),
            'auditable_id'  => $model->id,
            'old_values'   => $original,
            'new_values'   => $event === 'deleted' ? null : $model->toArray(),
            'url'         => request()?->fullUrl(),
            'ip_address'  => request()?->ip(),
            'user_agent'  => request()?->userAgent(),
            'user_id'     => Auth::id(),
        ]);
    }
}
