<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight font-mono">
                {{ $sar->ref_no }}
            </h2>
            <form method="POST" action="{{ route('sars.destroy', $sar) }}"
                  onsubmit="return confirm('Delete this request? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">Delete</x-danger-button>
            </form>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <form method="POST" action="{{ route('sars.update', $sar) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-label for="requester_name" value="Requester name" />
                        <x-input id="requester_name" name="requester_name" type="text" class="mt-1 block w-full"
                                 :value="old('requester_name', $sar->requester_name)" required />
                        <x-input-error for="requester_name" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="requester_type" value="Requester type" />
                            <select id="requester_type" name="requester_type"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                @foreach (['customer' => 'Customer', 'employee' => 'Employee', 'applicant' => 'Job applicant', 'other' => 'Other'] as $value => $optionLabel)
                                    <option value="{{ $value }}" @selected(old('requester_type', $sar->requester_type) === $value)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="requester_type" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="status" value="Status" />
                            <select id="status" name="status"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                @foreach (['received' => 'Received', 'verifying' => 'Verifying identity', 'in_progress' => 'In progress', 'completed' => 'Completed'] as $value => $optionLabel)
                                    <option value="{{ $value }}" @selected(old('status', $sar->status) === $value)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="status" class="mt-2" />
                        </div>
                    </div>

                    @if ($assignees->isNotEmpty())
                        <div>
                            <x-label for="assigned_to" value="Assign to" />
                            <select id="assigned_to" name="assigned_to"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                <option value="">Unassigned</option>
                                @foreach ($assignees as $assignee)
                                    <option value="{{ $assignee->id }}" @selected(old('assigned_to', $sar->assigned_to) == $assignee->id)>
                                        {{ $assignee->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="assigned_to" class="mt-2" />
                        </div>
                    @endif

                    <div>
                        <x-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4"
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">{{ old('notes', $sar->notes) }}</textarea>
                        <x-input-error for="notes" class="mt-2" />
                    </div>

                    <div class="text-xs text-gray-500 font-mono">
                        Received {{ $sar->received_at->format('d M Y') }} &middot; Deadline {{ $sar->deadline_at->format('d M Y') }}
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('sars.show', $sar) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-button>Save changes</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
