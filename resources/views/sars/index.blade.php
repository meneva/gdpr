<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Subject Access Requests
            </h2>
            <a href="{{ route('sars.create') }}">
                <x-button>New request</x-button>
            </a>
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
                @if ($sars->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        <p class="text-lg font-medium text-gray-700">No requests logged yet</p>
                        <p class="mt-1 text-sm">Log a request to start tracking the 30-day response deadline.</p>
                        <a href="{{ route('sars.create') }}" class="inline-block mt-4 text-sm font-medium text-teal-700 hover:text-teal-900">
                            Log the first request &rarr;
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Requester</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Received</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Deadline</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($sars as $sar)
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
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('sars.show', $sar) }}" class="font-mono text-sm text-teal-700 hover:text-teal-900">
                                                {{ $sar->ref_no }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $sar->requester_name }}</div>
                                            <div class="text-xs text-gray-500">{{ ucfirst($sar->requester_type) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ $sar->received_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ $sar->status === 'completed' ? '—' : $sar->deadline_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-status-stamp :tone="$tone">{{ $label }}</x-status-stamp>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <a href="{{ route('sars.edit', $sar) }}" class="text-teal-700 hover:text-teal-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $sars->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
