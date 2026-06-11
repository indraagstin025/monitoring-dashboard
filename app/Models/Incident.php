<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'target_id',
        'started_at',
        'resolved_at',
        'duration_seconds',
        'trigger_status',
        'notes',
    ];

    protected $casts = [
        'started_at'  => 'datetime',
        'resolved_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────

    public function target()
    {
        return $this->belongsTo(Target::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────

    /** Incident yang masih berlangsung (belum resolved) */
    public function scopeOngoing($query)
    {
        return $query->whereNull('resolved_at');
    }

    /** Incident yang sudah selesai */
    public function scopeResolved($query)
    {
        return $query->whereNotNull('resolved_at');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────

    /**
     * Durasi incident yang ditampilkan dalam format human-readable.
     */
    public function durationHuman(): string
    {
        if (!$this->resolved_at) {
            // Masih berlangsung — hitung dari sekarang
            $seconds = $this->started_at->diffInSeconds(now());
        } else {
            $seconds = $this->duration_seconds ?? 0;
        }

        if ($seconds < 60) {
            return "{$seconds} detik";
        } elseif ($seconds < 3600) {
            $minutes = (int) ($seconds / 60);
            return "{$minutes} menit";
        } else {
            $hours   = (int) ($seconds / 3600);
            $minutes = (int) (($seconds % 3600) / 60);
            return "{$hours}j {$minutes}m";
        }
    }

    /**
     * Apakah incident masih berlangsung?
     */
    public function isOngoing(): bool
    {
        return $this->resolved_at === null;
    }
}
