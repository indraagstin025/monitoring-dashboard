<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Target extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'host',
        'port',
        'protocol',
        'status',
        'is_paused',
        'consecutive_failures',
        'alert_threshold',
        'check_interval',
        'timeout',
        'group_name',
        'tags',
        'last_response_time',
        'last_checked_at',
        'ssl_expires_at',
        'uptime_1d',
        'uptime_7d',
        'uptime_30d',
        'notify_on_recovery',
    ];

    protected $casts = [
        'last_checked_at'    => 'datetime',
        'ssl_expires_at'     => 'datetime',
        'last_response_time' => 'float',
        'is_paused'          => 'boolean',
        'notify_on_recovery' => 'boolean',
        'tags'               => 'array',
        'uptime_1d'          => 'float',
        'uptime_7d'          => 'float',
        'uptime_30d'         => 'float',
    ];

    protected $attributes = [
        'status'              => 'unknown',
        'consecutive_failures'=> 0,
        'alert_threshold'     => 3,
        'check_interval'      => 300,
        'timeout'             => 5,
        'protocol'            => 'http',
        'is_paused'           => false,
        'notify_on_recovery'  => true,
    ];

    // ─── Scopes ───────────────────────────────────────────────────────────

    /** Hanya target yang aktif (tidak di-pause) */
    public function scopeActive($query)
    {
        return $query->where('is_paused', false);
    }

    /** Filter berdasarkan grup */
    public function scopeInGroup($query, string $group)
    {
        return $query->where('group_name', $group);
    }

    // ─── Relationships ────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function statusLogs()
    {
        return $this->hasMany(StatusLog::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class)->orderByDesc('started_at');
    }

    public function activeIncident()
    {
        return $this->hasOne(Incident::class)->whereNull('resolved_at')->latest('started_at');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    /**
     * Hitung uptime % berdasarkan status_logs dalam N hari terakhir.
     * Digunakan oleh CalculateUptimeCommand.
     */
    public function calculateUptime(int $days): float
    {
        $since = now()->subDays($days);

        $total = $this->statusLogs()
            ->where('checked_at', '>=', $since)
            ->count();

        if ($total === 0) {
            return 100.0;
        }

        $upCount = $this->statusLogs()
            ->where('checked_at', '>=', $since)
            ->where('status', 'up')
            ->count();

        return round(($upCount / $total) * 100, 2);
    }

    /**
     * Cek apakah SSL akan expired dalam N hari ke depan.
     */
    public function sslExpiresInDays(): ?int
    {
        if (!$this->ssl_expires_at) {
            return null;
        }
        return (int) now()->diffInDays($this->ssl_expires_at, false);
    }

    /**
     * Label status SSL untuk UI.
     */
    public function sslStatus(): string
    {
        $days = $this->sslExpiresInDays();
        if ($days === null) return 'unknown';
        if ($days < 0)  return 'expired';
        if ($days <= 3)  return 'critical';
        if ($days <= 14) return 'warning';
        return 'valid';
    }
}
