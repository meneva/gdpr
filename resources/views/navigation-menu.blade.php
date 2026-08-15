<div x-data="{ open: false }" class="sm:w-64 sm:shrink-0">

    <!-- Mobile top bar -->
    <div class="sm:hidden flex items-center justify-between bg-ink-900 h-14 px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <span class="w-2 h-2 rounded-full bg-white"></span>
            <span class="font-serif text-base font-semibold text-white">{{ config('app.name', 'Register') }}</span>
        </a>
        <button @click="open = true" class="text-ink-200 hover:text-white" aria-label="Open menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile backdrop -->
    <div x-show="open"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-ink-900/60 z-40 sm:hidden" x-cloak></div>

    <!-- Sidebar (static on desktop, slide-in drawer on mobile) -->
    <aside
        x-cloak
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-ink-900 flex flex-col transform transition-transform duration-200 ease-in-out sm:translate-x-0 sm:static sm:z-auto"
    >
        <div class="h-16 flex items-center justify-between px-5 border-b border-white/10 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-white"></span>
                <span class="font-serif text-base font-semibold text-white">{{ config('app.name', 'Register') }}</span>
            </a>
            <button @click="open = false" class="sm:hidden text-ink-300 hover:text-white" aria-label="Close menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>

            <x-nav-link :href="route('sars.index')" :active="request()->routeIs('sars.*')">
                {{ __('Subject Access Requests') }}
            </x-nav-link>

            <x-nav-link :href="route('breaches.index')" :active="request()->routeIs('breaches.*')">
                {{ __('Breaches & Incidents') }}
            </x-nav-link>

            <x-nav-link :href="route('dpias.index')" :active="request()->routeIs('dpias.*')">
                {{ __('DPIAs') }}
            </x-nav-link>

            <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                {{ __('Suppliers') }}
            </x-nav-link>

            <x-nav-link :href="route('processing-activities.index')" :active="request()->routeIs('processing-activities.*')">
                {{ __('Processing Activities') }}
            </x-nav-link>

            <x-nav-link :href="route('training-courses.index')" :active="request()->routeIs('training-courses.*')">
                {{ __('Staff Training') }}
            </x-nav-link>

            <x-nav-link :href="route('companies.members')" :active="request()->routeIs('companies.members')">
                {{ __('Members') }}
            </x-nav-link>
        </nav>

        <div class="border-t border-white/10 p-3 space-y-3 shrink-0">
            @auth
                <x-company-switcher :user="auth()->user()" />
            @endauth

            <div class="px-2">
                <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                <div class="text-xs font-mono text-ink-300 truncate">{{ Auth::user()->email }}</div>
            </div>

            <div class="space-y-0.5">
                <a href="{{ route('profile.show') }}" class="block px-2.5 py-1.5 rounded text-sm text-ink-200 hover:text-white hover:bg-white/5 transition">
                    {{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-2.5 py-1.5 rounded text-sm text-ink-200 hover:text-white hover:bg-white/5 transition">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>
