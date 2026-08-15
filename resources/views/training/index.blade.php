<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ink-900 leading-tight">
                Staff Training
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('training-courses.export.csv') }}" class="text-xs font-mono uppercase tracking-wide text-ink-500 hover:text-ink-900">CSV</a>
                <a href="{{ route('training-courses.export.pdf') }}" class="text-xs font-mono uppercase tracking-wide text-ink-500 hover:text-ink-900">PDF</a>
                <a href="{{ route('training-courses.create') }}">
                    <x-button>New course</x-button>
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

            @if ($courses->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-ink-100">
                    <div class="p-12 text-center text-gray-500">
                        <p class="text-lg font-medium text-ink-900">No training courses yet</p>
                        <p class="mt-1 text-sm">Add a course, then build a staff roster and track who's completed it.</p>
                        <a href="{{ route('training-courses.create') }}" class="inline-block mt-4 text-sm font-medium text-teal-700 hover:text-teal-900">
                            Add the first course &rarr;
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($courses as $course)
                        @php
                            $pct = $course->completionPercent();
                            $barTone = $pct >= 90 ? 'bg-teal-600' : ($pct >= 60 ? 'bg-amber-500' : 'bg-red-500');
                        @endphp
                        <a href="{{ route('training-courses.show', $course) }}" class="block bg-white border border-ink-100 rounded-lg p-5 hover:border-ink-300 transition">
                            <div class="text-sm font-medium text-gray-900">{{ $course->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $course->completions_count }} staff assigned</div>

                            <div class="mt-4 flex items-center gap-3">
                                <div class="flex-1 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full {{ $barTone }}" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="font-mono text-xs text-gray-500">{{ $pct }}%</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
