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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="rounded-xl overflow-hidden bg-white shadow-soft">
            <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=1200&auto=format&fit=crop' }}" class="w-full h-[400px] object-cover" alt="{{ $product->name }}">
        </div>
        <div>
            <h1 class="text-3xl font-semibold">{{ $product->name }}</h1>
            <p class="mt-3 text-text-secondary">{{ $product->description ?? 'No description' }}</p>
            <div class="mt-6 flex items-end gap-3">
                @if ($product->discount_price)
                    <span class="text-3xl font-bold text-primary">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                    <span class="text-text-secondary line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @else
                    <span class="text-3xl font-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>
            @if ($product->stock <= 0)
                <div class="mt-8">
                    <span class="inline-block rounded-full bg-gray-200 text-gray-600 px-4 py-2">Sold</span>
                </div>
            @else
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <div class="mt-8">
                        <span class="inline-block rounded-full bg-gray-200 text-gray-600 px-4 py-2">Admin View Only</span>
                    </div>
                @else
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-8 flex items-center gap-3">
                        @csrf
                        <input type="number" name="quantity" min="1" value="1" class="w-24 rounded-md border border-gray-300 px-3 py-2">
                        <button class="rounded-full bg-primary text-white px-6 py-3 hover:bg-primary-hover">Add to Cart</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</section>
@endsection
