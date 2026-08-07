{{--
    Not a real file — a patch snippet.

    Open resources/views/navigation-menu.blade.php and add these links
    alongside the existing nav items, in both the desktop and mobile
    sections. This is the FULL set — all five compliance modules plus
    Members, the complete roadmap.
--}}

{{-- Desktop nav links --}}
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

<x-nav-link :href="route('companies.members')" :active="request()->routeIs('companies.members')">
    {{ __('Members') }}
</x-nav-link>

{{-- Mobile nav links --}}
<x-responsive-nav-link :href="route('sars.index')" :active="request()->routeIs('sars.*')">
    {{ __('Subject Access Requests') }}
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('breaches.index')" :active="request()->routeIs('breaches.*')">
    {{ __('Breaches & Incidents') }}
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('dpias.index')" :active="request()->routeIs('dpias.*')">
    {{ __('DPIAs') }}
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
    {{ __('Suppliers') }}
</x-responsive-nav-link>

<x-responsive-nav-link :href="route('companies.members')" :active="request()->routeIs('companies.members')">
    {{ __('Members') }}
</x-responsive-nav-link>

{{-- Company switcher: place near the right-hand side of the desktop nav --}}
@auth
    <x-company-switcher :user="auth()->user()" />
@endauth
