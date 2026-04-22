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
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Purchase History</h2>
        @if($orders->count() > 0)
            <button onclick="openClearHistoryModal()" class="rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100 transition">
                Clear History
            </button>
        @endif
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-md bg-success/10 text-success px-4 py-3 flex items-start gap-3" data-alert>
            <div class="flex-1">{{ session('success') }}</div>
            <button type="button" class="ml-auto text-success hover:opacity-70" data-alert-close>✕</button>
        </div>
    @endif
    @if (session('popup'))
        <div class="mt-4 rounded-md bg-success/10 text-success px-4 py-3 flex items-start gap-3" data-alert>
            <div class="flex-1">{{ session('popup') }}</div>
            <button type="button" class="ml-auto text-success hover:opacity-70" data-alert-close>✕</button>
        </div>
    @endif
    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-soft">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Order</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-secondary">Total</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-secondary">Date & Time</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($orders as $o)
                    <tr>
                        <td class="px-4 py-3">{{ $o->order_number }}</td>
                        <td class="px-4 py-3">
                            @if (!$o->paid_at)
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Wait for payment</span>
                            @elseif (!$o->completed_at)
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">Waiting delivery</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Completed</span>
                            @endif
                            @if ($o->status === 'cancelled')
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $o->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('purchases.receipt', $o) }}" class="rounded-full border border-gray-300 px-3 py-2 hover:bg-gray-50 text-sm">Receipt</a>
                            <a href="{{ route('chat.show', $o) }}" class="rounded-full border border-gray-300 px-3 py-2 hover:bg-gray-50 text-sm">Chat</a>
                            @if (!$o->paid_at)
                                <form action="{{ route('purchases.pay', $o) }}" method="POST">
                                    @csrf
                                    <button class="rounded-full bg-primary text-white px-3 py-2 text-sm hover:bg-primary-hover">I have paid</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            No purchase history found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $orders->links() }}</div>
    </div>

    <!-- Clear History Confirmation Modal -->
    <div id="clearHistoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeClearHistoryModal()"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                <form action="{{ route('purchases.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Clear Purchase History</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to clear your purchase history? This action will hide all completed and cancelled orders from your view. Active orders will remain visible.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Clear History
                        </button>
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeClearHistoryModal()">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openClearHistoryModal() {
            document.getElementById('clearHistoryModal').classList.remove('hidden');
        }

        function closeClearHistoryModal() {
            document.getElementById('clearHistoryModal').classList.add('hidden');
        }
    </script>
</section>
@endsection
