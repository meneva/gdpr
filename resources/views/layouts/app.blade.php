<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Register') }}</title>

    <!-- Fonts: Fraunces (display), Inter (body), IBM Plex Mono (reference numbers, dates, labels) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind (CDN build) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Full 50–900 ramp — a previous partial version of this
                        // scale (missing 200/400/600) was the cause of nav text
                        // rendering invisible: an undefined shade like `ink-200`
                        // silently produces no color at all rather than an error.
                        ink: {
                            50: '#EEF1F6', 100: '#D7DEEA', 200: '#B7C3DA', 300: '#8C9BBE',
                            400: '#5E71A0', 500: '#3C4E76', 600: '#2A3A5C', 700: '#22315B',
                            800: '#182749', 900: '#16233F',
                        },
                        parchment: '#F6F4EE',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        serif: ['Fraunces', 'ui-serif', 'Georgia', 'serif'],
                        mono: ['"IBM Plex Mono"', 'ui-monospace', 'SFMono-Regular', 'monospace'],
                    },
                },
            },
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* The page-header wrapper sets font-family: serif on itself; every
           module view's <h2> inside the {{ $header }} slot inherits it via
           the normal CSS cascade — no per-view changes needed. */
        .page-header h2 { letter-spacing: -0.01em; }
    </style>

    @livewireStyles
</head>
<body class="font-sans antialiased bg-parchment text-ink-900">
    <x-banner />

    <div class="min-h-screen flex flex-col sm:flex-row">
        @livewire('navigation-menu')

        <div class="flex-1 min-w-0 flex flex-col">
            @if (isset($header))
                <header class="page-header font-serif bg-white border-b border-ink-100">
                    <div class="max-w-6xl mx-auto py-7 px-4 sm:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-1">
                {{ $slot }}
            </main>

            <footer class="max-w-6xl mx-auto w-full px-4 sm:px-8 py-8 mt-6">
                <p class="text-xs font-mono uppercase tracking-wide text-ink-300">
                    {{ config('app.name', 'Register') }} &middot; GDPR Compliance Register
                </p>
            </footer>
        </div>
    </div>

    @stack('modals')

    @livewireScripts
</body>
</html>
