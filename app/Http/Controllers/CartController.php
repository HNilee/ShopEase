<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function currentCart(): Cart
    {
        $sessionId = session()->getId() ?: Str::uuid()->toString();
        session()->put('cart_session_id', $sessionId);

        $cart = Cart::firstOrCreate([
            'session_id' => $sessionId,
            'user_id' => auth()->id(),
        ]);

        return $cart;
    }

    public function index()
    {
        $cart = $this->currentCart()->load('items.product');
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);
        $qty = $validated['quantity'] ?? 1;

        if ($product->stock <= 0) {
            return back()->with('warning', 'Produk sudah Sold');
        }

        $cart = $this->currentCart();
        $price = (float) ($product->discount_price ?? $product->price);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        $currentQty = $item ? $item->quantity : 0;
        if (($currentQty + $qty) > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi. Sisa stok: ' . $product->stock);
        }

        if ($item) {
            $item->quantity += $qty;
            $item->unit_price = $price;
            $item->total_price = $item->quantity * $price;
            $item->save();
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'unit_price' => $price,
                'total_price' => $qty * $price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function update(Request $request, CartItem $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = $item->product;
        if ($validated['quantity'] > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi. Sisa stok: ' . $product->stock);
        }

        $item->quantity = $validated['quantity'];
        $item->total_price = $item->quantity * $item->unit_price;
        $item->save();

        return back()->with('success', 'Keranjang diperbarui');
    }

    public function remove(CartItem $item)
    {
        $item->delete();
        return back()->with('success', 'Item dihapus dari keranjang');
    }
}

