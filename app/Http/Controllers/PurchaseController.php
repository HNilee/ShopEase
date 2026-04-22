<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Menampilkan halaman detail pesanan (Receipt / Struk) untuk User
     */
    public function show(Order $order)
    {
        // Di dalam purchases/show.blade.php
        @if(auth()->user()->role === 'buyer')
            <a href="{{ route('chat.show', $order) }}" class="...">Hubungi Penjual</a>
        @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
            <a href="{{ route('purchases.receipt', $order) }}" class="...">Print Official Receipt</a>
        @endif
    }
}
}