<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>UptimeGuard - Monitoring Dashboard</title>
    <script>
        (() => {
            const hour = new Date().getHours();
            const isDark = hour >= 18 || hour < 6;
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.dataset.theme = isDark ? 'dark' : 'light';
        })();
    </script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=roboto:400,500,700,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased theme-monitor">
    <div class="min-h-screen">
        <nav class="material-topbar">
            <div class="max-w-7xl mx-auto px-6 h-16 flex justify-between items-center">
                <a href="/" class="flex items-center gap-3">
                    <span class="brand-mark">
                        <x-application-logo class="w-5 h-5" />
                    </span>
                    <span class="text-xl font-black text-white">UptimeGuard</span>
                </a>
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="md-button">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="md-button md-button-secondary">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="md-button">Sign Up</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 py-12 lg:py-16">
            <section class="grid lg:grid-cols-[0.92fr_1.08fr] gap-10 items-center min-h-[calc(100vh-8rem)]">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border px-4 py-2 mb-6 text-sm font-bold" style="border-color: var(--md-border); background: var(--md-surface); color: var(--md-text-soft)">
                        <span class="status-dot status-up"></span>
                        Monitoring engine active
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black leading-tight" style="color: var(--md-text)">
                        UptimeGuard
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-8" style="color: var(--md-text-soft)">
                        Dashboard monitoring server dengan status real-time, laporan uptime, SSL tracker, dan notifikasi Telegram/Discord untuk tim operasional.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="md-button">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16m-7-7l7 7-7 7"/></svg>
                                Masuk ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="md-button">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/></svg>
                                Mulai Pantau
                            </a>
                            <a href="{{ route('login') }}" class="md-button md-button-secondary">Log In</a>
                        @endauth
                    </div>
                </div>

                <div class="surface-card p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="eyebrow">Live Overview</p>
                            <h2 class="text-xl font-black" style="color: var(--md-text)">Production Cluster</h2>
                        </div>
                        <span class="status-badge status-badge-up">All systems operational</span>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
                        <div class="surface-card-soft p-4">
                            <div class="eyebrow mb-1">Targets</div>
                            <div class="text-2xl font-black" style="color: var(--md-text)">24</div>
                        </div>
                        <div class="surface-card-soft p-4">
                            <div class="eyebrow mb-1">Online</div>
                            <div class="text-2xl font-black" style="color: var(--md-success)">23</div>
                        </div>
                        <div class="surface-card-soft p-4">
                            <div class="eyebrow mb-1">Down</div>
                            <div class="text-2xl font-black" style="color: var(--md-danger)">1</div>
                        </div>
                        <div class="surface-card-soft p-4">
                            <div class="eyebrow mb-1">Avg</div>
                            <div class="text-2xl font-black" style="color: var(--md-text)">142ms</div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach([
                            ['name' => 'API Gateway', 'host' => 'api.example.com', 'status' => 'up', 'rt' => '91 ms'],
                            ['name' => 'Billing Worker', 'host' => '10.10.4.24:443', 'status' => 'warning', 'rt' => '612 ms'],
                            ['name' => 'Public Website', 'host' => 'www.example.com', 'status' => 'up', 'rt' => '128 ms'],
                        ] as $item)
                            <div class="flex items-center justify-between rounded-lg border p-4" style="border-color: var(--md-border); background: var(--md-surface)">
                                <div class="flex items-center gap-3">
                                    <span class="status-dot {{ $item['status'] === 'up' ? 'status-up' : 'status-warning' }}"></span>
                                    <div>
                                        <div class="text-sm font-black" style="color: var(--md-text)">{{ $item['name'] }}</div>
                                        <div class="text-xs" style="color: var(--md-muted)">{{ $item['host'] }}</div>
                                    </div>
                                </div>
                                <div class="text-sm font-black" style="color: var(--md-text-soft)">{{ $item['rt'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
