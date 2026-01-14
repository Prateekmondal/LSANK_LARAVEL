<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class loggingUnit extends Model
{
    use Auditable;
    public $timestamps = false;
    protected $table = 'loggingUnits';
    protected $fillable = ['loggingUnit',];

    public function logTypes()
        {
            return $this->belongsToMany(logType::class, 'loggingUnitType', 'loggingUnit_id', 'logType_id');
        }
}
