<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $targets = Auth::user()->targets()->with('incidents')->get();

        // Ringkasan per target
        $summary = $targets->map(function ($target) {
            $incidents = $target->incidents;
            return [
                'id'               => $target->id,
                'name'             => $target->name,
                'host'             => $target->host,
                'status'           => $target->status,
                'uptime_1d'        => $target->uptime_1d,
                'uptime_7d'        => $target->uptime_7d,
                'uptime_30d'       => $target->uptime_30d,
                'total_incidents'  => $incidents->count(),
                'avg_response'     => round($target->statusLogs()
                    ->where('checked_at', '>=', now()->subDays(30))
                    ->avg('response_time_ms') ?? 0),
            ];
        });

        return view('reports.index', compact('targets', 'summary'));
    }

    public function exportCsv()
    {
        $targets = Auth::user()->targets()->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="uptime-report-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($targets) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM untuk Excel

            // Header CSV
            fputcsv($file, [
                'Nama Target', 'Host', 'Protocol', 'Status',
                'Uptime 24h (%)', 'Uptime 7d (%)', 'Uptime 30d (%)',
                'Avg Response Time (ms)', 'Total Incident (30d)',
                'Terakhir Dicek',
            ]);

            foreach ($targets as $target) {
                $avgResponse = round(
                    $target->statusLogs()
                        ->where('checked_at', '>=', now()->subDays(30))
                        ->avg('response_time_ms') ?? 0
                );

                $incidentCount = $target->incidents()
                    ->where('started_at', '>=', now()->subDays(30))
                    ->count();

                fputcsv($file, [
                    $target->name,
                    $target->host,
                    strtoupper($target->protocol),
                    ucfirst($target->status),
                    $target->uptime_1d  ?? 'N/A',
                    $target->uptime_7d  ?? 'N/A',
                    $target->uptime_30d ?? 'N/A',
                    $avgResponse,
                    $incidentCount,
                    $target->last_checked_at?->format('d/m/Y H:i') ?? 'Belum pernah',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
