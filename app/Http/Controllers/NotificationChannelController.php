<?php

namespace App\Http\Controllers;

use App\Models\NotificationChannel;
use App\Jobs\SendNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class NotificationChannelController extends Controller
{
    public function index()
    {
        $channels = Auth::user()->notificationChannels()->latest()->get();
        return view('notifications.settings', compact('channels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:telegram,discord',
            // Telegram
            'telegram_bot_token' => 'required_if:type,telegram|nullable|string|max:255',
            'telegram_chat_id'   => ['required_if:type,telegram', 'nullable', 'regex:/^-?\d+$/'],
            // Discord
            'discord_webhook_url' => 'required_if:type,discord|nullable|url',
        ], [
            'name.required' => 'Nama channel wajib diisi.',
            'name.max' => 'Nama channel maksimal 100 karakter.',
            'type.required' => 'Tipe channel wajib dipilih.',
            'type.in' => 'Tipe channel hanya Telegram atau Discord.',
            'telegram_bot_token.required_if' => 'Bot Token wajib diisi untuk Telegram.',
            'telegram_bot_token.max' => 'Bot Token terlalu panjang.',
            'telegram_chat_id.required_if' => 'Chat ID wajib diisi untuk Telegram.',
            'telegram_chat_id.regex' => 'Chat ID harus berupa angka. Untuk grup biasanya diawali tanda minus.',
            'discord_webhook_url.required_if' => 'Webhook URL wajib diisi untuk Discord.',
            'discord_webhook_url.url' => 'Webhook URL Discord harus berupa link yang valid.',
        ]);

        $telegramBotToken = $request->type === 'telegram'
            ? $this->normalizeTelegramToken((string) $request->telegram_bot_token)
            : null;

        if ($request->type === 'telegram' && ! $telegramBotToken) {
            return back()
                ->withErrors(['telegram_bot_token' => 'Token Telegram tidak valid. Paste token saja atau URL getUpdates yang benar.'])
                ->withInput();
        }

        $config = match ($request->type) {
            'telegram' => [
                'bot_token' => $telegramBotToken,
                'chat_id'   => trim((string) $request->telegram_chat_id),
            ],
            'discord' => [
                'webhook_url' => trim((string) $request->discord_webhook_url),
            ],
            default => [],
        };

        Auth::user()->notificationChannels()->create([
            'name'      => $request->name,
            'type'      => $request->type,
            'config'    => $config,
            'is_active' => true,
        ]);

        return redirect()->route('notifications.index')
            ->with('success', 'Channel notifikasi berhasil ditambahkan!');
    }

    public function lookupTelegramChats(Request $request)
    {
        $request->validate([
            'telegram_bot_token' => 'required|string|max:255',
        ]);

        $token = $this->normalizeTelegramToken($request->telegram_bot_token);

        if (!$token) {
            return response()->json([
                'message' => 'Token Telegram tidak valid. Paste token saja atau URL getUpdates yang benar.',
            ], 422);
        }

        $botResponse = Http::timeout(10)->get("https://api.telegram.org/bot{$token}/getMe");

        if (!$botResponse->successful() || $botResponse->json('ok') !== true) {
            return response()->json([
                'message' => 'Token tidak valid atau bot tidak ditemukan.',
            ], 422);
        }

        $updatesResponse = Http::timeout(10)->get("https://api.telegram.org/bot{$token}/getUpdates");

        if (!$updatesResponse->successful() || $updatesResponse->json('ok') !== true) {
            return response()->json([
                'message' => 'Gagal membaca update Telegram. Coba kirim /start ke bot lalu ulangi.',
            ], 422);
        }

        $chats = collect($updatesResponse->json('result', []))
            ->map(function (array $update) {
                return data_get($update, 'message.chat')
                    ?? data_get($update, 'edited_message.chat')
                    ?? data_get($update, 'channel_post.chat')
                    ?? data_get($update, 'my_chat_member.chat');
            })
            ->filter()
            ->unique('id')
            ->values()
            ->map(fn (array $chat) => [
                'id' => (string) $chat['id'],
                'title' => $chat['title']
                    ?? trim(($chat['first_name'] ?? '') . ' ' . ($chat['last_name'] ?? ''))
                    ?: ($chat['username'] ?? 'Private Chat'),
                'type' => $chat['type'] ?? 'unknown',
                'username' => $chat['username'] ?? null,
            ])
            ->values();

        if ($chats->isEmpty()) {
            return response()->json([
                'token' => $token,
                'bot' => $botResponse->json('result.username'),
                'chats' => [],
                'message' => 'Chat ID belum ditemukan. Kirim /start ke bot, lalu klik Ambil Chat ID lagi.',
            ], 422);
        }

        return response()->json([
            'token' => $token,
            'bot' => $botResponse->json('result.username'),
            'chats' => $chats,
            'message' => $chats->count() === 1
                ? 'Chat ID ditemukan dan sudah diisi otomatis.'
                : 'Beberapa chat ditemukan. Pilih salah satu Chat ID.',
        ]);
    }

    public function destroy($id)
    {
        $channel = Auth::user()->notificationChannels()->findOrFail($id);
        $channel->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Channel berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $channel = Auth::user()->notificationChannels()->findOrFail($id);
        $channel->update(['is_active' => !$channel->is_active]);

        $status = $channel->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Channel \"{$channel->name}\" berhasil {$status}.");
    }

    /**
     * Kirim pesan test ke channel dan catat hasilnya.
     */
    public function test($id)
    {
        $channel = Auth::user()->notificationChannels()->findOrFail($id);

        $testMsg = "<b>[TEST] Test Notifikasi UptimeGuard</b>\n\n"
                 . "Channel: <b>{$channel->name}</b>\n"
                 . "Tipe: {$channel->type}\n"
                 . "Waktu: " . now()->format('d M Y H:i:s');

        // Dispatch langsung (bukan queue) agar hasilnya bisa langsung diketahui
        try {
            SendNotificationJob::dispatchSync($channel->id, $testMsg);
            $channel->update([
                'last_tested_at'   => now(),
                'last_test_passed' => true,
            ]);
            return back()->with('success', 'Test notifikasi berhasil dikirim! Cek channel Anda.');
        } catch (\Exception $e) {
            $channel->update([
                'last_tested_at'   => now(),
                'last_test_passed' => false,
            ]);
            return back()->with('error', 'Gagal kirim test: ' . $e->getMessage());
        }
    }

    private function normalizeTelegramToken(string $value): ?string
    {
        $value = trim($value);

        if (preg_match('/\/bot([^\/\s]+)\/(?:getUpdates|getMe|sendMessage)/', $value, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^\d+:[A-Za-z0-9_-]+$/', $value)) {
            return $value;
        }

        return null;
    }
}
