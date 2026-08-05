<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight font-mono">
                {{ $breach->ref_no }}
            </h2>
            <form method="POST" action="{{ route('breaches.destroy', $breach) }}"
                  onsubmit="return confirm('Delete this incident record? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">Delete</x-danger-button>
            </form>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <form method="POST" action="{{ route('breaches.update', $breach) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-label for="title" value="What happened" />
                        <x-input id="title" name="title" type="text" class="mt-1 block w-full"
                                 :value="old('title', $breach->title)" required />
                        <x-input-error for="title" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="severity" value="Severity" />
                            <select id="severity" name="severity"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $optionLabel)
                                    <option value="{{ $value }}" @selected(old('severity', $breach->severity) === $value)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="severity" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="status" value="Status" />
                            <select id="status" name="status"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                @foreach (['assessing' => 'Assessing', 'notified' => 'ICO notified', 'resolved' => 'Resolved'] as $value => $optionLabel)
                                    <option value="{{ $value }}" @selected(old('status', $breach->status) === $value)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="status" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">{{ old('description', $breach->description) }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div class="text-xs text-gray-500 font-mono">
                        Discovered {{ $breach->discovered_at->format('d M Y H:i') }}
                        &middot; ICO deadline {{ $breach->notify_deadline_at->format('d M Y H:i') }}
                        @if ($breach->ico_notified_at)
                            &middot; ICO notified {{ $breach->ico_notified_at->format('d M Y H:i') }}
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('breaches.show', $breach) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-button>Save changes</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
