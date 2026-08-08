<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ink-900 leading-tight font-mono">
                {{ $activity->ref_no }}
            </h2>
            <form method="POST" action="{{ route('processing-activities.destroy', $activity) }}"
                  onsubmit="return confirm('Delete this activity record? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">Delete</x-danger-button>
            </form>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-ink-100 p-6">
                <form method="POST" action="{{ route('processing-activities.update', $activity) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-label for="name" value="Activity name" />
                        <x-input id="name" name="name" type="text" class="mt-1 block w-full"
                                 :value="old('name', $activity->name)" required />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="purpose" value="Purpose" />
                        <textarea id="purpose" name="purpose" rows="2"
                                  class="mt-1 block w-full border-ink-200 focus:border-ink-500 focus:ring-ink-500 rounded-md shadow-sm text-sm">{{ old('purpose', $activity->purpose) }}</textarea>
                        <x-input-error for="purpose" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="legal_basis" value="Legal basis" />
                            <x-input id="legal_basis" name="legal_basis" type="text" class="mt-1 block w-full"
                                     :value="old('legal_basis', $activity->legal_basis)" />
                            <x-input-error for="legal_basis" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="retention_period" value="Retention period" />
                            <x-input id="retention_period" name="retention_period" type="text" class="mt-1 block w-full"
                                     :value="old('retention_period', $activity->retention_period)" />
                            <x-input-error for="retention_period" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-label for="data_categories" value="Data categories" />
                        <textarea id="data_categories" name="data_categories" rows="2"
                                  class="mt-1 block w-full border-ink-200 focus:border-ink-500 focus:ring-ink-500 rounded-md shadow-sm text-sm">{{ old('data_categories', $activity->data_categories) }}</textarea>
                        <x-input-error for="data_categories" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="third_parties_involved" value="Third parties involved" />
                        <textarea id="third_parties_involved" name="third_parties_involved" rows="2"
                                  class="mt-1 block w-full border-ink-200 focus:border-ink-500 focus:ring-ink-500 rounded-md shadow-sm text-sm">{{ old('third_parties_involved', $activity->third_parties_involved) }}</textarea>
                        <x-input-error for="third_parties_involved" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="owner_name" value="Owning team / person" />
                        <x-input id="owner_name" name="owner_name" type="text" class="mt-1 block w-full"
                                 :value="old('owner_name', $activity->owner_name)" />
                        <x-input-error for="owner_name" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('processing-activities.show', $activity) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-button>Save changes</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
