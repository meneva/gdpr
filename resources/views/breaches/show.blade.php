<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight font-mono">
                {{ $breach->ref_no }}
            </h2>
            <a href="{{ route('breaches.edit', $breach) }}">
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
                $resolved = $breach->status === 'resolved';
                $hoursLeft = $breach->hoursRemaining();
                $overdue = $breach->isOverdue();
                $tone = $resolved
                    ? 'green'
                    : ($overdue ? 'red' : ($hoursLeft <= 24 ? 'red' : ($hoursLeft <= 48 ? 'amber' : 'teal')));
                $label = $resolved
                    ? 'Resolved'
                    : ($overdue ? abs($hoursLeft).'h over' : $hoursLeft.'h left');
            @endphp

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-medium text-gray-900">{{ $breach->title }}</div>
                        <div class="text-sm text-gray-500">{{ ucfirst($breach->severity) }} severity</div>
                    </div>
                    <x-status-stamp :tone="$tone">{{ $label }}</x-status-stamp>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm border-t border-gray-100 pt-4">
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Discovered</dt>
                        <dd class="mt-1 text-gray-900">{{ $breach->discovered_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">ICO deadline</dt>
                        <dd class="mt-1 text-gray-900">{{ $breach->notify_deadline_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Status</dt>
                        <dd class="mt-1 text-gray-900">{{ ucfirst($breach->status) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">ICO notified</dt>
                        <dd class="mt-1 text-gray-900">{{ $breach->ico_notified_at?->format('d M Y H:i') ?? '—' }}</dd>
                    </div>
                </dl>

                @if ($breach->description)
                    <div class="border-t border-gray-100 pt-4">
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Description</dt>
                        <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $breach->description }}</dd>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('breaches.index') }}" class="text-sm text-teal-700 hover:text-teal-900">&larr; Back to all incidents</a>
            </div>
        </div>
    </div>
</x-app-layout>
