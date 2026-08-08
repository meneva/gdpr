<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ink-900 leading-tight font-mono">
                {{ $activity->ref_no }}
            </h2>
            <a href="{{ route('processing-activities.edit', $activity) }}">
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

            <div class="bg-white shadow-sm sm:rounded-lg border border-ink-100 p-6 space-y-5">
                <div>
                    <div class="text-lg font-medium text-gray-900">{{ $activity->name }}</div>
                    <div class="text-sm text-gray-500">{{ $activity->owner_name ?? 'Unassigned owner' }}</div>
                </div>

                @if ($activity->purpose)
                    <div class="border-t border-gray-100 pt-4">
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Purpose</dt>
                        <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $activity->purpose }}</dd>
                    </div>
                @endif

                <dl class="grid grid-cols-2 gap-4 text-sm border-t border-gray-100 pt-4">
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Legal basis</dt>
                        <dd class="mt-1 text-gray-900">{{ $activity->legal_basis ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Retention period</dt>
                        <dd class="mt-1 text-gray-900">{{ $activity->retention_period ?? '—' }}</dd>
                    </div>
                </dl>

                @if ($activity->data_categories)
                    <div class="border-t border-gray-100 pt-4">
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Data categories</dt>
                        <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $activity->data_categories }}</dd>
                    </div>
                @endif

                @if ($activity->third_parties_involved)
                    <div class="border-t border-gray-100 pt-4">
                        <dt class="text-gray-500 font-mono text-xs uppercase tracking-wide">Third parties involved</dt>
                        <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $activity->third_parties_involved }}</dd>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('processing-activities.index') }}" class="text-sm text-teal-700 hover:text-teal-900">&larr; Back to all activities</a>
            </div>
        </div>
    </div>
</x-app-layout>
