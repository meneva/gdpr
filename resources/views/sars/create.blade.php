<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Log a subject access request
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <form method="POST" action="{{ route('sars.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-label for="requester_name" value="Requester name" />
                        <x-input id="requester_name" name="requester_name" type="text" class="mt-1 block w-full"
                                 :value="old('requester_name')" required autofocus />
                        <x-input-error for="requester_name" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label for="requester_type" value="Requester type" />
                            <select id="requester_type" name="requester_type"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                @foreach (['customer' => 'Customer', 'employee' => 'Employee', 'applicant' => 'Job applicant', 'other' => 'Other'] as $value => $optionLabel)
                                    <option value="{{ $value }}" @selected(old('requester_type') === $value)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="requester_type" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="received_at" value="Date received" />
                            <x-input id="received_at" name="received_at" type="date" class="mt-1 block w-full"
                                     :value="old('received_at', now()->toDateString())" />
                            <x-input-error for="received_at" class="mt-2" />
                        </div>
                    </div>

                    @if ($assignees->isNotEmpty())
                        <div>
                            <x-label for="assigned_to" value="Assign to" />
                            <select id="assigned_to" name="assigned_to"
                                    class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                                <option value="">Unassigned</option>
                                @foreach ($assignees as $assignee)
                                    <option value="{{ $assignee->id }}" @selected(old('assigned_to') == $assignee->id)>
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
                                  class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm"
                                  placeholder="What's being requested, and from which systems?">{{ old('notes') }}</textarea>
                        <x-input-error for="notes" class="mt-2" />
                    </div>

                    <p class="text-xs text-gray-500 font-mono">
                        The 30-day statutory deadline is calculated automatically from the date received.
                    </p>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('sars.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-button>Save request</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
