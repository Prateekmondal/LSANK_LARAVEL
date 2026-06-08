<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Gate;

use App\Traits\Auditable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles {
        hasRole as traitHasRole;
        hasAnyRole as traitHasAnyRole;
        hasAllRoles as traitHasAllRoles;
        hasPermissionTo as traitHasPermissionTo;
        hasAnyPermission as traitHasAnyPermission;
        scopeRole as traitScopeRole;
        scopePermission as traitScopePermission;
    }
    // use HasAvatar;

    public $timestamps = false;
    protected $connection = 'central'; // Forces central DB

    /**
     * Get the avatar URL for Filament.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar
            ? asset('storage/images/profile_image/' . $this->avatar)
            : null;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'seniority',
        'cpf',
        'name',
        'designation',
        'email',
        'phone',
        'description',
        'avatar',
        'status',
        'is_approved',
        'is_super_admin',
        'approved_at',
        'approved_by',
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
            'approved_at'       => 'datetime',
            'is_approved'       => 'boolean',
            'is_super_admin'    => 'boolean',
            'password'          => 'hashed',
        ];
    }

    /**
     * Override newRelatedInstance to prevent 'central' connection from being
     * inherited by tenant-only models (Jcr, TimeRegister, etc.).
     *
     * By default, Laravel propagates the parent model's connection to related
     * models that have none set. Since User forces $connection = 'central',
     * all tenant tables (jcr, jcruser, time_registers…) would be queried on
     * the central DB — which doesn't have those tables.
     *
     * Fix: when tenancy is active, related models without an explicit connection
     * receive the current default (tenant) connection instead of 'central'.
     * Models that already declare their own connection (e.g. User itself for
     * self-referential relationships like approver()) are left untouched.
     */
    protected function newRelatedInstance($class)
    {
        return tap(new $class, function ($instance) {
            if (! $instance->getConnectionName()) {
                $instance->setConnection(
                    tenancy()->initialized
                        ? config('database.default') // tenant DB
                        : $this->connection          // fallback: central
                );
            }
        });
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Only assign role when inside a tenant context (roles table exists in tenant DB)
            if (tenancy()->initialized) {
                try {
                    $user->assignRole('field_officer');
                } catch (\Throwable $e) {
                    // Silently ignore if roles table not available
                }
            }
        });
    }

    /**
     * Override Spatie's roles() relationship.
     *
     * The User model forces $connection = 'central', but roles/permissions
     * tables only exist in tenant databases. When tenancy is initialized
     * (i.e. a subdomain request), we clone the model with the current
     * default connection (tenant DB) so that the pivot join queries run
     * against the correct database.
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        $pivotTable  = config('permission.table_names.model_has_roles');
        $foreignKey  = config('permission.column_names.model_morph_key');
        $relatedKey  = config('permission.column_names.role_pivot_key') ?: 'role_id';
        $roleClass   = config('permission.models.role');

        if (tenancy()->initialized) {
            // Clone with tenant DB connection so pivot queries target the correct DB.
            $self = (clone $this)->setConnection(config('database.default'));
            return $self->morphToMany($roleClass, 'model', $pivotTable, $foreignKey, $relatedKey);
        }

        // Central domain: no roles table exists — return an always-empty relation
        // so hasRole() / hasAnyRole() calls return false without crashing.
        return $this->morphToMany($roleClass, 'model', $pivotTable, $foreignKey, $relatedKey)
                    ->whereRaw('1 = 0'); // Forces an empty result set
    }

    /**
     * Override Spatie's permissions() relationship for the same reason.
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        $pivotTable      = config('permission.table_names.model_has_permissions');
        $foreignKey      = config('permission.column_names.model_morph_key');
        $relatedKey      = config('permission.column_names.permission_pivot_key') ?: 'permission_id';
        $permissionClass = config('permission.models.permission');

        if (tenancy()->initialized) {
            $self = (clone $this)->setConnection(config('database.default'));
            return $self->morphToMany($permissionClass, 'model', $pivotTable, $foreignKey, $relatedKey);
        }

        // Central domain: no permissions table exists — return safe empty relation.
        return $this->morphToMany($permissionClass, 'model', $pivotTable, $foreignKey, $relatedKey)
                    ->whereRaw('1 = 0');
    }

    /**
     * Relationship with the user's assigned tenant/location.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id');
    }

    protected function avatarUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            set: function (?string $value) {
                if (!empty($this->avatar_url) && (is_null($value) || $value !== $this->avatar_url)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($this->avatar_url);
                }

                return $value;
            },
        );
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            // Super-admins (central flag) can access the admin panel from any tenant subdomain.
            if ((bool) $this->is_super_admin && $this->is_approved) {
                return true;
            }

            if (! tenancy()->initialized) {
                // On the central domain there are no roles tables.
                return false;
            }

            // On a tenant subdomain, check Spatie roles normally.
            return $this->hasAnyRole(['super-admin', 'Head_Logging_Services', 'Location Manager']);
        }

        return true;
    }

    /**
     * Intercept role checks on the central domain to avoid querying Spatie tables
     * that only exist inside tenant databases.
     */
    public function hasRole($roles, $guard = null): bool
    {
        if (! tenancy()->initialized) {
            return false;
        }
        return $this->traitHasRole($roles, $guard);
    }

    public function hasAnyRole(...$roles): bool
    {
        if (! tenancy()->initialized) {
            return false;
        }
        return $this->traitHasAnyRole(...$roles);
    }

    public function hasAllRoles(...$roles): bool
    {
        if (! tenancy()->initialized) {
            return false;
        }
        return $this->traitHasAllRoles(...$roles);
    }

    public function hasPermissionTo($permission, $guard = null): bool
    {
        if (! tenancy()->initialized) {
            return false;
        }
        return $this->traitHasPermissionTo($permission, $guard);
    }

    public function hasAnyPermission(...$permissions): bool
    {
        if (! tenancy()->initialized) {
            return false;
        }
        return $this->traitHasAnyPermission(...$permissions);
    }

    public function scopeRole(\Illuminate\Database\Eloquent\Builder $query, $roles, $guard = null, $without = false): \Illuminate\Database\Eloquent\Builder
    {
        if (tenancy()->initialized) {
            $ref = new \ReflectionProperty($query->getQuery(), 'connection');
            $ref->setAccessible(true);
            $ref->setValue($query->getQuery(), \DB::connection(config('database.default')));

            $centralDb = config('database.connections.central.database', 'lsank_laravel');
            $query->from($centralDb . '.users');
        } else {
            return $query->whereRaw('1 = 0');
        }
        return $this->traitScopeRole($query, $roles, $guard, $without);
    }

    public function scopePermission(\Illuminate\Database\Eloquent\Builder $query, $permissions, $without = false): \Illuminate\Database\Eloquent\Builder
    {
        if (tenancy()->initialized) {
            $ref = new \ReflectionProperty($query->getQuery(), 'connection');
            $ref->setAccessible(true);
            $ref->setValue($query->getQuery(), \DB::connection(config('database.default')));

            $centralDb = config('database.connections.central.database', 'lsank_laravel');
            $query->from($centralDb . '.users');
        } else {
            return $query->whereRaw('1 = 0');
        }
        return $this->traitScopePermission($query, $permissions, $without);
    }

    public function jcrs()
    {
        return $this->belongsToMany(Jcr::class, 'jcruser', 'user_id', 'jcr_id')
                    ->using(TenantPivot::class); // Use custom pivot
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

    // Relationship with approver user
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
