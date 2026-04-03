<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'payload',
        'otp_hash',
        'expires_at',
        'used_at',
        'resend_count',
    ];

    protected $casts = [
        'payload' => 'array',
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at?->isPast() ?? true;
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }
}