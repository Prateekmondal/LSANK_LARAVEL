<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class loggingUnit extends Model
{
    public $timestamps = false;
    protected $table = 'loggingUnits';
    protected $fillable = ['loggingUnit',];

    public function logTypes()
        {
            return $this->belongsToMany(logType::class, 'loggingUnitType', 'logType_id', 'loggingUnit_id');
        }
}
