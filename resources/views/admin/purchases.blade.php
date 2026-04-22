@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-6 py-12">
    <div class="mb-6">
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>
    <h2 class="text-2xl font-semibold">Purchases</h2>
    @if (session('popup'))
        <div class="mt-4 rounded-md bg-success/10 text-success px-4 py-3 flex items-start gap-3" data-alert>
            <div class="flex-1">{{ session('popup') }}</div>
            <button type="button" class="ml-auto text-success hover:opacity-70" data-alert-close>✕</button>
        </div>
    @endif
    <div class="mt-6 overflow-x-auto rounded-xl bg-white shadow-soft">
        <div class="min-w-full inline-block align-middle">
            <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($orders as $o)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $o->order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $o->customer_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                            {{ $o->status === 'completed' ? 'bg-green-100 text-green-800' : 
                               ($o->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                               ($o->status === 'wait_for_payment' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                {{ ucfirst(str_replace('_', ' ', $o->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $o->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $o) }}" class="text-primary hover:text-primary-hover">View Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $orders->links() }}</div>
    </div>
</section>
@endsection
