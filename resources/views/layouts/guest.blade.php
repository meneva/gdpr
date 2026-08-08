<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Register') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
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

    @livewireStyles
</head>
<body class="font-sans text-ink-900 antialiased bg-parchment">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">

        <a href="/" class="mb-8 flex items-center gap-2.5">
            <span class="w-2.5 h-2.5 rounded-full bg-ink-900"></span>
            <span class="font-serif text-xl font-semibold text-ink-900">{{ config('app.name', 'Register') }}</span>
        </a>

        <div class="w-full sm:max-w-md">
            <div class="bg-white border border-ink-100 rounded-lg shadow-sm overflow-hidden">
                <div class="h-1 bg-ink-900"></div>
                <div class="px-6 py-8 sm:px-8">
                    {{ $slot }}
                </div>
            </div>

            <p class="mt-6 text-center text-xs font-mono uppercase tracking-wide text-ink-300">
                GDPR Compliance Register
            </p>
        </div>
    </div>

    @livewireScripts
</body>
</html>
