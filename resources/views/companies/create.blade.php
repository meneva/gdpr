<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create your company
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-md bg-teal-50 border border-teal-200 px-4 py-3 text-sm text-teal-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <p class="text-sm text-gray-600 mb-6">
                    You're not a member of any company yet. Create one to start tracking GDPR
                    compliance — you'll be the owner, and can invite teammates once it's set up.
                </p>

                <form method="POST" action="{{ route('companies.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-label for="name" value="Company name" />
                        <x-input id="name" name="name" type="text" class="mt-1 block w-full"
                                 :value="old('name')" required autofocus />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="industry" value="Industry (optional)" />
                        <x-input id="industry" name="industry" type="text" class="mt-1 block w-full"
                                 :value="old('industry')" placeholder="e.g. Retail, Education, SaaS" />
                        <x-input-error for="industry" class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <x-button>Create company</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
