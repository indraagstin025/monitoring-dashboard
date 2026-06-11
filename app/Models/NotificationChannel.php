<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationChannel extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'config',
        'is_active',
        'last_tested_at',
        'last_test_passed',
    ];

    protected $casts = [
        'config'           => 'array',
        'is_active'        => 'boolean',
        'last_tested_at'   => 'datetime',
        'last_test_passed' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTelegram($query)
    {
        return $query->where('type', 'telegram');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    /**
     * Ambil nilai konfigurasi tertentu.
     * Contoh: $channel->getConfig('bot_token')
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Label tipe channel untuk UI.
     */
    public function typeLabel(): string
    {
        return match ($this->type) {
            'telegram' => 'Telegram',
            'discord'  => 'Discord',
            'email'    => 'Email',
            'webhook'  => 'Webhook',
            default    => ucfirst($this->type),
        };
    }
}
