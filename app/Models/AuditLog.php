<?php

// app/Models/AuditLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $connection = 'tenant';
    protected $guarded = [];
    
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
    
    // Who performed the action?
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Polymorphic: Log can belong to any model
    public function auditable()
    {
        return $this->morphTo();
    }

    // app/Models/AuditLog.php
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['event'] ?? false, fn($q, $event) => 
            $q->where('event', $event)
        );
        
        $query->when($filters['user_id'] ?? false, fn($q, $userId) => 
            $q->where('user_id', $userId)
        );
        
        $query->when($filters['model'] ?? false, fn($q, $model) => 
            $q->where('auditable_type', $model)
        );
    }
}