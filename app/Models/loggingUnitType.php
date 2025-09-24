<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Auditable;

class loggingUnitType extends Model
{
    use Auditable;
    public $timestamps = false;
    protected $table = 'loggingUnitType';
    protected $fillable = ['loggingUnit_id', 'logType_id',];

    public function loggingUnitType()
        {
            return $this->belongsToMany(loggingUnitType::class, 'loggingUnitType');
        }
}
