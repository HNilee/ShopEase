@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-6 py-12">
    <div class="mb-6">
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">User Manager</h2>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-lg bg-red-50 p-4 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-6 overflow-x-auto rounded-xl bg-white shadow-soft">
        <div class="min-w-full inline-block align-middle">
            <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Username</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Role</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">IP Address</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-text-secondary">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($users as $u)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $u->name }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-text-secondary">{{ $u->email }}</td>
                        <td class="px-4 py-3 text-sm text-text-secondary">{{ $u->username }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($u->is_blocked)
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                    Blocked
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-text-secondary">{{ $u->ip_address ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            @if($u->id !== auth()->id())
                                <div class="flex justify-end gap-2">
                                    <!-- Block Button -->
                                    <button onclick="openModal('blockModal-{{ $u->id }}')" 
                                            class="rounded-lg border border-yellow-300 bg-yellow-50 px-3 py-1 text-xs font-medium text-yellow-700 hover:bg-yellow-100">
                                        {{ $u->is_blocked ? 'Unblock' : 'Block' }}
                                    </button>

                                    <!-- Ban Button -->
                                    <button onclick="openModal('banModal-{{ $u->id }}')"
                                            class="rounded-lg border border-orange-300 bg-orange-50 px-3 py-1 text-xs font-medium text-orange-700 hover:bg-orange-100">
                                        Suspend
                                    </button>

                                    <!-- Delete Button -->
                                    <button onclick="openModal('deleteModal-{{ $u->id }}')"
                                            class="rounded-lg border border-red-300 bg-red-50 px-3 py-1 text-xs font-medium text-red-700 hover:bg-red-100">
                                        Delete
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $users->links() }}</div>
    </div>
</section>

@foreach ($users as $u)
    @if($u->id !== auth()->id())
        <!-- Block Modal -->
        <div id="blockModal-{{ $u->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('blockModal-{{ $u->id }}')"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
                <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <form action="{{ route('admin.users.block', $u) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                {{ $u->is_blocked ? 'Unblock User' : 'Block User' }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ $u->is_blocked ? 'Are you sure you want to unblock this user?' : 'Are you sure you want to block this user? They will not be able to login.' }}
                                </p>
                                @if(!$u->is_blocked)
                                    <div class="mt-4">
                                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                                        <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2" placeholder="Enter reason for blocking..."></textarea>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                                Confirm
                            </button>
                            <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal('blockModal-{{ $u->id }}')">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ban Modal -->
        <div id="banModal-{{ $u->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('banModal-{{ $u->id }}')"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
                <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <form action="{{ route('admin.users.ban', $u) }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Suspend User (IP Ban)</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to suspend this user? Their IP address ({{ $u->ip_address ?? 'Unknown' }}) will be permanently banned from accessing the site.
                                </p>
                                <div class="mt-4">
                                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                                    <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2" placeholder="Enter reason for suspension..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                                Suspend
                            </button>
                            <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal('banModal-{{ $u->id }}')">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal-{{ $u->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('deleteModal-{{ $u->id }}')"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle">&#8203;</span>
                <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <form action="{{ route('admin.users.delete', $u) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Delete User</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this user? They will be removed from the system but can register again.
                                </p>
                                <div class="mt-4">
                                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason (Optional)</label>
                                    <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2" placeholder="Enter reason..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                                Delete
                            </button>
                            <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal('deleteModal-{{ $u->id }}')">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endsection
