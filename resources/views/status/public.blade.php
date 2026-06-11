<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>Status Page - {{ $user->name }}</title>
    <script>
        (() => {
            const hour = new Date().getHours();
            const isDark = hour >= 18 || hour < 6;
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.dataset.theme = isDark ? 'dark' : 'light';
        })();
    </script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=roboto:400,500,700,900&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased theme-monitor">
    <div class="min-h-screen">
        <header class="material-header">
            <div class="max-w-4xl mx-auto px-6 py-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <span class="brand-mark">
                            <x-application-logo class="w-5 h-5" />
                        </span>
                        <div>
                            <p class="eyebrow">Public Status</p>
                            <h1 class="text-2xl font-black" style="color: var(--md-text)">{{ $user->name }} Status Page</h1>
                        </div>
                    </div>
                    <div class="text-sm sm:text-right" style="color: var(--md-muted)">
                        {{ now()->format('d M Y H:i') }} WIB
                    </div>
                </div>

                <div class="surface-card-soft mt-6 p-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <div class="flex items-center gap-3">
                            <span class="status-dot
                                {{ $operationalMessage['level'] === 'up' ? 'status-up' :
                                   ($operationalMessage['level'] === 'down' ? 'status-down animate-pulse' : 'status-warning') }}">
                            </span>
                            <span class="font-black text-sm
                                {{ $operationalMessage['level'] === 'up' ? 'text-emerald-600 dark:text-emerald-400' :
                                   ($operationalMessage['level'] === 'down' ? 'text-red-600 dark:text-red-400' : 'text-yellow-700 dark:text-yellow-300') }}">
                                {{ $operationalMessage['text'] }}
                            </span>
                        </div>
                        <div class="sm:ml-auto flex flex-wrap items-center gap-2 text-xs">
                            <span class="status-badge status-badge-up">{{ $stats['up'] }} Online</span>
                            @if($stats['down'] > 0)<span class="status-badge status-badge-down">{{ $stats['down'] }} Down</span>@endif
                            @if($stats['unstable'] > 0)<span class="status-badge status-badge-warning">{{ $stats['unstable'] }} Unstable</span>@endif
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-6 py-8 space-y-6">
            @foreach($groups as $groupName => $groupTargets)
                <section class="surface-card overflow-hidden">
                    <div class="px-6 py-3 border-b" style="border-color: var(--md-border); background: var(--md-surface-soft)">
                        <h3 class="eyebrow">{{ $groupName }}</h3>
                    </div>
                    <div>
                        @foreach($groupTargets as $target)
                            <div class="px-6 py-4 border-t flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" style="border-color: var(--md-border)">
                                <div class="flex items-center gap-3">
                                    <span class="status-dot
                                        {{ $target->status === 'up' ? 'status-up' :
                                           ($target->status === 'down' ? 'status-down animate-pulse' :
                                           ($target->status === 'unstable' ? 'status-warning' : 'status-muted')) }}">
                                    </span>
                                    <div>
                                        <div class="text-sm font-black" style="color: var(--md-text)">{{ $target->name }}</div>
                                        <div class="text-xs" style="color: var(--md-muted)">{{ $target->host }}</div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-4 sm:justify-end sm:text-right">
                                    @if($target->uptime_30d !== null)
                                        <div>
                                            <div class="text-xs" style="color: var(--md-muted)">Uptime 30d</div>
                                            <div class="text-sm font-black {{ $target->uptime_30d >= 99 ? 'text-emerald-600 dark:text-emerald-400' : ($target->uptime_30d >= 95 ? 'text-yellow-600 dark:text-yellow-300' : 'text-red-600 dark:text-red-400') }}">
                                                {{ number_format($target->uptime_30d, 2) }}%
                                            </div>
                                        </div>
                                    @endif
                                    @if($target->last_response_time)
                                        <div>
                                            <div class="text-xs" style="color: var(--md-muted)">Response</div>
                                            <div class="text-sm font-black" style="color: var(--md-text-soft)">{{ number_format($target->last_response_time) }} ms</div>
                                        </div>
                                    @endif
                                    <div>
                                        @if($target->status === 'up')
                                            <span class="status-badge status-badge-up">Operational</span>
                                        @elseif($target->status === 'down')
                                            <span class="status-badge status-badge-down">Down</span>
                                        @elseif($target->status === 'unstable')
                                            <span class="status-badge status-badge-warning">Gangguan</span>
                                        @else
                                            <span class="status-badge">Unknown</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach

            @if($targets->isEmpty())
                <div class="surface-card p-12 text-center text-sm" style="color: var(--md-muted)">Belum ada layanan yang dipantau.</div>
            @endif

            <p class="text-center text-xs pt-4" style="color: var(--md-muted)">
                Powered by <strong>UptimeGuard</strong> - Halaman ini bersifat publik
            </p>
        </main>
    </div>
</body>
</html>
