@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl px-6 py-12">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden print:shadow-none print:w-full print:max-w-none">
        <!-- Header -->
        <div class="bg-primary px-8 py-6 text-white flex justify-between items-center print:bg-white print:text-black">
            <div>
                <h1 class="text-2xl font-bold">ShopEase Receipt</h1>
                <p class="text-sm opacity-90 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="text-right">
                <div class="text-sm opacity-90">Order #</div>
                <div class="font-mono text-lg font-bold">{{ $order->order_number }}</div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="p-8">
            <div class="flex justify-between mb-8 text-sm">
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 uppercase tracking-wider text-xs font-semibold mb-2">Customer</h3>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $order->customer_name }}</p>
                    <p class="text-gray-600 dark:text-gray-300">{{ $order->customer_email }}</p>
                    <p class="text-gray-600 dark:text-gray-300">{{ $order->customer_phone }}</p>
                </div>
                <div class="text-right">
                    <h3 class="text-gray-500 dark:text-gray-400 uppercase tracking-wider text-xs font-semibold mb-2">Status</h3>
                    @php
                        $statusLabel = match ($order->status) {
                            'payment_verified' => 'Payment Success',
                            'shipping' => 'Wait for Delivery',
                            'paid_waiting_delivery' => 'Wait for Delivery',
                            'wait_for_payment' => 'Wait for Payment',
                            'cancelled' => 'Cancelled',
                            'completed' => 'Completed',
                            default => ucwords(str_replace('_', ' ', $order->status)),
                        };
                        $statusClass = match ($order->status) {
                            'payment_verified' => 'bg-green-100 text-green-800',
                            'wait_for_payment' => 'bg-yellow-100 text-yellow-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            'shipping' => 'bg-blue-100 text-blue-800',
                            'paid_waiting_delivery' => 'bg-blue-100 text-blue-800',
                            'completed' => 'bg-green-100 text-green-800',
                            default => 'bg-yellow-100 text-yellow-800',
                        };
                    @endphp
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                    @if($order->paid_at)
                        <p class="text-xs text-gray-500 mt-2">Paid: {{ $order->paid_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>

            <!-- Items -->
            <div class="border-t border-gray-200 dark:border-gray-700 py-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 text-left">
                            <th class="pb-3 font-medium">Item</th>
                            <th class="pb-3 font-medium text-center">Qty</th>
                            <th class="pb-3 font-medium text-right">Price</th>
                            <th class="pb-3 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($order->items as $item)
                            <tr class="text-gray-900 dark:text-white">
                                <td class="py-3">{{ $item->product_name }}</td>
                                <td class="py-3 text-center">{{ $item->quantity }}</td>
                                <td class="py-3 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="py-3 text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <div class="flex justify-end text-sm mb-2">
                    <div class="w-32 text-gray-500 dark:text-gray-400">Subtotal</div>
                    <div class="text-right font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                </div>
                <div class="flex justify-end text-sm mb-2">
                    <div class="w-32 text-gray-500 dark:text-gray-400">Tax (10%)</div>
                    <div class="text-right font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->tax, 0, ',', '.') }}</div>
                </div>
                <div class="flex justify-end text-lg font-bold border-t border-gray-100 dark:border-gray-700 pt-3 mt-3">
                    <div class="w-32">Total</div>
                    <div class="text-right text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Footer / Actions -->
        <div class="bg-gray-50 dark:bg-gray-900/50 px-8 py-6 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center print:hidden">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Receipt
            </button>
        </div>
    </div>
    
    <div class="mt-8 text-center text-sm text-gray-500 print:hidden">
        <p>Thank you for shopping with ShopEase!</p>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .mx-auto.max-w-2xl, .mx-auto.max-w-2xl * {
            visibility: visible;
        }
        .mx-auto.max-w-2xl {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        nav, footer, .fixed {
            display: none !important;
        }
    }
</style>
@endsection
