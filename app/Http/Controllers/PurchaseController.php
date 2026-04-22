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
        return view('purchases.show', compact('order'));
    }
}