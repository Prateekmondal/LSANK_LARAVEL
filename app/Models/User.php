<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Concerns\HasAvatar;
use Illuminate\Support\Facades\Gate;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles; 
    // use HasAvatar;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'seniority',
        'cpf',
        'name',
        'designation',
        'email',
        'phone',
        'description',
        'avatar',
        'status',
        'email_verified_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->assignRole('Field_Officer'); // Assign default role on user creation
        });
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            set: function (?string $value) {
                if (!empty($this->avatar_url) && (is_null($value) || $value !== $this->avatar_url)) {
                    Storage::disk('public')->delete($this->avatar_url);
                }

                return $value;
            },
        );
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole('super-admin'); // Replace with your role/permission check
        }

        return true; // Default: allow access to other panels if not admin
    }

    public function jcrs()
    {
        return $this->belongsToMany(Jcr::class, 'jcruser');
    }
}
