<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class logsRecorded extends Model
{
    use Auditable;
    //
    protected $table = 'logsRecorded';
    public $timestamps = false;

    protected $fillable = [
        'jcr_id',
        'runNo',
        'logRecorded',
        'otherLogDescription',
        'bottomDepth',
        'topDepth',
        'toolNo',
        'logQuality',
        'bottomShotDepth',
        'topShotDepth',
        'charge',
        'chargeNo',
        'primaChord',
        'primaChordQty',
        'fuse',
        'fuseNo',
        'fMf',
    ];
    public function jcr()
    {
        return $this->belongsTo(Jcr::class);
    }
}
