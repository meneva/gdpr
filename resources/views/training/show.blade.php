<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ink-900 leading-tight">
                {{ $course->name }}
            </h2>
            <a href="{{ route('training-courses.edit', $course) }}">
                <x-secondary-button>Edit course</x-secondary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="rounded-md bg-teal-50 border border-teal-200 px-4 py-3 text-sm text-teal-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($course->description)
                <div class="bg-white border border-ink-100 rounded-lg p-5 text-sm text-gray-700">
                    {{ $course->description }}
                </div>
            @endif

            @php
                $total = $completions->count();
                $done = $completions->filter->isCompleted()->count();
                $pct = $total > 0 ? (int) round(100 * $done / $total) : 0;
                $barTone = $pct >= 90 ? 'bg-teal-600' : ($pct >= 60 ? 'bg-amber-500' : 'bg-red-500');
            @endphp

            <div class="bg-white border border-ink-100 rounded-lg p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-mono text-[11px] uppercase tracking-wide text-ink-400">Completion</span>
                    <span class="font-mono text-sm text-ink-900">{{ $done }} / {{ $total }} &middot; {{ $pct }}%</span>
                </div>
                <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                    <div class="h-full {{ $barTone }}" style="width: {{ $pct }}%"></div>
                </div>
            </div>

            <!-- Add staff to roster -->
            <div class="bg-white border border-ink-100 rounded-lg p-5">
                <h3 class="font-serif text-sm font-semibold text-ink-900 mb-3">Add to roster</h3>
                <form method="POST" action="{{ route('training-completions.store', $course) }}" class="flex flex-wrap items-end gap-3">
                    @csrf
                    <div class="flex-1 min-w-[180px]">
                        <x-label for="staff_name" value="Staff name" />
                        <x-input id="staff_name" name="staff_name" type="text" class="mt-1 block w-full" required />
                        <x-input-error for="staff_name" class="mt-2" />
                    </div>
                    <div class="flex-1 min-w-[180px]">
                        <x-label for="staff_email" value="Email (optional)" />
                        <x-input id="staff_email" name="staff_email" type="email" class="mt-1 block w-full" />
                        <x-input-error for="staff_email" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="due_at" value="Due (optional)" />
                        <x-input id="due_at" name="due_at" type="date" class="mt-1 block w-full" />
                        <x-input-error for="due_at" class="mt-2" />
                    </div>
                    <x-button>Add</x-button>
                </form>
            </div>

            <!-- Roster -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-ink-100">
                @if ($completions->isEmpty())
                    <div class="p-10 text-center text-gray-500 text-sm">
                        No one's on the roster yet — add staff above.
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Staff</th>
                                <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Due</th>
                                <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($completions as $completion)
                                @php
                                    $tone = $completion->isCompleted() ? 'green' : ($completion->isOverdue() ? 'red' : 'grey');
                                    $label = $completion->isCompleted()
                                        ? 'Completed '.$completion->completed_at->format('d M Y')
                                        : ($completion->isOverdue() ? 'Overdue' : 'Pending');
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $completion->staff_name }}</div>
                                        @if ($completion->staff_email)
                                            <div class="text-xs text-gray-500">{{ $completion->staff_email }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                        {{ $completion->due_at?->format('d M Y') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-status-stamp :tone="$tone">{{ $label }}</x-status-stamp>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-3">
                                        <form method="POST" action="{{ route('training-completions.toggle', $completion) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-teal-700 hover:text-teal-900">
                                                {{ $completion->isCompleted() ? 'Mark incomplete' : 'Mark complete' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('training-completions.destroy', $completion) }}" class="inline"
                                              onsubmit="return confirm('Remove {{ $completion->staff_name }} from this roster?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div>
                <a href="{{ route('training-courses.index') }}" class="text-sm text-teal-700 hover:text-teal-900">&larr; Back to all courses</a>
            </div>
        </div>
    </div>
</x-app-layout>
