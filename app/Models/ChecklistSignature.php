<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class ChecklistSignature extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'explosive_checklist_id',
        'user_id',
        'signature_type',
        'signed_at',
        'comments',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function checklist()
    {
        return $this->belongsTo(ExplosiveChecklist::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}