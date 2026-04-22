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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow-soft rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Order Items</h2>
                    <span class="text-sm text-text-secondary">{{ $order->items->count() }} Items</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach ($order->items as $item)
                        <div class="p-6 flex items-start gap-4">
                            <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                <img src="{{ $item->product->image ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=200&auto=format&fit=crop' }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover object-center">
                            </div>
                            <div class="flex flex-1 flex-col">
                                <div>
                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                        <h3>{{ $item->product_name }}</h3>
                                        <p class="ml-4">Rp {{ number_format($item->total_price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-1 items-end justify-between text-sm">
                                    <p class="text-gray-500">Qty {{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="bg-gray-50 px-6 py-6 border-t border-gray-100">
                    <div class="flex justify-between text-base font-medium text-gray-900 mb-2">
                        <p>Subtotal</p>
                        <p>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex justify-between text-base font-medium text-gray-900 mb-2">
                        <p>Tax (10%)</p>
                        <p>Rp {{ number_format($order->tax, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-gray-900 mt-4 pt-4 border-t border-gray-200">
                        <p>Total</p>
                        <p>Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Management -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white shadow-soft rounded-xl overflow-hidden p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Details</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Name</p>
                        <p class="font-medium">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="font-medium">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Phone</p>
                        <p class="font-medium">{{ $order->customer_phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Address</p>
                        <p class="font-medium">{{ $order->customer_address ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Order Date</p>
                        <p class="font-medium">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-gray-100">
                     <a href="{{ route('chat.show', $order) }}" class="flex w-full justify-center items-center gap-2 rounded-full border border-primary text-primary px-4 py-2 hover:bg-primary/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                        </svg>
                        Chat with Customer
                    </a>
                </div>
            </div>

            <!-- Status Management -->
            <div class="bg-white shadow-soft rounded-xl overflow-hidden p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status</h3>
                
                @if (session('success'))
                    <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Current Status</label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                <option value="wait_for_payment" {{ $order->status === 'wait_for_payment' ? 'selected' : '' }}>Wait for Payment</option>
                                <option value="payment_verified" {{ $order->status === 'payment_verified' ? 'selected' : '' }}>Payment Verified</option>
                                <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>Shipping</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full rounded-full bg-primary text-white px-4 py-3 font-medium hover:bg-primary-hover shadow-lg shadow-primary/30 transition-all">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection