<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Suppliers &amp; Processors
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('suppliers.export.csv') }}" class="text-xs font-mono uppercase tracking-wide text-ink-500 hover:text-ink-900">CSV</a>
                <a href="{{ route('suppliers.export.pdf') }}" class="text-xs font-mono uppercase tracking-wide text-ink-500 hover:text-ink-900">PDF</a>
                <a href="{{ route('suppliers.create') }}">
                    <x-button>Register supplier</x-button>
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
                @if ($suppliers->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        <p class="text-lg font-medium text-gray-700">No suppliers registered</p>
                        <p class="mt-1 text-sm">Add a third party who processes personal data on your behalf.</p>
                        <a href="{{ route('suppliers.create') }}" class="inline-block mt-4 text-sm font-medium text-teal-700 hover:text-teal-900">
                            Register the first one &rarr;
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Supplier</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">DPA</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Last reviewed</th>
                                    <th class="px-6 py-3 text-left text-xs font-mono uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($suppliers as $supplier)
                                    @php
                                        $tone = ! $supplier->dpa_on_file
                                            ? 'red'
                                            : ($supplier->risk_level === 'high' ? 'amber' : 'green');
                                        $label = ! $supplier->dpa_on_file
                                            ? 'No DPA on file'
                                            : ($supplier->risk_level === 'high' ? 'High risk' : 'In order');
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('suppliers.show', $supplier) }}" class="font-mono text-sm text-teal-700 hover:text-teal-900">
                                                {{ $supplier->ref_no }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $supplier->category ?? 'Uncategorised' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $supplier->dpa_on_file ? 'On file' : 'Missing' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ $supplier->last_reviewed_at?->format('d M Y') ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-status-stamp :tone="$tone">{{ $label }}</x-status-stamp>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <a href="{{ route('suppliers.edit', $supplier) }}" class="text-teal-700 hover:text-teal-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $suppliers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
