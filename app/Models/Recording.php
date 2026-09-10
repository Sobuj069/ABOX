<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recording extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'voice_id',
        'title',
        'original_file',
        'processed_file',
        'duration',
        'file_size',
        'pitch_shift',
    ];

    protected $casts = [
        'duration' => 'float',
        'file_size' => 'integer',
        'pitch_shift' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voice(): BelongsTo
    {
        return $this->belongsTo(Voice::class);
    }
}
