<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class logType extends Model
{
    use Auditable;
    public $timestamps = false;
    protected $table = 'logTypes';
    protected $fillable = ['logType',];

    public function loggingUnits()
        {
            return $this->belongsToMany(loggingUnit::class, 'loggingUnitType', 'logType_id','loggingUnit_id');
        }
}
