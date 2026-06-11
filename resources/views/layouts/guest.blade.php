<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <meta name="color-scheme" content="light dark">

        <script>
            (() => {
                const hour = new Date().getHours();
                const isDark = hour >= 18 || hour < 6;
                document.documentElement.classList.toggle('dark', isDark);
                document.documentElement.dataset.theme = isDark ? 'dark' : 'light';
            })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=roboto:400,500,700,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased theme-monitor">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex items-center gap-3">
                    <span class="brand-mark">
                        <x-application-logo class="w-5 h-5 fill-current" />
                    </span>
                    <span class="text-xl font-black" style="color: var(--md-text)">UptimeGuard</span>
                </a>
            </div>

            <div class="surface-card w-full sm:max-w-md mt-6 px-6 py-5 overflow-hidden">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
