@props(['user'])

@php
    $companies = $user->companies;
@endphp

@if ($companies->count() > 1)
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" type="button"
                class="w-full flex items-center justify-between gap-2 px-2.5 py-2 rounded-md border border-white/15 text-sm text-ink-100 hover:bg-white/5 transition">
            <span class="truncate">{{ $user->currentCompanyRelation?->name ?? 'Select company' }}</span>
            <svg class="w-4 h-4 shrink-0 text-ink-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
            </svg>
        </button>

        <!-- Opens upward: this sits at the bottom of the sidebar, so a
             downward panel would run off the bottom of the viewport. -->
        <div x-show="open" @click.away="open = false" x-cloak
             class="absolute bottom-full mb-2 left-0 w-full rounded-md shadow-lg bg-white border border-gray-200 py-1 z-50 max-h-64 overflow-y-auto">
            @foreach ($companies as $company)
                <form method="POST" action="{{ route('companies.switch', $company) }}">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 {{ session('current_company_id') == $company->id ? 'font-semibold text-teal-700' : 'text-gray-700' }}">
                        {{ $company->name }}
                    </button>
                </form>
            @endforeach
        </div>
    </div>
@endif
