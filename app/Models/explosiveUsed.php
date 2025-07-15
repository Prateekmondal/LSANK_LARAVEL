<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class explosiveUsed extends Model
{
    //
    protected $table = 'explosiveUsed';
    public $timestamps = false;

    protected $fillable = [
        'jcr_id',
        'explosive',
        'issued',
        'used',
        'returned',
    ];

    public function jcr()
    {
        return $this->belongsTo(Jcr::class);
    }
}
