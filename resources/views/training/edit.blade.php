<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ink-900 leading-tight">
                Edit course
            </h2>
            <form method="POST" action="{{ route('training-courses.destroy', $course) }}"
                  onsubmit="return confirm('Delete this course and its entire roster? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">Delete course</x-danger-button>
            </form>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-ink-100 p-6">
                <form method="POST" action="{{ route('training-courses.update', $course) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-label for="name" value="Course name" />
                        <x-input id="name" name="name" type="text" class="mt-1 block w-full"
                                 :value="old('name', $course->name)" required />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 block w-full border-ink-200 focus:border-ink-500 focus:ring-ink-500 rounded-md shadow-sm text-sm">{{ old('description', $course->description) }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('training-courses.show', $course) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <x-button>Save changes</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
