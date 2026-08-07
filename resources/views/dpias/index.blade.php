<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Data Protection Impact Assessments
            </h2>
            <a href="{{ route('dpias.create') }}">
                <x-button>Start a DPIA</x-button>
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
                @if ($dpias->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        <p class="text-lg font-medium text-gray-700">No assessments yet</p>
                        <p class="mt-1 text-sm">Start a DPIA for any new project involving personal data.</p>
                        <a href="{{ route('dpias.create') }}" class="inline-block mt-4 text-sm font-medium text-teal-700 hover:text-teal-900">
                            Start the first one &rarr;
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Project</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Risk</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Review due</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($dpias as $dpia)
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
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('dpias.show', $dpia) }}" class="font-mono text-sm text-teal-700 hover:text-teal-900">
                                                {{ $dpia->ref_no }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $dpia->project_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $dpia->owner_name ?? 'Unassigned owner' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ucfirst($dpia->risk_level) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ $dpia->due_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-status-stamp :tone="$tone">{{ $label }}</x-status-stamp>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <a href="{{ route('dpias.edit', $dpia) }}" class="text-teal-700 hover:text-teal-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $dpias->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
