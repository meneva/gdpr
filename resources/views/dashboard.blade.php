<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-ink-900 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('status'))
                <div class="rounded-md bg-teal-50 border border-teal-200 px-4 py-3 text-sm text-teal-800">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Score panel -->
            <div class="bg-ink-900 rounded-lg px-7 py-6 flex items-center gap-7 flex-wrap">
                <div class="relative w-20 h-20 shrink-0 rounded-full"
                     style="background: conic-gradient(#2dd4bf {{ $score }}%, rgba(255,255,255,0.12) 0)">
                    <div class="absolute inset-[6px] rounded-full bg-ink-900 flex items-center justify-center">
                        <span class="font-serif text-xl font-semibold text-white">{{ $score }}</span>
                    </div>
                </div>
                <div class="max-w-xl">
                    <h3 class="font-serif text-white text-base font-semibold mb-1">Overall compliance standing</h3>
                    <p class="text-ink-200 text-sm leading-relaxed">
                        Weighted from open SARs, unresolved incidents, pending DPIAs, and supplier gaps.
                        Treat this as a prompt to act, not a certificate.
                    </p>
                </div>
            </div>

            <!-- Aggregate cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('sars.index') }}" class="block bg-white border border-ink-100 rounded-lg p-5 hover:border-ink-300 transition">
                    <div class="font-mono text-[11px] uppercase tracking-wide text-ink-400">Subject Requests</div>
                    <div class="font-serif text-3xl font-semibold text-ink-900 mt-1">{{ $openSarsCount }}</div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $overdueSars > 0 ? $overdueSars.' overdue' : 'none overdue' }}
                    </div>
                </a>

                <a href="{{ route('breaches.index') }}" class="block bg-white border border-ink-100 rounded-lg p-5 hover:border-ink-300 transition">
                    <div class="font-mono text-[11px] uppercase tracking-wide text-ink-400">Incidents Open</div>
                    <div class="font-serif text-3xl font-semibold text-ink-900 mt-1">{{ $openBreachesCount }}</div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $urgentBreaches > 0 ? $urgentBreaches.' nearing 72h' : 'none nearing deadline' }}
                    </div>
                </a>

                <a href="{{ route('dpias.index') }}" class="block bg-white border border-ink-100 rounded-lg p-5 hover:border-ink-300 transition">
                    <div class="font-mono text-[11px] uppercase tracking-wide text-ink-400">DPIAs Pending</div>
                    <div class="font-serif text-3xl font-semibold text-ink-900 mt-1">{{ $pendingDpias }}</div>
                    <div class="text-xs text-gray-500 mt-1">draft or in review</div>
                </a>

                <a href="{{ route('suppliers.index') }}" class="block bg-white border border-ink-100 rounded-lg p-5 hover:border-ink-300 transition">
                    <div class="font-mono text-[11px] uppercase tracking-wide text-ink-400">Supplier Gaps</div>
                    <div class="font-serif text-3xl font-semibold text-ink-900 mt-1">{{ $supplierGaps }}</div>
                    <div class="text-xs text-gray-500 mt-1">missing DPA or high risk</div>
                </a>

                <a href="{{ route('processing-activities.index') }}" class="block bg-white border border-ink-100 rounded-lg p-5 hover:border-ink-300 transition">
                    <div class="font-mono text-[11px] uppercase tracking-wide text-ink-400">Processing Activities</div>
                    <div class="font-serif text-3xl font-semibold text-ink-900 mt-1">{{ $ropaCount }}</div>
                    <div class="text-xs text-gray-500 mt-1">logged in your RoPA</div>
                </a>

                <a href="{{ route('training-courses.index') }}" class="block bg-white border border-ink-100 rounded-lg p-5 hover:border-ink-300 transition">
                    <div class="font-mono text-[11px] uppercase tracking-wide text-ink-400">Training Completion</div>
                    <div class="font-serif text-3xl font-semibold text-ink-900 mt-1">{{ $trainingPct !== null ? $trainingPct.'%' : '—' }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $trainingPct !== null ? 'across all courses' : 'no roster yet' }}</div>
                </a>
            </div>

            <!-- Quick actions -->
            <div>
                <h3 class="font-serif text-sm font-semibold text-ink-900 mb-3 pb-2 border-b border-ink-100">
                    Log something new
                </h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('sars.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-ink-200 rounded-md font-mono text-xs text-ink-700 uppercase tracking-widest shadow-sm hover:bg-parchment transition">
                        New Request
                    </a>
                    <a href="{{ route('breaches.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-ink-200 rounded-md font-mono text-xs text-ink-700 uppercase tracking-widest shadow-sm hover:bg-parchment transition">
                        Report Incident
                    </a>
                    <a href="{{ route('dpias.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-ink-200 rounded-md font-mono text-xs text-ink-700 uppercase tracking-widest shadow-sm hover:bg-parchment transition">
                        Start DPIA
                    </a>
                    <a href="{{ route('suppliers.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-ink-200 rounded-md font-mono text-xs text-ink-700 uppercase tracking-widest shadow-sm hover:bg-parchment transition">
                        Register Supplier
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
