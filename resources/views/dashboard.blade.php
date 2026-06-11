<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="eyebrow">Command Center</p>
                <h2 class="text-2xl font-black leading-tight" style="color: var(--md-text)">Monitoring Dashboard</h2>
            </div>
            <a href="/status/{{ auth()->user()->statusPageSlug() }}" target="_blank"
               class="md-button md-button-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Halaman Status Publik
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="surface-card flex items-center gap-3 p-4 text-sm font-medium" style="color: var(--md-success)">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="surface-card flex items-center gap-3 p-4 text-sm font-medium" style="color: var(--md-danger)">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="surface-card p-4 text-sm" style="color: var(--md-danger)">
                    <div class="font-bold mb-2">Form belum bisa disimpan.</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="metric-card p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="eyebrow">Total</div>
                        <div class="metric-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/></svg>
                        </div>
                    </div>
                    <div class="summary-total text-3xl font-black" style="color: var(--md-text)">{{ $targets->count() }}</div>
                    <div class="text-xs mt-1" style="color: var(--md-muted)">Target dipantau</div>
                </div>

                <div class="metric-card p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="eyebrow">Online</div>
                        <div class="status-orb"><span class="status-dot status-up"></span></div>
                    </div>
                    <div class="summary-up text-3xl font-black" style="color: var(--md-success)">{{ $targets->where('status','up')->count() }}</div>
                    <div class="text-xs mt-1" style="color: var(--md-muted)">Normal</div>
                </div>

                <div class="metric-card p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="eyebrow">Down</div>
                        <div class="status-orb"><span class="status-dot status-down"></span></div>
                    </div>
                    <div class="summary-down text-3xl font-black" style="color: var(--md-danger)">{{ $targets->where('status','down')->count() }}</div>
                    <div class="text-xs mt-1" style="color: var(--md-muted)">Butuh cek</div>
                </div>

                <div class="metric-card p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="eyebrow">Unstable</div>
                        <div class="status-orb"><span class="status-dot status-warning"></span></div>
                    </div>
                    <div class="summary-unstable text-3xl font-black" style="color: var(--md-primary)">{{ $targets->whereIn('status',['unstable','unknown'])->count() }}</div>
                    <div class="text-xs mt-1" style="color: var(--md-muted)">Perlu pantau</div>
                </div>

                <div class="metric-card p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="eyebrow">Dijeda</div>
                        <div class="metric-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="summary-paused text-3xl font-black" style="color: var(--md-text)">{{ $targets->where('is_paused', true)->count() }}</div>
                    <div class="text-xs mt-1" style="color: var(--md-muted)">Pause</div>
                </div>
            </div>

            <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }" class="surface-card overflow-hidden">
                <div class="p-5 border-b flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between" style="border-color: var(--md-border)">
                    <div>
                        <h3 class="text-base font-bold" style="color: var(--md-text)">Daftar Target</h3>
                        <p class="text-sm" style="color: var(--md-muted)">Ringkasan status, response time, uptime, dan SSL.</p>
                    </div>
                    <button @click="open = !open" class="md-button">
                        <svg x-show="!open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/></svg>
                        <svg x-show="open" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/></svg>
                        <span x-text="open ? 'Tutup Form' : 'Tambah Target'"></span>
                    </button>
                </div>

                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="p-6 border-b" style="border-color: var(--md-border); background: var(--md-surface-soft)">
                    <form action="{{ route('targets.store') }}" method="POST">
                        @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Nama Target</label>
                                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Server Production" class="monitor-input w-full text-sm">
                                @error('name')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Protocol</label>
                                <select name="protocol" class="monitor-select w-full text-sm">
                                    <option value="https" @selected(old('protocol', 'https') === 'https')>HTTPS</option>
                                    <option value="http" @selected(old('protocol') === 'http')>HTTP</option>
                                    <option value="tcp" @selected(old('protocol') === 'tcp')>TCP (Port)</option>
                                </select>
                                @error('protocol')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Host (Domain / IP)</label>
                                <input type="text" name="host" required value="{{ old('host') }}" placeholder="myapp.com atau https://myapp.com" class="monitor-input w-full text-sm">
                                @error('host')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Port <span class="font-normal">(Opsional)</span></label>
                                <input type="number" name="port" value="{{ old('port') }}" placeholder="443" class="monitor-input w-full text-sm">
                                @error('port')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Interval Cek <span class="font-normal">(Detik)</span></label>
                                <input type="number" name="check_interval" value="{{ old('check_interval', 300) }}" required class="monitor-input w-full text-sm">
                                @error('check_interval')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Timeout <span class="font-normal">(Detik)</span></label>
                                <input type="number" name="timeout" value="{{ old('timeout', 5) }}" required class="monitor-input w-full text-sm">
                                @error('timeout')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Alert Threshold</label>
                                <input type="number" name="alert_threshold" value="{{ old('alert_threshold', 3) }}" min="1" max="10" class="monitor-input w-full text-sm">
                                @error('alert_threshold')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1" style="color: var(--md-text-soft)">Grup <span class="font-normal">(Opsional)</span></label>
                                <input type="text" name="group_name" value="{{ old('group_name') }}" placeholder="Production" class="monitor-input w-full text-sm">
                                @error('group_name')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-5 flex justify-end">
                            <button type="submit" class="md-button">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Target
                            </button>
                        </div>
                    </form>
                </div>

                <div>
                    @forelse($targets as $target)
                        <div id="target-row-{{ $target->id }}" class="p-4 sm:p-5 border-t transition-colors {{ $target->is_paused ? 'opacity-60' : '' }}" style="border-color: var(--md-border)">
                            <div class="grid gap-4 lg:grid-cols-[minmax(340px,1.45fr)_minmax(110px,.55fr)_minmax(110px,.55fr)_minmax(110px,.55fr)_minmax(140px,.65fr)_auto] lg:items-center">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="shrink-0 status-col">
                                        @if($target->status === 'up')
                                            <div class="status-orb h-11 w-11"><span class="status-dot status-up"></span></div>
                                        @elseif($target->status === 'down')
                                            <div class="status-orb h-11 w-11" title="Down ({{ $target->consecutive_failures }}x)"><span class="status-dot status-down animate-pulse"></span></div>
                                        @elseif($target->status === 'unstable')
                                            <div class="status-orb h-11 w-11"><span class="status-dot status-warning"></span></div>
                                        @else
                                            <div class="status-orb h-11 w-11"><span class="status-dot status-muted"></span></div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 space-y-2">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h4 class="truncate text-[1.05rem] font-black leading-tight" style="color: var(--md-text)">{{ $target->name }}</h4>
                                            @if($target->is_paused)
                                                <span class="status-badge">Dijeda</span>
                                            @endif
                                            @if($target->group_name)
                                                <span class="status-badge status-badge-warning">{{ strtoupper($target->group_name) }}</span>
                                            @endif
                                        </div>
                                        <div class="inline-flex max-w-full items-center gap-2 rounded-full border px-3 py-1 text-xs sm:text-sm" style="border-color: var(--md-border); background: var(--md-surface); color: var(--md-muted)">
                                            <span class="shrink-0 font-black uppercase" style="color: var(--md-text-soft)">{{ $target->protocol }}</span>
                                            <span class="truncate font-medium tabular-nums">{{ $target->host }}{{ $target->port ? ':'.$target->port : '' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 lg:contents">
                                    <div>
                                        <div class="eyebrow mb-1">Response</div>
                                        <div class="response-col">
                                            @if($target->last_response_time)
                                                @php $rt = $target->last_response_time; @endphp
                                                <span class="text-sm font-black {{ $rt < 200 ? 'text-emerald-600 dark:text-emerald-400' : ($rt < 800 ? 'text-yellow-600 dark:text-yellow-300' : 'text-red-600 dark:text-red-400') }}">
                                                    {{ number_format($rt) }} ms
                                                </span>
                                            @else
                                                <span class="text-sm font-medium" style="color: var(--md-muted)">-</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <div class="eyebrow mb-1">Uptime 7d</div>
                                        <div class="uptime-col">
                                            @if($target->uptime_7d !== null)
                                                @php $u = $target->uptime_7d; @endphp
                                                <span class="text-sm font-black {{ $u >= 99 ? 'text-emerald-600 dark:text-emerald-400' : ($u >= 95 ? 'text-yellow-600 dark:text-yellow-300' : 'text-red-600 dark:text-red-400') }}">
                                                    {{ number_format($u, 2) }}%
                                                </span>
                                            @else
                                                <span class="text-xs italic" style="color: var(--md-muted)">Menghitung...</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <div class="eyebrow mb-1">SSL Cert</div>
                                        @if($target->protocol === 'https' && $target->ssl_expires_at)
                                            @php $sslDays = $target->sslExpiresInDays(); @endphp
                                            @if($sslDays < 0)
                                                <span class="text-sm font-black text-red-600 dark:text-red-400">Expired</span>
                                            @elseif($sslDays <= 14)
                                                <span class="text-sm font-black text-yellow-600 dark:text-yellow-300">{{ $sslDays }} hari</span>
                                            @else
                                                <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">{{ $sslDays }} hari</span>
                                            @endif
                                        @elseif($target->protocol === 'https')
                                            <span class="text-xs italic" style="color: var(--md-muted)">Menunggu...</span>
                                        @else
                                            <span class="text-sm font-medium" style="color: var(--md-muted)">-</span>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="eyebrow mb-1">Terakhir Cek</div>
                                        <div class="text-xs font-medium checked-col" style="color: var(--md-muted)">
                                            {{ $target->last_checked_at ? $target->last_checked_at->diffForHumans() : 'Belum pernah' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-start gap-2 lg:justify-end">
                                    <a href="{{ route('targets.show', $target->id) }}" title="Detail & Grafik" class="md-icon-button">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                                    </a>

                                    <a href="{{ route('targets.edit', $target->id) }}" title="Edit Target" class="md-icon-button">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form action="{{ route('targets.pause', $target->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="{{ $target->is_paused ? 'Resume' : 'Jeda' }}" class="md-icon-button">
                                            @if($target->is_paused)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </button>
                                    </form>

                                    <form action="{{ route('targets.destroy', $target->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus target ini? Semua log akan ikut terhapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus" class="md-icon-button text-red-600 dark:text-red-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <div class="brand-mark mx-auto mb-4">
                                <x-application-logo class="w-5 h-5" />
                            </div>
                            <h3 class="text-lg font-black mb-1" style="color: var(--md-text)">Belum Ada Target</h3>
                            <p class="text-sm" style="color: var(--md-muted)">Klik tombol Tambah Target untuk mulai memonitor server Anda.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        window.Echo.private('App.Models.User.{{ auth()->id() }}')
            .listen('.TargetPinged', (e) => {
                const row = document.getElementById('target-row-' + e.id);
                if (!row) return;

                row.style.transition = 'background-color 0.3s';
                row.style.backgroundColor = 'rgba(251, 188, 4, 0.12)';
                setTimeout(() => row.style.backgroundColor = '', 1000);

                const statusCol = row.querySelector('.status-col');
                const statusMap = {
                    up: `<div class="status-orb"><span class="status-dot status-up"></span></div>`,
                    down: `<div class="status-orb" title="Down (${e.consecutive_failures}x)"><span class="status-dot status-down animate-pulse"></span></div>`,
                    unstable: `<div class="status-orb"><span class="status-dot status-warning"></span></div>`,
                    unknown: `<div class="status-orb"><span class="status-dot status-muted"></span></div>`,
                };
                if (statusMap[e.status]) statusCol.innerHTML = statusMap[e.status];

                const rt = e.last_response_time;
                const color = rt < 200 ? 'text-emerald-600 dark:text-emerald-400' : (rt < 800 ? 'text-yellow-600 dark:text-yellow-300' : 'text-red-600 dark:text-red-400');
                row.querySelector('.response-col').innerHTML = `<span class="text-sm font-black ${color}">${rt.toLocaleString()} ms</span>`;

                const uptime = e.uptime_7d;
                if (uptime !== null && uptime !== undefined) {
                    const uptimeColor = uptime >= 99 ? 'text-emerald-600 dark:text-emerald-400' : (uptime >= 95 ? 'text-yellow-600 dark:text-yellow-300' : 'text-red-600 dark:text-red-400');
                    row.querySelector('.uptime-col').innerHTML = `<span class="text-sm font-black ${uptimeColor}">${Number(uptime).toFixed(2)}%</span>`;
                }

                row.querySelector('.checked-col').textContent = e.last_checked_at;

                if (e.summary) {
                    document.querySelector('.summary-total').textContent = e.summary.total;
                    document.querySelector('.summary-up').textContent = e.summary.up;
                    document.querySelector('.summary-down').textContent = e.summary.down;
                    document.querySelector('.summary-unstable').textContent = e.summary.unstable;
                    document.querySelector('.summary-paused').textContent = e.summary.paused;
                }
            });
    </script>
</x-app-layout>
