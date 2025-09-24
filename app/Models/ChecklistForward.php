<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistForward extends Model
{
    use HasFactory;

    protected $fillable = [
        'explosive_checklist_id',
        'from_user_id',
        'to_user_id',
        'forwarded_at',
        'message',
        'purpose',
        'comments',
        'is_signed',
    ];

    public function checklist()
    {
        return $this->belongsTo(ExplosiveChecklist::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}