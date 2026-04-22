@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-4xl px-6 py-12">
    <div class="mb-6">
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-text-secondary hover:text-primary dark:hover:text-blue-400 transition-colors font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
        
        <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/50 dark:bg-gray-800/50">
            <div>
                <h1 class="text-2xl font-bold text-text-primary dark:text-white">Order Details</h1>
                <p class="text-sm text-text-secondary dark:text-gray-400 mt-1">
                    Order ID: <span class="font-semibold text-primary dark:text-blue-400">#{{ $order->order_number }}</span>
                </p>
            </div>
            
            <div class="px-4 py-2 rounded-full text-sm font-bold shadow-sm
                @if($order->status == 'completed') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                @elseif($order->status == 'cancelled') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </div>
        </div>

        <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold mb-2">Informasi Pembelian</h3>
                <p class="text-text-primary dark:text-gray-200 text-sm mb-1"><span class="text-gray-500 dark:text-gray-400 inline-block w-24">Tanggal</span> : {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                <p class="text-text-primary dark:text-gray-200 text-sm"><span class="text-gray-500 dark:text-gray-400 inline-block w-24">Pembayaran</span> : Transfer Bank / E-Wallet</p>
            </div>
            <div>
                <h3 class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold mb-2">Informasi Akun</h3>
                <p class="text-text-primary dark:text-gray-200 text-sm mb-1"><span class="text-gray-500 dark:text-gray-400 inline-block w-24">Nama</span> : {{ auth()->user()->username }}</p>
                <p class="text-text-primary dark:text-gray-200 text-sm"><span class="text-gray-500 dark:text-gray-400 inline-block w-24">Email</span> : {{ auth()->user()->email }}</p>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <h3 class="text-lg font-bold text-text-primary dark:text-white mb-4">Ringkasan Pesanan</h3>
            <div class="space-y-4">
                
                @foreach($order->items as $item)
                <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30 hover:shadow-sm transition-shadow">
                    <div class="flex items-center gap-4">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200 dark:border-gray-600" alt="Product">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-2xl">📦</div>
                        @endif
                        
                        <div>
                            <h4 class="font-bold text-text-primary dark:text-white text-sm md:text-base">{{ $item->product->name }}</h4>
                            <p class="text-xs md:text-sm text-text-secondary dark:text-gray-400 mt-1">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                        </div>
                    </div>
                    <div class="font-bold text-primary dark:text-blue-400">
                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach

            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-col items-end gap-2">
                <div class="flex justify-between w-full md:w-1/2 text-sm text-gray-500 dark:text-gray-400">
                    <span>Subtotal Produk</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between w-full md:w-1/2 text-sm text-gray-500 dark:text-gray-400">
                    <span>Biaya Layanan</span>
                    <span>Rp 0</span>
                </div>
                <div class="flex justify-between w-full md:w-1/2 text-xl font-black text-text-primary dark:text-white mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                    <span>Total Pembayaran</span>
                    <span class="text-primary dark:text-blue-400">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

<div class="p-6 bg-blue-50 border-t flex flex-col md:flex-row justify-between items-center gap-4">
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
        <div class="text-sm text-gray-500">Menu khusus Admin/Owner:</div>
        <a href="{{ route('purchases.receipt', $order) }}" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-full transition shadow-soft">
            🖨️ Print Official Receipt
        </a>
    @else
        <div class="text-sm text-gray-500">Ada masalah dengan pesanan ini?</div>
        <a href="{{ route('chat.show', $order) }}" class="px-6 py-2.5 bg-primary hover:bg-primary-hover text-white font-bold rounded-full transition shadow-soft">
            💬 Hubungi Penjual
        </a>
    @endif
</div>
    </div>
</section>
@endsection