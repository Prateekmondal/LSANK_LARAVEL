<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class logsRecorded extends Model
{
    //

    protected $table = 'logsRecorded';
    public $timestamps = false;

    protected $fillable = [
        'jcr_id',
        'runNo',
        'logRecorded',
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
