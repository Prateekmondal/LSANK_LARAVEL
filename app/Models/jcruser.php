<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class jcruser extends Model
{
    use Auditable;
    //
    protected $table = 'jcruser';
    public $timestamps = false;

    protected $fillable = ['jcr_id', 'user_id'];

    public function jcr()
    {
        return $this->belongsTo(Jcr::class, 'jcr_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
