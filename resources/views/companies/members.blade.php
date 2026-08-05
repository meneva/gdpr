<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $company->name }} — Members
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('status'))
                <div class="rounded-md bg-teal-50 border border-teal-200 px-4 py-3 text-sm text-teal-800 break-words">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Invite a teammate</h3>
                <form method="POST" action="{{ route('invitations.store') }}" class="flex gap-3 items-end flex-wrap">
                    @csrf
                    <div class="flex-1 min-w-[220px]">
                        <x-label for="email" value="Email" />
                        <x-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-label for="role" value="Role" />
                        <select id="role" name="role"
                                class="mt-1 block border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm">
                            <option value="admin">Admin</option>
                            <option value="editor" selected>Editor</option>
                            <option value="viewer">Viewer</option>
                        </select>
                    </div>
                    <x-button>Send invite</x-button>
                </form>
            </div>

            @if ($invitations->isNotEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Pending invitations</h3>
                    <ul class="divide-y divide-gray-100">
                        @foreach ($invitations as $invitation)
                            <li class="py-3 flex items-center justify-between text-sm gap-3">
                                <div>
                                    <span class="font-medium text-gray-900">{{ $invitation->email }}</span>
                                    <span class="text-gray-500"> — {{ ucfirst($invitation->role) }}</span>
                                    @if ($invitation->isExpired())
                                        <x-status-stamp tone="red">Expired</x-status-stamp>
                                    @else
                                        <span class="text-xs text-gray-400 font-mono">expires {{ $invitation->expires_at->format('d M Y') }}</span>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('invitations.destroy', $invitation) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800 text-sm">Revoke</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Members</h3>
                <ul class="divide-y divide-gray-100">
                    @foreach ($members as $member)
                        <li class="py-3 flex items-center justify-between text-sm">
                            <div>
                                <span class="font-medium text-gray-900">{{ $member->name }}</span>
                                <span class="text-gray-500 ml-2">{{ $member->email }}</span>
                            </div>
                            <x-status-stamp tone="teal">{{ ucfirst($member->pivot->role) }}</x-status-stamp>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
