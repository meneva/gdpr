<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Breaches &amp; Incidents
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('breaches.export.csv') }}" class="text-xs font-mono uppercase tracking-wide text-ink-500 hover:text-ink-900">CSV</a>
                <a href="{{ route('breaches.export.pdf') }}" class="text-xs font-mono uppercase tracking-wide text-ink-500 hover:text-ink-900">PDF</a>
                <a href="{{ route('breaches.create') }}">
                    <x-button>Report incident</x-button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-md bg-teal-50 border border-teal-200 px-4 py-3 text-sm text-teal-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                @if ($breaches->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        <p class="text-lg font-medium text-gray-700">No incidents logged</p>
                        <p class="mt-1 text-sm">Report an incident to start the 72-hour ICO notification clock.</p>
                        <a href="{{ route('breaches.create') }}" class="inline-block mt-4 text-sm font-medium text-teal-700 hover:text-teal-900">
                            Report the first incident &rarr;
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Incident</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Discovered</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">72h window</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($breaches as $breach)
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
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('breaches.show', $breach) }}" class="font-mono text-sm text-teal-700 hover:text-teal-900">
                                                {{ $breach->ref_no }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $breach->title }}</div>
                                            <div class="text-xs text-gray-500">{{ ucfirst($breach->severity) }} severity</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ $breach->discovered_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ $resolved ? '—' : $breach->notify_deadline_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-status-stamp :tone="$tone">{{ $label }}</x-status-stamp>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <a href="{{ route('breaches.edit', $breach) }}" class="text-teal-700 hover:text-teal-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $breaches->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
