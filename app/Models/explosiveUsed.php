<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class explosiveUsed extends Model
{
    use Auditable;
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
