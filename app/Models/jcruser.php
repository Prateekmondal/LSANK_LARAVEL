<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jcruser extends Model
{
    //
    protected $table = 'jcruser';
    public $timestamps = false;

    protected $fillable = ['jcr_id', 'user_id'];
}
