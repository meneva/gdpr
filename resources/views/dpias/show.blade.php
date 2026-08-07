<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight font-mono">
                {{ $dpia->ref_no }}
            </h2>
            <a href="{{ route('dpias.edit', $dpia) }}">
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
                $closed = in_array($dpia->status, ['approved', 'rejected']);
                $daysLeft = $dpia->daysRemaining();
                $overdue = $dpia->isOverdue();
                $tone = match (true) {
                    $dpia->status === 'approved' => 'green',
                    $dpia->status === 'rejected' => 'grey',
                    $overdue => 'red',
                    $daysLeft <= 7 => 'amber',
                    default => 'teal',
                };
                $label = match (true) {
                    $dpia->status === 'approved' => 'Approved',
                    $dpia->status === 'rejected' => 'Rejected',
                    $overdue => abs($daysLeft).' days over',
                    default => $daysLeft.' days left',
                };
            @endphp

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-medium text-gray-900">{{ $dpia->project_name }}</div>
                        <div class="text-sm text-gray-500">{{ $dpia->owner_name ?? 'Unassigned owner' }}</div>
                    </div>
                    <x-status-stamp :tone="$tone">{{ $label }}</x-status-stamp>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm border-t border-gray-100 pt-4">
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Risk level</dt>
                        <dd class="mt-1 text-gray-900">{{ ucfirst($dpia->risk_level) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Review due</dt>
                        <dd class="mt-1 text-gray-900">{{ $dpia->due_at->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Status</dt>
                        <dd class="mt-1 text-gray-900">{{ ucfirst(str_replace('_', ' ', $dpia->status)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Approved</dt>
                        <dd class="mt-1 text-gray-900">{{ $dpia->approved_at?->format('d M Y H:i') ?? '—' }}</dd>
                    </div>
                </dl>

                @if ($dpia->description)
                    <div class="border-t border-gray-100 pt-4">
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Description</dt>
                        <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $dpia->description }}</dd>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('dpias.index') }}" class="text-sm text-teal-700 hover:text-teal-900">&larr; Back to all assessments</a>
            </div>
        </div>
    </div>
</x-app-layout>
