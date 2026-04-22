<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected function ensureUserOwns(Order $order)
    {
        if (!auth()->check() || ($order->user_id && $order->user_id !== auth()->id())) {
            abort(403);
        }
    }

    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $orders = Order::where('user_id', auth()->id())
            ->where('hidden_by_user', false)
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('purchases.index', compact('orders'));
    }

    public function clearHistory()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Only hide completed or cancelled orders
        Order::where('user_id', auth()->id())
            ->whereIn('status', ['completed', 'cancelled'])
            ->update(['hidden_by_user' => true]);

        return back()->with('success', 'Purchase history cleared (completed and cancelled orders only).');
    }

    public function pay(Order $order)
    {
        $this->ensureUserOwns($order);
        if (!$order->paid_at) {
            $order->paid_at = now();
        }
        if ($order->status === 'wait_for_payment') {
            $order->status = 'payment_verified';
        }
        $order->save();
        return back()->with('success', 'Payment recorded');
    }

    public function receipt(Order $order)
    {
        $this->ensureUserOwns($order);
        return view('purchases.receipt', compact('order'));
    }
}
