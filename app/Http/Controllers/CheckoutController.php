<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('home')->with('error', 'Admin cannot checkout.');
        }

        $cart = app(CartController::class)->currentCart()->load('items.product');
        if ($cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('warning', 'Cart empty, please fill it with our products');
        }
        return view('checkout.index', compact('cart'));
    }

    public function place(Request $request)
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('home')->with('error', 'Admin cannot checkout.');
        }

        $cart = app(CartController::class)->currentCart()->load('items.product');
        if ($cart->items->isEmpty()) {
            return redirect()->route('products.index')->with('warning', 'Cart empty, please fill it with our products');
        }

        // Validate stock before processing
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with('error', 'Stok untuk produk ' . $item->product->name . ' tidak mencukupi. Sisa stok: ' . $item->product->stock);
            }
        }

        $user = auth()->user();

        $subtotal = $cart->items->sum('total_price');
        $tax = round($subtotal * 0.1, 2); // 10% tax
        $total = $subtotal + $tax;

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . random_int(100, 999),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'wait_for_payment',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,
            'customer_address' => null, // Address skipped as per requirement
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
            ]);
            $item->product->decrement('stock', $item->quantity);
        }

        $cart->items()->delete();

        return redirect()->route('checkout.payment', $order)->with('success', 'Pesanan berhasil dibuat, silakan lanjutkan pembayaran');
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        if ($order->status !== 'wait_for_payment') {
            return redirect()->route('purchases.index')->with('info', 'Order status is ' . $order->status);
        }
        return view('checkout.payment', compact('order'));
    }

    public function pay(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        if (!$order->paid_at) {
            $order->paid_at = now();
        }
        if ($order->status === 'wait_for_payment') {
            $order->status = 'payment_verified';
        }
        $order->save();
        
        return redirect()->route('checkout.success', $order)->with('success', 'Pembayaran berhasil dikonfirmasi');
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }
}
