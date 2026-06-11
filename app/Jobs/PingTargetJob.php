<?php

namespace App\Jobs;

use App\Models\Target;
use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PingTargetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $targetId;
    public $timeout = 10;
    public $tries = 3;

    public function __construct($targetId)
    {
        $this->targetId = $targetId;
    }

    public function handle(): void
    {
        // 1. Fetch data awal
        $target = Target::find($this->targetId);

        if (!$target || $target->is_paused) {
            return; // Skip target yang tidak ada atau sedang di-pause
        }

        $startTime = microtime(true);
        $isUp      = false;
        $sslInfo   = null;

        try {
            if (in_array($target->protocol, ['http', 'https'])) {
                $url = rtrim($target->protocol . '://' . $target->host, '/');
                if ($target->port) {
                    $url .= ':' . $target->port;
                }

                $response = Http::timeout($target->timeout)
                    ->retry(2, 100)
                    ->get($url);

                $isUp = $response->successful();

                // SSL check untuk HTTPS
                if ($target->protocol === 'https') {
                    $sslInfo = $this->checkSsl($target->host, $target->port ?? 443);
                }
            } else {
                $port       = $target->port ?? 80;
                $connection = fsockopen($target->host, $port, $errno, $errstr, $target->timeout);

                if (is_resource($connection)) {
                    $isUp = true;
                    fclose($connection);
                } else {
                    $isUp = false;
                    Log::warning("TCP gagal untuk target {$target->id}: {$errstr} ({$errno})");
                }
            }
        } catch (\Exception $e) {
            $isUp = false;
            Log::warning("Gagal ping target {$target->id}: " . $e->getMessage());
        }

        $responseTime = round((microtime(true) - $startTime) * 1000);

        // 2. Transaksi Database
        $updatedTarget = null;
        $previousStatus = null;
        $newStatus      = null;

        \Illuminate\Support\Facades\DB::transaction(function () use ($isUp, $responseTime, $sslInfo, &$updatedTarget, &$previousStatus, &$newStatus) {
            $target = Target::lockForUpdate()->find($this->targetId);

            if (!$target) return;

            $previousStatus       = $target->status;
            $consecutiveFailures  = $target->consecutive_failures;
            $threshold            = $target->alert_threshold ?? 3;

            if ($isUp) {
                $consecutiveFailures = 0;
                $newStatus = 'up';
            } else {
                $consecutiveFailures++;
                $newStatus = $consecutiveFailures >= $threshold ? 'down' : 'unstable';
            }

            $updateData = [
                'status'               => $newStatus,
                'consecutive_failures' => $consecutiveFailures,
                'last_response_time'   => $responseTime,
                'last_checked_at'      => now(),
            ];

            // Update SSL expiry jika ada info baru
            if ($sslInfo !== null) {
                $updateData['ssl_expires_at'] = $sslInfo;
            }

            $target->update($updateData);
            $target->refresh();

            // Atomic Insert Log
            $target->statusLogs()->create([
                'status'          => $newStatus,
                'response_time_ms'=> $responseTime,
                'checked_at'      => now(),
            ]);

            // Incident tracking
            $this->handleIncident($target, $previousStatus, $newStatus);

            $target->forceFill([
                'uptime_1d' => $target->calculateUptime(1),
                'uptime_7d' => $target->calculateUptime(7),
                'uptime_30d' => $target->calculateUptime(30),
            ])->save();
            $target->refresh();

            $updatedTarget = $target;
        });

        // 3. Broadcast & Notifikasi setelah transaksi
        if ($updatedTarget) {
            \App\Events\TargetPinged::dispatch($updatedTarget);
            $this->handleNotifications($updatedTarget, $previousStatus, $newStatus);
        }
    }

    /**
     * Cek tanggal expiry SSL certificate.
     * Return Carbon instance atau null jika gagal.
     */
    private function checkSsl(string $host, int $port = 443): ?\Illuminate\Support\Carbon
    {
        try {
            $context = stream_context_create([
                'ssl' => [
                    'capture_peer_cert' => true,
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                ],
            ]);

            $socket = @stream_socket_client(
                "ssl://{$host}:{$port}",
                $errno, $errstr, 10,
                STREAM_CLIENT_CONNECT,
                $context
            );

            if (!$socket) {
                return null;
            }

            $params = stream_context_get_params($socket);
            $cert   = openssl_x509_parse($params['options']['ssl']['peer_certificate'] ?? null);
            fclose($socket);

            if (!$cert || empty($cert['validTo_time_t'])) {
                return null;
            }

            return \Illuminate\Support\Carbon::createFromTimestamp($cert['validTo_time_t']);
        } catch (\Exception $e) {
            Log::debug("SSL check gagal untuk {$host}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buka atau tutup incident secara otomatis berdasarkan perubahan status.
     */
    private function handleIncident(Target $target, ?string $previousStatus, string $newStatus): void
    {
        $wasDown = in_array($previousStatus, ['down', 'unstable']);
        $isDown  = in_array($newStatus, ['down', 'unstable']);

        // Buka incident baru jika baru saja down
        if (!$wasDown && $isDown) {
            Incident::create([
                'target_id'      => $target->id,
                'started_at'     => now(),
                'trigger_status' => $newStatus,
            ]);
        }

        // Tutup incident yang sedang berlangsung jika sudah up
        if ($wasDown && $newStatus === 'up') {
            $ongoingIncident = Incident::where('target_id', $target->id)
                ->whereNull('resolved_at')
                ->latest('started_at')
                ->first();

            if ($ongoingIncident) {
                $duration = $ongoingIncident->started_at->diffInSeconds(now());
                $ongoingIncident->update([
                    'resolved_at'      => now(),
                    'duration_seconds' => $duration,
                ]);
            }
        }
    }

    /**
     * Kirim notifikasi ke semua channel aktif milik user target.
     */
    private function handleNotifications(Target $target, ?string $previousStatus, string $newStatus): void
    {
        $threshold   = $target->alert_threshold ?? 3;
        $cacheKey    = "notif_cooldown_{$target->id}_{$newStatus}";

        $shouldNotifyDown     = $newStatus === 'down' && $previousStatus !== 'down' && !cache()->has($cacheKey);
        $shouldNotifyRecovery = $newStatus === 'up' && in_array($previousStatus, ['down', 'unstable'])
                                && $target->notify_on_recovery && !cache()->has($cacheKey);

        if (!$shouldNotifyDown && !$shouldNotifyRecovery) {
            return;
        }

        $safeName = htmlspecialchars($target->name);
        $safeHost = htmlspecialchars($target->host);

        if ($shouldNotifyDown) {
            $msg = "<b>[URGENT] Server DOWN!</b>\n\nTarget: <b>{$safeName}</b>\nHost: {$safeHost}\nStatus: Gagal merespons {$threshold}x berturut-turut.";
            Log::alert("Server {$target->name} DOWN!");
        } else {
            $msg = "<b>[RECOVERED] Server UP!</b>\n\nTarget: <b>{$safeName}</b>\nHost: {$safeHost}\nStatus: Kembali online dan stabil.";
            Log::info("Server {$target->name} RECOVERED!");
        }

        // Kirim ke semua channel notifikasi aktif milik user
        $channels = $target->user->notificationChannels()->active()->get();

        foreach ($channels as $channel) {
            \App\Jobs\SendNotificationJob::dispatch($channel->id, $msg)->onQueue('notifications');
        }

        cache()->put($cacheKey, true, 300); // Cooldown 5 menit
    }
}
