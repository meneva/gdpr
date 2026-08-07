<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight font-mono">
                {{ $supplier->ref_no }}
            </h2>
            <a href="{{ route('suppliers.edit', $supplier) }}">
                <x-secondary-button>Edit</x-secondary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-md bg-teal-50 border border-teal-200 px-4 py-3 text-sm text-teal-800">
                    {{ session('status') }}
                </div>
            @endif

            @php
                $tone = ! $supplier->dpa_on_file
                    ? 'red'
                    : ($supplier->risk_level === 'high' ? 'amber' : 'green');
                $label = ! $supplier->dpa_on_file
                    ? 'No DPA on file'
                    : ($supplier->risk_level === 'high' ? 'High risk' : 'In order');
            @endphp

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-medium text-gray-900">{{ $supplier->name }}</div>
                        <div class="text-sm text-gray-500">{{ $supplier->category ?? 'Uncategorised' }}</div>
                    </div>
                    <x-status-stamp :tone="$tone">{{ $label }}</x-status-stamp>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm border-t border-gray-100 pt-4">
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">DPA on file</dt>
                        <dd class="mt-1 text-gray-900">{{ $supplier->dpa_on_file ? 'Yes' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Risk level</dt>
                        <dd class="mt-1 text-gray-900">{{ ucfirst($supplier->risk_level) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Last reviewed</dt>
                        <dd class="mt-1 text-gray-900">{{ $supplier->last_reviewed_at?->format('d M Y') ?? '—' }}</dd>
                    </div>
                </dl>

                @if ($supplier->notes)
                    <div class="border-t border-gray-100 pt-4">
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Notes</dt>
                        <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $supplier->notes }}</dd>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('suppliers.index') }}" class="text-sm text-teal-700 hover:text-teal-900">&larr; Back to all suppliers</a>
            </div>
        </div>
    </div>
</x-app-layout>
