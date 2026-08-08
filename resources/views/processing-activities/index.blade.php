<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ink-900 leading-tight">
                Processing Activities
            </h2>
            <a href="{{ route('processing-activities.create') }}">
                <x-button>Log activity</x-button>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-ink-100">
                @if ($activities->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        <p class="text-lg font-medium text-ink-900">No processing activities logged</p>
                        <p class="mt-1 text-sm">Record what personal data you process, why, and under what legal basis — your Record of Processing Activities (RoPA).</p>
                        <a href="{{ route('processing-activities.create') }}" class="inline-block mt-4 text-sm font-medium text-teal-700 hover:text-teal-900">
                            Log the first one &rarr;
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Activity</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Legal basis</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Retention</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($activities as $activity)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('processing-activities.show', $activity) }}" class="font-mono text-sm text-teal-700 hover:text-teal-900">
                                                {{ $activity->ref_no }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $activity->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $activity->owner_name ?? 'Unassigned owner' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $activity->legal_basis ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $activity->retention_period ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <a href="{{ route('processing-activities.edit', $activity) }}" class="text-teal-700 hover:text-teal-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
