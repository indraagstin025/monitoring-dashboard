<?php

namespace App\Jobs;

use App\Models\NotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Job notifikasi generik yang mendukung multi-channel (Telegram, Discord, dll).
 * Menggantikan SendTelegramNotificationJob dengan model "own bot per user".
 */
class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries   = 3;
    public $timeout = 15;

    public function __construct(
        public readonly int    $channelId,
        public readonly string $message
    ) {}

    public function handle(): void
    {
        $channel = NotificationChannel::find($this->channelId);

        if (!$channel || !$channel->is_active) {
            return;
        }

        match ($channel->type) {
            'telegram' => $this->sendTelegram($channel),
            'discord'  => $this->sendDiscord($channel),
            default    => Log::warning("Tipe channel tidak dikenal: {$channel->type}"),
        };
    }

    private function sendTelegram(NotificationChannel $channel): void
    {
        $botToken = $channel->getConfig('bot_token');
        $chatId   = $channel->getConfig('chat_id');

        if (!$botToken || !$chatId) {
            Log::warning("Channel #{$channel->id}: bot_token atau chat_id kosong.");
            return;
        }

        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

        try {
            $response = Http::retry(3, 200)->timeout(10)->post($url, [
                'chat_id'    => $chatId,
                'text'       => $this->message,
                'parse_mode' => 'HTML',
            ]);

            if (!$response->successful()) {
                Log::error("Telegram API Error (channel #{$channel->id})", [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            } else {
                Log::info("Notifikasi Telegram terkirim via channel #{$channel->id}");
            }
        } catch (\Exception $e) {
            Log::error("Gagal kirim Telegram (channel #{$channel->id}): " . $e->getMessage());
            throw $e; // Re-throw agar job bisa di-retry
        }
    }

    private function sendDiscord(NotificationChannel $channel): void
    {
        $webhookUrl = $channel->getConfig('webhook_url');

        if (!$webhookUrl) {
            Log::warning("Channel #{$channel->id}: webhook_url kosong.");
            return;
        }

        // Konversi HTML ke plain text untuk Discord
        $plainText = strip_tags(str_replace(['<b>', '</b>'], '**', $this->message));

        try {
            $response = Http::retry(3, 200)->timeout(10)->post($webhookUrl, [
                'content' => $plainText,
            ]);

            if (!$response->successful()) {
                Log::error("Discord Webhook Error (channel #{$channel->id})", [
                    'status' => $response->status(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Gagal kirim Discord (channel #{$channel->id}): " . $e->getMessage());
            throw $e;
        }
    }
}
