<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class logType extends Model
{
    public $timestamps = false;
    protected $table = 'logTypes';
    protected $fillable = ['logType',];

    public function loggingUnits()
        {
            return $this->belongsToMany(loggingUnit::class, 'loggingUnitType', 'logType_id','loggingUnit_id');
        }
}
