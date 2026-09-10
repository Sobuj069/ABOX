<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'credits',
        'is_vip',
        'vip_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_vip' => 'boolean',
            'vip_expires_at' => 'datetime',
            'credits' => 'integer',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasVip(): bool
    {
        if ($this->is_vip) {
            if ($this->vip_expires_at === null || $this->vip_expires_at->isFuture()) {
                return true;
            }
        }
        return false;
    }

    public function recordings(): HasMany
    {
        return $this->hasMany(Recording::class);
    }

    public function appSettings(): HasMany
    {
        return $this->hasMany(UserAppSetting::class);
    }
}
