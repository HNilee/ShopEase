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
        <h2 class="text-2xl font-semibold">Products</h2>
        <form method="GET" class="hidden">
            <input type="text" name="q" placeholder="Search..." class="rounded-full border border-gray-300 px-4 py-2">
        </form>
    </div>
    @if (session('warning'))
        <div class="mt-4 rounded-md bg-warning/10 text-warning px-4 py-3 flex items-start gap-3" data-alert>
            <div class="flex-1">{{ session('warning') }}</div>
            <button type="button" class="ml-auto text-warning hover:opacity-70" data-alert-close>✕</button>
        </div>
    @endif
    <div class="mt-8 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="rounded-xl bg-white shadow-soft overflow-hidden">
                <a href="{{ route('products.show', $product) }}">
                    <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=600&auto=format&fit=crop' }}" class="w-full h-40 object-cover" alt="{{ $product->name }}">
                </a>
                <div class="p-4">
                    <a href="{{ route('products.show', $product) }}" class="block font-medium">{{ $product->name }}</a>
                    <div class="mt-2">
                        @if ($product->discount_price)
                            <span class="text-primary font-semibold">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                            <span class="ml-2 text-sm text-text-secondary line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @else
                            <span class="text-primary font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    @if ($product->stock <= 0)
                        <div class="mt-4">
                            <span class="inline-block rounded-full bg-gray-200 text-gray-600 px-4 py-2">Sold</span>
                        </div>
                    @else
                        @if (auth()->check() && auth()->user()->role === 'admin')
                            <div class="mt-4">
                                <span class="inline-block rounded-full bg-gray-200 text-gray-600 px-4 py-2 text-xs">Admin View</span>
                            </div>
                        @else
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                                @csrf
                                <button class="w-full rounded-full bg-primary text-white px-4 py-2 hover:bg-primary-hover">Add to Cart</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <p class="text-text-secondary">Belum ada produk.</p>
        @endforelse
    </div>
    <div class="mt-8">
        {{ $products->links() }}
    </div>
 </section>
@endsection
