<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Concerns\HasAvatar;
use Illuminate\Support\Facades\Gate;

use App\Traits\Auditable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Auditable;
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
            return $this->hasAnyRole(['super-admin', 'Head_Logging_Services', 'Location Manager']); // Replace with your role/permission check
        }

        return true; // Default: allow access to other panels if not admin
    }

    public function jcrs()
    {
        return $this->belongsToMany(Jcr::class, 'jcruser');
    }

    // Add this method to the User model
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }

    /**
     * Determine if the user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        // Example: Assuming you have a 'role' column in your users table
        // and 'super_admin' is a specific role value.
        return $this->role === 'super-admin';

        // Or, if you have a dedicated 'is_super_admin' boolean column
        // return (bool) $this->is_super_admin;

        // Or, if using a package like Spatie Laravel-Permission
        // return $this->hasRole('super_admin');
    }

        // Relationship with TimeRegisters as logging chief
    public function timeRegistersAsChief()
    {
        return $this->hasMany(TimeRegister::class, 'logging_chief_id');
    }

    // Relationship with TimeRegisters as creator
    public function createdTimeRegisters()
    {
        return $this->hasMany(TimeRegister::class, 'created_by');
    }
    // Relationship with TimeRegisters
    public function timeRegisters()
    {
        return $this->hasMany(TimeRegister::class, 'created_by');
    }
}
