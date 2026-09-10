<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TargetApp extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'badge',
        'icon',
        'category',
        'package_name',
        'is_active',
        'order',
        'guide_info',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function userSettings(): HasMany
    {
        return $this->hasMany(UserAppSetting::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order', 'asc');
    }
}
