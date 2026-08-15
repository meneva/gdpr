<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink-900 leading-tight">
            New training course
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-ink-100 p-6">
                <form method="POST" action="{{ route('training-courses.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-label for="name" value="Course name" />
                        <x-input id="name" name="name" type="text" class="mt-1 block w-full"
                                 :value="old('name')" placeholder="e.g. GDPR Fundamentals" required autofocus />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 block w-full border-ink-200 focus:border-ink-500 focus:ring-ink-500 rounded-md shadow-sm text-sm"
                                  placeholder="What does this course cover, and who needs to take it?">{{ old('description') }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('training-courses.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-button>Create course</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
