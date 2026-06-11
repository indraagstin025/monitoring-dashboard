<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="eyebrow">30 Hari Terakhir</p>
                <h2 class="font-black text-2xl" style="color: var(--md-text)">Laporan Uptime</h2>
            </div>
            <a href="{{ route('reports.export-csv') }}" class="md-button">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="metric-card p-5">
                    <div class="eyebrow mb-2">Total Target</div>
                    <div class="text-3xl font-black" style="color: var(--md-text)">{{ $targets->count() }}</div>
                </div>
                <div class="metric-card p-5">
                    <div class="eyebrow mb-2">Avg Uptime 30d</div>
                    @php $avgUptime = $summary->avg('uptime_30d'); @endphp
                    <div class="text-3xl font-black {{ $avgUptime >= 99 ? 'text-emerald-600 dark:text-emerald-400' : ($avgUptime >= 95 ? 'text-yellow-600 dark:text-yellow-300' : 'text-red-600 dark:text-red-400') }}">
                        {{ $avgUptime ? number_format($avgUptime, 2).'%' : 'N/A' }}
                    </div>
                </div>
                <div class="metric-card p-5">
                    <div class="eyebrow mb-2">Total Incident</div>
                    <div class="text-3xl font-black" style="color: var(--md-text)">{{ $summary->sum('total_incidents') }}</div>
                </div>
                <div class="metric-card p-5">
                    <div class="eyebrow mb-2">Avg Response</div>
                    <div class="text-3xl font-black" style="color: var(--md-text)">{{ number_format($summary->avg('avg_response')) }} ms</div>
                </div>
            </div>

            <div class="surface-card overflow-hidden">
                <div class="px-6 py-4 border-b" style="border-color: var(--md-border)">
                    <h3 class="text-sm font-bold" style="color: var(--md-text)">Detail per Target</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="monitor-table">
                        <thead>
                            <tr>
                                <th>Target</th>
                                <th>Status</th>
                                <th>Uptime 24h</th>
                                <th>Uptime 7d</th>
                                <th>Uptime 30d</th>
                                <th>Avg Response</th>
                                <th>Incident</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($summary as $row)
                                <tr>
                                    <td>
                                        <a href="{{ route('targets.show', $row['id']) }}" class="text-sm font-black text-yellow-700 dark:text-yellow-300 hover:underline">
                                            {{ $row['name'] }}
                                        </a>
                                        <div class="text-xs" style="color: var(--md-muted)">{{ $row['host'] }}</div>
                                    </td>
                                    <td>
                                        @if($row['status'] === 'up')
                                            <span class="status-badge status-badge-up">Up</span>
                                        @elseif($row['status'] === 'down')
                                            <span class="status-badge status-badge-down">Down</span>
                                        @else
                                            <span class="status-badge status-badge-warning">{{ ucfirst($row['status']) }}</span>
                                        @endif
                                    </td>
                                    @foreach(['uptime_1d', 'uptime_7d', 'uptime_30d'] as $key)
                                        <td>
                                            @if($row[$key] !== null)
                                                @php $u = (float)$row[$key]; @endphp
                                                <span class="text-sm font-black {{ $u >= 99 ? 'text-emerald-600 dark:text-emerald-400' : ($u >= 95 ? 'text-yellow-600 dark:text-yellow-300' : 'text-red-600 dark:text-red-400') }}">
                                                    {{ number_format($u, 2) }}%
                                                </span>
                                            @else
                                                <span class="text-sm" style="color: var(--md-muted)">N/A</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="text-sm">{{ number_format($row['avg_response']) }} ms</td>
                                    <td>
                                        <span class="text-sm font-black {{ $row['total_incidents'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                            {{ $row['total_incidents'] }}x
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
