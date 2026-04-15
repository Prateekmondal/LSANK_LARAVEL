<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

use App\Traits\Auditable;

class Notification extends DatabaseNotification
{
    use HasFactory, Auditable;

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Relationship to the notifiable entity (usually User)
    public function notifiable()
    {
        return $this->morphTo();
    }
}