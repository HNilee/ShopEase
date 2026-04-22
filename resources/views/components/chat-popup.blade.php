<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use App\Models\ChatGroup;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // ==============================================================
    // FITUR CHAT PESANAN (ORDER)
    // ==============================================================
    public function show(Order $order)
    {
        if (!auth()->check()) return redirect()->route('login');
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'owner' && $order->user_id !== auth()->id()) abort(403);

        $messages = Message::with('replyTo')->where('order_id', $order->id)->orderBy('created_at')->get();
        return view(auth()->user()->role === 'admin' ? 'admin.chat' : 'purchases.chat', compact('order', 'messages'));
    }

    public function fetchMessages(Request $request, Order $order)
    {
        request()->session()->save();
        if (!auth()->check()) return response()->json(['error' => 'Unauthorized'], 401);

        Message::where('order_id', $order->id)->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)->update(['is_read' => true]);

        $messages = Message::with('replyTo')->where('order_id', $order->id)->orderBy('created_at')->get();
        return response()->json($messages);
    }

    public function send(Request $request, Order $order)
    {
        $validated = $request->validate([
            'body' => 'nullable|string|max:2000',
            'reply_to_id' => 'nullable|exists:messages,id'
        ]);

        $message = Message::create([
            'order_id' => $order->id,
            'customer_id' => $order->user_id,
            'sender_id' => auth()->id(),
            'body' => $validated['body'],
            'is_read' => false,
            'reply_to_id' => $validated['reply_to_id'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    // ==============================================================
    // FITUR GENERAL CHAT (CUSTOMER SUPPORT)
    // ==============================================================
    public function fetchGeneralMessages(Request $request)
    {
        request()->session()->save();
        $customerId = (auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
            ? $request->query('customer_id')
            : auth()->id();

        if ($customerId) {
            Message::whereNull('order_id')->where('customer_id', $customerId)
                ->where('sender_id', '!=', auth()->id())->where('is_read', false)
                ->update(['is_read' => true]);
        }

        $messages = Message::with('replyTo')->whereNull('order_id')->where('customer_id', $customerId)->orderBy('created_at')->get();
        return response()->json($messages);
    }

    public function sendGeneralMessage(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'customer_id' => 'nullable|exists:users,id',
            'reply_to_id' => 'nullable|exists:messages,id'
        ]);

        $customerId = (auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
            ? $validated['customer_id']
            : auth()->id();

        $message = Message::create([
            'order_id' => null,
            'customer_id' => $customerId,
            'sender_id' => auth()->id(),
            'body' => $validated['body'],
            'is_read' => false,
            'reply_to_id' => $validated['reply_to_id'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    // ==============================================================
    // FITUR GROUP CHAT (MEDIASI)
    // ==============================================================
    public function fetchGroupMessages(ChatGroup $group) {
        request()->session()->save();
        if (!auth()->check()) return response()->json(['error' => 'Unauthorized'], 401);

        Message::where('chat_group_id', $group->id)->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)->update(['is_read' => true]);

        $messages = Message::with('replyTo')->where('chat_group_id', $group->id)->orderBy('created_at')->get();
        return response()->json($messages);
    }

    public function sendGroupMessage(Request $request, ChatGroup $group) {
        if (!auth()->check()) return response()->json(['error' => 'Unauthorized'], 401);

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'reply_to_id' => 'nullable|exists:messages,id'
        ]);

        $message = Message::create([
            'chat_group_id' => $group->id,
            'sender_id' => auth()->id(),
            'body' => $validated['body'],
            'is_read' => false,
            'reply_to_id' => $validated['reply_to_id'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }
}