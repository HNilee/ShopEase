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

    <div class="flex items-center gap-4 mb-6">
        <h2 class="text-2xl font-semibold">Keranjang</h2>
    </div>
    @if (session('success'))
        <div class="mt-4 rounded-md bg-success/10 text-success px-4 py-3 flex items-start gap-3" data-alert>
            <div class="flex-1">{{ session('success') }}</div>
            <button type="button" class="ml-auto text-success hover:opacity-70" data-alert-close>✕</button>
        </div>
    @endif
    @if (session('warning'))
        <div class="mt-4 rounded-md bg-warning/10 text-warning px-4 py-3 flex items-start gap-3" data-alert>
            <div class="flex-1">{{ session('warning') }}</div>
            <button type="button" class="ml-auto text-warning hover:opacity-70" data-alert-close>✕</button>
        </div>
    @endif
    <div id="cartEmptyPopup" class="hidden fixed inset-0 z-50 flex items-start justify-center pt-24">
        <div class="rounded-md bg-warning/10 text-warning px-4 py-3 flex items-start gap-3 shadow-soft w-[calc(100%-48px)] max-w-2xl" data-alert>
            <div class="flex-1">Cart empty, please fill it with our products</div>
            <button type="button" class="ml-auto text-warning hover:opacity-70" data-alert-close>✕</button>
        </div>
    </div>
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            @forelse ($cart->items as $item)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between rounded-xl bg-white shadow-soft p-4 gap-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ $item->product->image ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=400&auto=format&fit=crop' }}" class="w-20 h-16 object-cover rounded-md flex-shrink-0" />
                        <div>
                            <a href="{{ route('products.show', $item->product) }}" class="font-medium line-clamp-1">{{ $item->product->name }}</a>
                            <div class="text-sm text-text-secondary">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                            @if ($item->product->stock <= 0)
                                <div class="mt-1 inline-block rounded-full bg-gray-200 text-gray-600 px-3 py-1 text-xs">Sold</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 justify-between sm:justify-end">
                        @if ($item->product->stock > 0)
                            <form action="{{ route('cart.item.update', $item) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 sm:w-20 rounded-md border border-gray-300 px-2 py-1.5 text-sm">
                                <button class="rounded-full border border-gray-300 px-3 py-1.5 hover:bg-gray-50 text-sm">Update</button>
                            </form>
                        @endif
                        <form action="{{ route('cart.item.remove', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-full bg-danger text-white px-3 py-1.5 hover:bg-danger-hover text-sm">Remove</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-text-secondary">Keranjang Anda kosong.</p>
            @endforelse
        </div>
        <div class="rounded-xl bg-white shadow-soft p-6">
            @php
                $subtotal = $cart->items->sum('total_price');
                $tax = round($subtotal * 0.1, 2);
                $total = $subtotal + $tax;
            @endphp
            <h3 class="text-lg font-semibold">Ringkasan</h3>
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Tax (10%)</span><span>Rp {{ number_format($tax, 0, ',', '.') }}</span></div>
                <div class="flex justify-between font-semibold"><span>Total</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
            </div>
            <form id="checkoutForm" action="{{ route('checkout.place') }}" method="POST">
                @csrf
                <button type="submit" id="checkoutButton" class="mt-6 inline-block w-full rounded-full bg-primary text-center text-white px-4 py-3 hover:bg-primary-hover">Checkout</button>
            </form>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('checkoutForm');
    var btn = document.getElementById('checkoutButton');
    if (!form || !btn) return;
    var count = {{ $cart->items->count() }};
    form.addEventListener('submit', function (e) {
        if (count === 0) {
            e.preventDefault();
            var pop = document.getElementById('cartEmptyPopup');
            if (pop) {
                pop.classList.remove('hidden');
                var closeBtn = pop.querySelector('[data-alert-close]');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function () {
                        pop.classList.add('hidden');
                    }, { once: true });
                }
            }
        }
    });
});
</script>
@endsection
