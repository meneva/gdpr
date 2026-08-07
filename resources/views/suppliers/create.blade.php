<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Register a supplier
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-label for="name" value="Supplier name" />
                        <x-input id="name" name="name" type="text" class="mt-1 block w-full"
                                 :value="old('name')" placeholder="e.g. Acme Cloud Ltd" required autofocus />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="category" value="Category" />
                        <x-input id="category" name="category" type="text" class="mt-1 block w-full"
                                 :value="old('category')" placeholder="e.g. Cloud hosting, Payroll, Recruitment" />
                        <x-input-error for="category" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
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

                        <div>
                            <x-label for="last_reviewed_at" value="Last reviewed" />
                            <x-input id="last_reviewed_at" name="last_reviewed_at" type="date" class="mt-1 block w-full"
                                     :value="old('last_reviewed_at')" />
                            <x-input-error for="last_reviewed_at" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input id="dpa_on_file" name="dpa_on_file" type="checkbox" value="1"
                               @checked(old('dpa_on_file'))
                               class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                        <x-label for="dpa_on_file" value="Data Processing Agreement (DPA) is on file" class="!mb-0" />
                    </div>

                    <div>
                        <x-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"
                                  placeholder="Contract details, contact person, anything worth flagging">{{ old('notes') }}</textarea>
                        <x-input-error for="notes" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-button>Save supplier</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
