<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_app_id',
        'selected_voice_id',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function targetApp(): BelongsTo
    {
        return $this->belongsTo(TargetApp::class);
    }

    public function voice(): BelongsTo
    {
        return $this->belongsTo(Voice::class, 'selected_voice_id');
    }
}
