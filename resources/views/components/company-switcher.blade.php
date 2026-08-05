@props(['user'])

@php
    $companies = $user->companies;
@endphp

@if ($companies->count() > 1)
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" type="button"
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
            {{ $user->currentCompanyRelation?->name ?? 'Select company' }}
            <svg class="ml-1.5 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" @click.away="open = false" x-cloak
             class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white border border-gray-200 py-1">
            @foreach ($companies as $company)
                <form method="POST" action="{{ route('companies.switch', $company) }}">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 {{ session('current_company_id') == $company->id ? 'font-semibold text-teal-700' : 'text-gray-700' }}">
                        {{ $company->name }}
                    </button>
                </form>
            @endforeach
        </div>
    </div>
@endif
