<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight font-mono">
                {{ $dpia->ref_no }}
            </h2>
            <form method="POST" action="{{ route('dpias.destroy', $dpia) }}"
                  onsubmit="return confirm('Delete this assessment? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">Delete</x-danger-button>
            </form>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <form method="POST" action="{{ route('dpias.update', $dpia) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-label for="project_name" value="Project name" />
                        <x-input id="project_name" name="project_name" type="text" class="mt-1 block w-full"
                                 :value="old('project_name', $dpia->project_name)" required />
                        <x-input-error for="project_name" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="owner_name" value="Owning team / person" />
                            <x-input id="owner_name" name="owner_name" type="text" class="mt-1 block w-full"
                                     :value="old('owner_name', $dpia->owner_name)" />
                            <x-input-error for="owner_name" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="risk_level" value="Risk level" />
                            <select id="risk_level" name="risk_level"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $optionLabel)
                                    <option value="{{ $value }}" @selected(old('risk_level', $dpia->risk_level) === $value)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="risk_level" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="status" value="Status" />
                            <select id="status" name="status"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                @foreach (['draft' => 'Draft', 'in_review' => 'In review', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $optionLabel)
                                    <option value="{{ $value }}" @selected(old('status', $dpia->status) === $value)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="status" class="mt-2" />
                            <p class="mt-1 text-xs text-gray-500">Approving or rejecting requires an owner/admin role.</p>
                        </div>

                        <div>
                            <x-label for="due_at" value="Review due date" />
                            <x-input id="due_at" name="due_at" type="date" class="mt-1 block w-full"
                                     :value="old('due_at', $dpia->due_at->toDateString())" required />
                            <x-input-error for="due_at" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">{{ old('description', $dpia->description) }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    @if ($dpia->approved_at)
                        <div class="text-xs text-gray-500 font-mono">
                            Approved {{ $dpia->approved_at->format('d M Y H:i') }}
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('dpias.show', $dpia) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-button>Save changes</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
