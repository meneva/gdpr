<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Start a DPIA
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <form method="POST" action="{{ route('dpias.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-label for="project_name" value="Project name" />
                        <x-input id="project_name" name="project_name" type="text" class="mt-1 block w-full"
                                 :value="old('project_name')" placeholder="e.g. New booking system" required autofocus />
                        <x-input-error for="project_name" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="owner_name" value="Owning team / person" />
                            <x-input id="owner_name" name="owner_name" type="text" class="mt-1 block w-full"
                                     :value="old('owner_name')" placeholder="e.g. Product" />
                            <x-input-error for="owner_name" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="risk_level" value="Risk level" />
                            <select id="risk_level" name="risk_level"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                <option value="low" @selected(old('risk_level') === 'low')>Low</option>
                                <option value="medium" @selected(old('risk_level') === 'medium')>Medium</option>
                                <option value="high" @selected(old('risk_level') === 'high')>High</option>
                            </select>
                            <x-input-error for="risk_level" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-label for="due_at" value="Review due date" />
                        <x-input id="due_at" name="due_at" type="date" class="mt-1 block w-full"
                                 :value="old('due_at', now()->addWeeks(4)->toDateString())" required />
                        <x-input-error for="due_at" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"
                                  placeholder="What personal data does this project touch, and why?">{{ old('description') }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('dpias.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-button>Save assessment</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
