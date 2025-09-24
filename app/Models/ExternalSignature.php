<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'explosive_checklist_id',
        'name',
        'designation',
        'cpf_no',
        'email',
        'signed_at'
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function checklist()
    {
        return $this->belongsTo(ExplosiveChecklist::class);
    }
}