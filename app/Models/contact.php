<?php

namespace App\Models;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mail;
use App\Mail\ContactMail;
use Illuminate\Database\Eloquent\Model;

class contact extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = ['name', 'email', 'phone', 'message'];

    /**
     * Write code on Method
     *
     * @return response()
     */
    public static function boot(): void
    {
        parent::boot();
        static::created(function ($data) {
            $adminEmail = "prateekmondal@gmail.com";
            Mail::to($adminEmail)->send(new ContactMail($data));
        });
    }
}

