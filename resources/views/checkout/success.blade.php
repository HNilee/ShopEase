@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-3xl px-6 py-16 text-center">
    <div class="rounded-xl bg-white shadow-soft p-8">
        <h2 class="text-2xl font-semibold text-success">Pesanan Berhasil</h2>
        <p class="mt-2 text-text-secondary">Nomor Pesanan: {{ $order->order_number }}</p>
        <div class="mt-6">
            <div class="text-lg font-semibold">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</div>
        </div>
        <div class="mt-8 flex flex-col gap-4 sm:flex-row justify-center">
            <a href="{{ route('purchases.receipt', $order) }}" class="inline-block rounded-full border border-gray-300 px-6 py-3 text-text-primary hover:bg-gray-50 transition">View Receipt</a>
            <a href="{{ route('products.index') }}" class="inline-block rounded-full bg-primary text-white px-6 py-3 hover:bg-primary-hover transition">Kembali Belanja</a>
        </div>
    </div>
</section>
@endsection
