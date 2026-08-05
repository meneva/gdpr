<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Report an incident
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <form method="POST" action="{{ route('breaches.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-label for="title" value="What happened" />
                        <x-input id="title" name="title" type="text" class="mt-1 block w-full"
                                 :value="old('title')" placeholder="e.g. Email sent to wrong recipient" required autofocus />
                        <x-input-error for="title" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="severity" value="Severity" />
                            <select id="severity" name="severity"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                <option value="low" @selected(old('severity') === 'low')>Low</option>
                                <option value="medium" @selected(old('severity') === 'medium')>Medium</option>
                                <option value="high" @selected(old('severity') === 'high')>High</option>
                            </select>
                            <x-input-error for="severity" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="discovered_at" value="Date &amp; time discovered" />
                            <x-input id="discovered_at" name="discovered_at" type="datetime-local" class="mt-1 block w-full"
                                     :value="old('discovered_at', now()->format('Y-m-d\TH:i'))" />
                            <x-input-error for="discovered_at" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"
                                  placeholder="What data was involved, and who's affected?">{{ old('description') }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <p class="text-xs text-gray-500 font-mono">
                        The 72-hour ICO notification window is calculated automatically from the discovery time.
                    </p>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('breaches.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-button>Log incident</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
