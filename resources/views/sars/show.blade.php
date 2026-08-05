<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight font-mono">
                {{ $sar->ref_no }}
            </h2>
            <a href="{{ route('sars.edit', $sar) }}">
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
                $overdue = $sar->isOverdue();
                $daysLeft = $sar->daysRemaining();
                $tone = $sar->status === 'completed'
                    ? 'green'
                    : ($overdue ? 'red' : ($daysLeft <= 7 ? 'amber' : 'teal'));
                $label = $sar->status === 'completed'
                    ? 'Completed'
                    : ($overdue ? abs($daysLeft).' days over' : $daysLeft.' days left');
            @endphp

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-medium text-gray-900">{{ $sar->requester_name }}</div>
                        <div class="text-sm text-gray-500">{{ ucfirst($sar->requester_type) }}</div>
                    </div>
                    <x-status-stamp :tone="$tone">{{ $label }}</x-status-stamp>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm border-t border-gray-100 pt-4">
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Received</dt>
                        <dd class="mt-1 text-gray-900">{{ $sar->received_at->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Deadline</dt>
                        <dd class="mt-1 text-gray-900">{{ $sar->deadline_at->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Status</dt>
                        <dd class="mt-1 text-gray-900">{{ ucfirst(str_replace('_', ' ', $sar->status)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Assigned to</dt>
                        <dd class="mt-1 text-gray-900">{{ $sar->assignee?->name ?? '—' }}</dd>
                    </div>
                </dl>

                @if ($sar->notes)
                    <div class="border-t border-gray-100 pt-4">
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Notes</dt>
                        <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $sar->notes }}</dd>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('sars.index') }}" class="text-sm text-teal-700 hover:text-teal-900">&larr; Back to all requests</a>
            </div>
        </div>
    </div>
</x-app-layout>
