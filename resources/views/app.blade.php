<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        {{-- Splash screen: tampil sebelum JS dimuat, sembunyi otomatis saat halaman siap. --}}
        <div
            id="app-splash"
            style="position: fixed; inset: 0; z-index: 10000; display: flex; align-items: center; justify-content: center; background: rgb(2 6 23 / 0.45); backdrop-filter: blur(4px);"
        >
            <div
                style="display: flex; flex-direction: column; align-items: center; gap: 12px; background: #fff; border-radius: 16px; padding: 28px 36px; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.15);"
            >
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="animation: app-splash-spin 0.8s linear infinite;">
                    <circle cx="12" cy="12" r="10" stroke="#1e293b" stroke-width="4" opacity="0.25" />
                    <path d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z" fill="#1e293b" opacity="0.75" />
                </svg>
                <p style="margin: 0; font-size: 14px; font-weight: 500; color: #475569;">Memuat…</p>
            </div>
        </div>
        <style>
            @keyframes app-splash-spin { to { transform: rotate(360deg); } }
            #app-splash[hidden] { display: none; }
        </style>
        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    var splash = document.getElementById('app-splash');
                    if (splash) splash.hidden = true;
                }, 200);
            });
        </script>

        <x-inertia::app />
    </body>
</html>
