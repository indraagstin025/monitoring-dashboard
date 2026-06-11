<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TargetController extends Controller
{
    public function index()
    {
        $targets = Auth::user()->targets()->latest()->get();
        return view('dashboard', compact('targets'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedTargetData($request);

        Auth::user()->targets()->create($data);

        return redirect()->route('dashboard')
            ->with('success', 'Target server baru berhasil ditambahkan!');
    }

    public function show($id)
    {
        $target = Auth::user()->targets()->findOrFail($id);

        // Data grafik response time 24 jam terakhir
        $chartData = $target->statusLogs()
            ->where('checked_at', '>=', now()->subHours(24))
            ->orderBy('checked_at')
            ->get(['checked_at', 'response_time_ms', 'status'])
            ->map(fn($log) => [
                'x' => $log->checked_at->timestamp * 1000, // ms untuk JS
                'y' => $log->response_time_ms,
                's' => $log->status,
            ]);

        // Incident historis
        $incidents = $target->incidents()
            ->latest('started_at')
            ->limit(20)
            ->get();

        // Uptime stats
        $uptimeStats = [
            '24h'  => $target->uptime_1d  ?? $target->calculateUptime(1),
            '7d'   => $target->uptime_7d  ?? $target->calculateUptime(7),
            '30d'  => $target->uptime_30d ?? $target->calculateUptime(30),
        ];

        return view('targets.show', compact('target', 'chartData', 'incidents', 'uptimeStats'));
    }

    public function edit($id)
    {
        $target = Auth::user()->targets()->findOrFail($id);
        return view('targets.edit', compact('target'));
    }

    public function update(Request $request, $id)
    {
        $target = Auth::user()->targets()->findOrFail($id);

        $data = $this->validatedTargetData($request, true);

        $target->update($data);

        return redirect()->route('targets.show', $target->id)
            ->with('success', 'Target berhasil diperbarui!');
    }

    public function togglePause($id)
    {
        $target = Auth::user()->targets()->findOrFail($id);
        $target->update(['is_paused' => !$target->is_paused]);

        $status = $target->is_paused ? 'dijeda' : 'diaktifkan kembali';
        return back()->with('success', "Target \"{$target->name}\" berhasil {$status}.");
    }

    public function destroy($id)
    {
        $target = Auth::user()->targets()->findOrFail($id);
        $target->delete();
        return redirect()->route('dashboard')
            ->with('success', 'Target berhasil dihapus.');
    }

    private function validatedTargetData(Request $request, bool $isUpdate = false): array
    {
        $rules = [
            'name'           => 'required|string|max:255',
            'host'           => 'required|string|max:255',
            'port'           => 'nullable|integer|min:1|max:65535',
            'protocol'       => 'nullable|in:http,https,tcp',
            'check_interval' => 'nullable|integer|min:10',
            'timeout'        => 'nullable|integer|min:1|max:30',
            'alert_threshold'=> 'nullable|integer|min:1|max:10',
            'group_name'     => 'nullable|string|max:100',
        ];

        if ($isUpdate) {
            $rules['notify_on_recovery'] = 'nullable|boolean';
        }

        $data = $request->validate($rules, [
            'name.required' => 'Nama target wajib diisi.',
            'name.max' => 'Nama target maksimal 255 karakter.',
            'host.required' => 'Host, domain, IP, atau URL wajib diisi.',
            'host.max' => 'Host maksimal 255 karakter.',
            'port.integer' => 'Port harus berupa angka.',
            'port.min' => 'Port minimal 1.',
            'port.max' => 'Port maksimal 65535.',
            'protocol.in' => 'Protocol harus HTTP, HTTPS, atau TCP.',
            'check_interval.min' => 'Interval cek minimal 10 detik.',
            'timeout.min' => 'Timeout minimal 1 detik.',
            'timeout.max' => 'Timeout maksimal 30 detik.',
            'alert_threshold.min' => 'Alert threshold minimal 1.',
            'alert_threshold.max' => 'Alert threshold maksimal 10.',
            'group_name.max' => 'Nama grup maksimal 100 karakter.',
        ]);

        [$host, $protocol, $port] = $this->normalizeHost(
            $data['host'],
            $data['protocol'] ?? null,
            $data['port'] ?? null
        );

        $data['name'] = trim($data['name']);
        $data['host'] = $host;
        $data['protocol'] = $protocol;
        $data['port'] = $port;
        $data['group_name'] = isset($data['group_name']) && trim((string) $data['group_name']) !== ''
            ? trim($data['group_name'])
            : null;

        return $data;
    }

    private function normalizeHost(string $host, ?string $protocol, mixed $port): array
    {
        $rawHost = trim($host);
        $source = preg_match('/^[a-z][a-z0-9+.-]*:\/\//i', $rawHost) ? $rawHost : '//' . $rawHost;
        $parts = parse_url($source);

        $parsedHost = $parts['host'] ?? null;

        if (!$parsedHost && !str_contains($rawHost, '/')) {
            $parsedHost = $rawHost;
        }

        $parsedHost = trim((string) $parsedHost, " \t\n\r\0\x0B[]");

        if ($parsedHost === '' || preg_match('/\s/', $parsedHost)) {
            throw ValidationException::withMessages([
                'host' => 'Host tidak valid. Gunakan domain/IP, contoh: example.com atau https://example.com.',
            ]);
        }

        $parsedProtocol = strtolower((string) ($parts['scheme'] ?? $protocol ?? 'https'));

        if (!in_array($parsedProtocol, ['http', 'https', 'tcp'], true)) {
            throw ValidationException::withMessages([
                'protocol' => 'Protocol dari URL tidak didukung. Gunakan HTTP, HTTPS, atau TCP.',
            ]);
        }

        $parsedPort = $port ?: ($parts['port'] ?? null);

        return [$parsedHost, $parsedProtocol, $parsedPort ? (int) $parsedPort : null];
    }
}
