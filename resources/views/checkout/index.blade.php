@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-6 py-12 relative">
    <div class="mb-6">
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    <div class="flex items-center gap-4 mb-6">
        <h2 class="text-2xl font-semibold">Checkout</h2>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 rounded-xl bg-white shadow-soft p-6">
            <form id="checkoutForm" action="{{ route('checkout.place') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-text-secondary">Nama</label>
                    <input type="text" name="customer_name" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-text-secondary">Email</label>
                        <input type="email" name="customer_email" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm text-text-secondary">Telepon</label>
                        <input type="text" name="customer_phone" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-text-secondary">Alamat</label>
                    <textarea name="customer_address" rows="3" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></textarea>
                </div>
                <button type="submit" id="btnPlaceOrder" class="rounded-full bg-primary text-white px-6 py-3 hover:bg-primary-hover">Place Order</button>
            </form>
        </div>
        <div class="rounded-xl bg-white shadow-soft p-6">
            <h3 class="text-lg font-semibold">Ringkasan</h3>
            @php
                $subtotal = $cart->items->sum('total_price');
                $tax = round($subtotal * 0.1, 2);
                $total = $subtotal + $tax;
            @endphp
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Tax (10%)</span><span>Rp {{ number_format($tax, 0, ',', '.') }}</span></div>
                <div class="flex justify-between font-semibold" id="totalDisplay" data-total="{{ $total }}"><span>Total</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>
</section>
@endsection