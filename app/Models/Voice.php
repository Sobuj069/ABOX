<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'category',
        'avatar',
        'preview_audio',
        'is_vip',
        'pitch_shift',
        'speed',
        'reverb',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_vip' => 'boolean',
        'is_active' => 'boolean',
        'pitch_shift' => 'float',
        'speed' => 'float',
        'reverb' => 'float',
        'order' => 'integer',
    ];

    public function recordings(): HasMany
    {
        return $this->hasMany(Recording::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order', 'asc');
    }
}
