<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="md-icon-button" title="Kembali">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <p class="eyebrow">Target Detail</p>
                <h2 class="font-black text-2xl" style="color: var(--md-text)">{{ $target->name }}</h2>
                <p class="text-sm" style="color: var(--md-muted)">{{ strtoupper($target->protocol) }}://{{ $target->host }}{{ $target->port ? ':'.$target->port : '' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="surface-card p-4 text-sm font-medium" style="color: var(--md-success)">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="metric-card p-5">
                    <div class="eyebrow mb-3">Status Saat Ini</div>
                    @if($target->status === 'up')
                        <div class="flex items-center gap-3"><span class="status-dot status-up"></span><span class="text-lg font-black" style="color: var(--md-success)">Online</span></div>
                    @elseif($target->status === 'down')
                        <div class="flex items-center gap-3"><span class="status-dot status-down animate-pulse"></span><span class="text-lg font-black" style="color: var(--md-danger)">Down</span></div>
                    @elseif($target->status === 'unstable')
                        <div class="flex items-center gap-3"><span class="status-dot status-warning"></span><span class="text-lg font-black" style="color: var(--md-primary)">Unstable</span></div>
                    @else
                        <div class="flex items-center gap-3"><span class="status-dot status-muted"></span><span class="text-lg font-black" style="color: var(--md-muted)">Unknown</span></div>
                    @endif
                </div>

                <div class="metric-card p-5">
                    <div class="eyebrow mb-3">Response Time</div>
                    <div class="text-2xl font-black" style="color: var(--md-text)">
                        {{ $target->last_response_time ? number_format($target->last_response_time).' ms' : '-' }}
                    </div>
                </div>

                <div class="metric-card p-5">
                    <div class="eyebrow mb-3">Uptime 7 Hari</div>
                    @php $u7 = $uptimeStats['7d']; @endphp
                    <div class="text-2xl font-black {{ $u7 >= 99 ? 'text-emerald-600 dark:text-emerald-400' : ($u7 >= 95 ? 'text-yellow-600 dark:text-yellow-300' : 'text-red-600 dark:text-red-400') }}">
                        {{ number_format($u7, 2) }}%
                    </div>
                </div>

                <div class="metric-card p-5">
                    <div class="eyebrow mb-3">SSL Certificate</div>
                    @if($target->protocol === 'https' && $target->ssl_expires_at)
                        @php $sslDays = $target->sslExpiresInDays(); @endphp
                        <div class="text-2xl font-black {{ $sslDays <= 14 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                            {{ $sslDays >= 0 ? $sslDays.'h' : 'Expired' }}
                        </div>
                        <div class="text-xs mt-1" style="color: var(--md-muted)">{{ $target->ssl_expires_at->format('d M Y') }}</div>
                    @else
                        <div class="text-sm" style="color: var(--md-muted)">N/A</div>
                    @endif
                </div>
            </div>

            <div class="surface-card p-6">
                <h3 class="text-sm font-bold mb-4" style="color: var(--md-text)">Uptime Persentase</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach(['24h' => $uptimeStats['24h'], '7d' => $uptimeStats['7d'], '30d' => $uptimeStats['30d']] as $label => $val)
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-2">
                                <span style="color: var(--md-muted)">{{ $label }}</span>
                                <span class="{{ $val >= 99 ? 'text-emerald-600 dark:text-emerald-400' : ($val >= 95 ? 'text-yellow-600 dark:text-yellow-300' : 'text-red-600 dark:text-red-400') }}">{{ number_format($val, 2) }}%</span>
                            </div>
                            <div class="h-2.5 rounded-full overflow-hidden" style="background: var(--md-surface-soft)">
                                <div class="h-full rounded-full {{ $val >= 99 ? 'bg-emerald-500' : ($val >= 95 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                     style="width: {{ $val }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="surface-card p-6">
                <h3 class="text-sm font-bold mb-4" style="color: var(--md-text)">Grafik Response Time (24 Jam Terakhir)</h3>
                <div id="responseTimeChart"></div>
            </div>

            <div class="surface-card overflow-hidden">
                <div class="px-6 py-4 border-b" style="border-color: var(--md-border)">
                    <h3 class="text-sm font-bold" style="color: var(--md-text)">Riwayat Incident</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="monitor-table">
                        <thead>
                            <tr>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Durasi</th>
                                <th>Trigger</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incidents as $incident)
                                <tr>
                                    <td>{{ $incident->started_at->format('d M Y H:i') }}</td>
                                    <td>
                                        {{ $incident->resolved_at ? $incident->resolved_at->format('d M Y H:i') : '-' }}
                                        @if($incident->isOngoing())
                                            <span class="status-badge status-badge-down ml-2">Berlangsung</span>
                                        @endif
                                    </td>
                                    <td>{{ $incident->durationHuman() }}</td>
                                    <td>
                                        <span class="status-badge {{ $incident->trigger_status === 'down' ? 'status-badge-down' : 'status-badge-warning' }}">
                                            {{ ucfirst($incident->trigger_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-sm" style="color: var(--md-muted)">Tidak ada incident tercatat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <a href="{{ route('targets.edit', $target->id) }}" class="md-button">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Target
                </a>
                <form action="{{ route('targets.pause', $target->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="md-button md-button-secondary w-full sm:w-auto">
                        @if($target->is_paused)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                            Resume Monitoring
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6"/></svg>
                            Jeda Monitoring
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const rawData = @json($chartData);
        const cssVar = (name) => getComputedStyle(document.documentElement).getPropertyValue(name).trim();
        const chartTheme = () => ({
            foreColor: cssVar('--md-muted'),
            borderColor: cssVar('--md-border'),
            textColor: cssVar('--md-text'),
        });

        const theme = chartTheme();
        const chart = new ApexCharts(document.querySelector('#responseTimeChart'), {
            series: [{
                name: 'Response Time',
                data: rawData.map(d => ({ x: d.x, y: d.y }))
            }],
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                zoom: { enabled: false },
                foreColor: theme.foreColor,
                background: 'transparent'
            },
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.32, opacityTo: 0.04 } },
            colors: ['#fbbc04'],
            xaxis: { type: 'datetime', labels: { format: 'HH:mm' } },
            yaxis: { labels: { formatter: v => v ? v.toFixed(0) + ' ms' : '0 ms' } },
            tooltip: { theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light', x: { format: 'dd MMM HH:mm' }, y: { formatter: v => v + ' ms' } },
            grid: { borderColor: theme.borderColor, strokeDashArray: 4 },
            markers: { size: 0 },
            dataLabels: { enabled: false },
        });

        chart.render();

        window.addEventListener('theme-scheduled', () => {
            const next = chartTheme();
            chart.updateOptions({
                chart: { foreColor: next.foreColor },
                grid: { borderColor: next.borderColor },
                tooltip: { theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
            });
        });
    </script>
</x-app-layout>
