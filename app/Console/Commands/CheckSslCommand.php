<?php

namespace App\Console\Commands;

use App\Models\Target;
use App\Jobs\SendNotificationJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSslCommand extends Command
{
    protected $signature   = 'ssl:check';
    protected $description = 'Cek SSL expiry semua target HTTPS dan kirim notifikasi jika hampir expired';

    public function handle(): void
    {
        $targets = Target::active()
            ->where('protocol', 'https')
            ->whereNotNull('ssl_expires_at')
            ->get();

        $this->info("Memeriksa SSL untuk {$targets->count()} target HTTPS...");
        $notified = 0;

        foreach ($targets as $target) {
            $daysLeft = $target->sslExpiresInDays();

            if ($daysLeft === null) continue;

            // Kirim notifikasi jika SSL akan expired dalam 14 hari atau 3 hari
            $shouldNotify = ($daysLeft <= 14 && $daysLeft > 3)
                         || ($daysLeft <= 3 && $daysLeft >= 0);

            $cacheKey = "ssl_notif_{$target->id}_{$daysLeft}";

            if ($shouldNotify && !cache()->has($cacheKey)) {
                $severity = $daysLeft <= 3 ? 'CRITICAL' : 'WARNING';
                $safeName = htmlspecialchars($target->name);
                $safeHost = htmlspecialchars($target->host);
                $expiry   = $target->ssl_expires_at->format('d M Y');

                $msg = "<b>[SSL {$severity}] Sertifikat Hampir Expired!</b>\n\n"
                     . "Target: <b>{$safeName}</b>\n"
                     . "Host: {$safeHost}\n"
                     . "Expired pada: {$expiry}\n"
                     . "Sisa: <b>{$daysLeft} hari</b>";

                $channels = $target->user->notificationChannels()->active()->get();

                foreach ($channels as $channel) {
                    SendNotificationJob::dispatch($channel->id, $msg)->onQueue('notifications');
                }

                // Cooldown: 1 hari agar tidak spam
                cache()->put($cacheKey, true, 86400);
                $notified++;

                Log::warning("SSL Alert: {$target->name} expires in {$daysLeft} days");
            }
        }

        $this->info("Selesai. {$notified} notifikasi SSL terkirim.");
    }
}
