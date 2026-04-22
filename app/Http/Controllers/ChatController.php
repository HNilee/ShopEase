<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ChatGroup;

class ChatController extends Controller
{
    public function getConversations()
{
    $userId = auth()->id();
    $role = auth()->user()->role;
    $conversations = collect();

    // 1. AMBIL SEMUA CHAT PESANAN (ORDER)
    $orders = \App\Models\Order::where(function($q) use ($userId, $role) {
        if ($role === 'buyer') $q->where('user_id', $userId);
        // Admin/Owner bisa melihat semua chat order
    })->get();

    foreach ($orders as $order) {
        $lastMsg = \App\Models\Message::where('order_id', $order->id)->latest()->first();
        if ($lastMsg) {
            $unread = \App\Models\Message::where('order_id', $order->id)
                ->where('sender_id', '!=', $userId)
                ->where('is_read', false)->count();

            $conversations->push([
                'id' => $order->id,
                'order_id' => $order->id,
                'title' => 'ORD-' . $order->order_number,
                'last_message' => $lastMsg->body ?? 'Gambar terkirim',
                'unread_count' => $unread,
                'order_status' => $order->status,
                'item_count' => $order->items->count(),
                'order_total' => $order->total,
                'product_image' => $order->items->first()->product->image ?? null,
                'created_at' => $lastMsg->created_at
            ]);
        }
    }

    // 2. AMBIL CHAT CUSTOMER SUPPORT (CS) - ANTI TABRAKAN
    if ($role === 'admin' || $role === 'owner') {
        // Admin melihat semua CS secara terpisah
        $csChats = \App\Models\Message::whereNull('order_id')
            ->selectRaw('customer_id, MAX(created_at) as last_msg_time')
            ->groupBy('customer_id')->get();

        foreach ($csChats as $cs) {
            $customer = \App\Models\User::find($cs->customer_id);
            $unreadCS = \App\Models\Message::whereNull('order_id')->where('customer_id', $cs->customer_id)
                ->where('sender_id', '!=', $userId)->where('is_read', false)->count();
            $lastMsgCS = \App\Models\Message::whereNull('order_id')->where('customer_id', $cs->customer_id)->latest()->first();

            $conversations->push([
                'id' => 'CS_' . $cs->customer_id,
                'order_id' => null,
                'customer_id' => $cs->customer_id,
                'title' => 'CS - ' . ($customer->username ?? 'User'),
                'last_message' => $lastMsgCS->body ?? 'Pesan baru',
                'unread_count' => $unreadCS,
                'created_at' => $cs->last_msg_time
            ]);
        }
    } else {
        // User biasa hanya melihat 1 kamar CS miliknya sendiri
        $unreadCS = \App\Models\Message::whereNull('order_id')->where('customer_id', $userId)
            ->where('sender_id', '!=', $userId)->where('is_read', false)->count();
        $lastMsgCS = \App\Models\Message::whereNull('order_id')->where('customer_id', $userId)->latest()->first();

        $conversations->push([
            'id' => 'CS',
            'order_id' => null,
            'customer_id' => $userId,
            'title' => 'Customer Support',
            'last_message' => $lastMsgCS->body ?? 'Tanya bantuan...',
            'unread_count' => $unreadCS,
            'created_at' => $lastMsgCS ? $lastMsgCS->created_at : now()
        ]);
    }

    // 3. AMBIL CHAT GROUP
    $groups = ChatGroup::whereHas('users', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })->get();

    foreach($groups as $group) {
        $lastMsg = \App\Models\Message::where('chat_group_id', $group->id)->latest()->first();
        $unread = \App\Models\Message::where('chat_group_id', $group->id)
            ->where('sender_id', '!=', $userId)->where('is_read', false)->count();

        $memberNames = $group->users->pluck('username')->implode(', ');

        $conversations->push([
            'id' => 'GRP_' . $group->id,
            'order_id' => null,
            'customer_id' => null,
            'is_group' => true,
            'title' => $group->name,
            'member_names' => $memberNames,
            'last_message' => $lastMsg->body ?? 'Grup Mediasi Dibuat',
            'unread_count' => $unread,
            'created_at' => $lastMsg ? $lastMsg->created_at : $group->created_at
        ]);
    }
    return response()->json($conversations->sortByDesc('created_at')->values());
}

    // FUNGSI UNTUK MEMBUAT GROUP BARU
    public function createGroup(Request $request) {
        try {
            $request->validate([
                'name' => 'required', 
                'user_ids' => 'required|array'
            ]);
            
            // Buat grup di tabel chat_groups
            $group = ChatGroup::create(['name' => $request->name]);
            
            $userIds = $request->user_ids;
            $userIds[] = auth()->id(); // Memasukkan Admin pembuat

            // Otomatis memasukkan semua akun ber-role 'owner'
            $owners = User::where('role', 'owner')->pluck('id')->toArray();
            $allParticipants = array_unique(array_merge($userIds, $owners));
            
            // Masukkan anggota ke tabel pivot chat_group_user
            $group->users()->attach($allParticipants);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // JIKA ERROR, LARAVEL AKAN MENGIRIM PESAN INI KE BROWSER
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getAdminConversations()
    {
        // Menggunakan Eloquent untuk menghindari Error SQL Strict Mode
        $orders = Order::with('user')
            ->whereIn('status', ['payment_verified', 'shipping', 'completed', 'paid_waiting_delivery'])
            ->orderByDesc('updated_at')
            ->take(20)
            ->get();

        $conversations = [];

        foreach ($orders as $order) {
            $lastMessage = Message::where('order_id', $order->id)->orderByDesc('created_at')->first();

            $conversations[] = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'buyer_id' => $order->user_id,
                'buyer_name' => $order->user ? $order->user->name : 'Unknown User',
                'buyer_email' => $order->user ? $order->user->email : '-',
                'message_count' => Message::where('order_id', $order->id)->count(),
                'last_message_at' => $lastMessage ? $lastMessage->created_at : $order->created_at,
                'last_message' => $lastMessage ? $lastMessage->body : 'No messages yet',
                'unread_count' => $this->getUnreadCount($order->id, auth()->id(), true),
                // Title diganti menjadi Nama Pembeli + Nomor Order
                'title' => ($order->user ? $order->user->name : 'Unknown User') . ' (' . $order->order_number . ')',
                'message' => $lastMessage ? $lastMessage->body : 'No messages yet',
                'is_read' => $this->isConversationRead($order->id, auth()->id(), true)
            ];
        }

        usort($conversations, function ($a, $b) {
            return $b['last_message_at'] <=> $a['last_message_at'];
        });

        return $conversations;
    }

    private function getUserConversations($user)
    {
        $orders = Order::where('user_id', $user->id)
            ->whereIn('status', ['payment_verified', 'shipping', 'completed', 'paid_waiting_delivery'])
            ->orderByDesc('updated_at')
            ->take(20)
            ->get();

        $conversations = [];

        foreach ($orders as $order) {
            $lastMessage = Message::where('order_id', $order->id)->orderByDesc('created_at')->first();

            $conversations[] = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'buyer_id' => $order->user_id,
                'buyer_name' => 'Admin ShopEase',
                'buyer_email' => 'admin@shopease.com',
                'message_count' => Message::where('order_id', $order->id)->count(),
                'last_message_at' => $lastMessage ? $lastMessage->created_at : $order->created_at,
                'last_message' => $lastMessage ? $lastMessage->body : 'No messages yet',
                'unread_count' => $this->getUnreadCount($order->id, $user->id, false),
                // Title diganti menjadi nama toko/admin
                'title' => 'Admin ShopEase',
                'message' => $lastMessage ? $lastMessage->body : 'No messages yet',
                'is_read' => $this->isConversationRead($order->id, $user->id, false)
            ];
        }

        usort($conversations, function ($a, $b) {
            return $b['last_message_at'] <=> $a['last_message_at'];
        });

        return $conversations;
    }

    private function getUnreadCount($orderId, $userId, $isAdmin = false)
    {
        $sessionKey = $isAdmin ? 'chat_last_seen_at_admin' : 'chat_last_seen_at_user';
        $lastSeen = session($sessionKey);

        $query = Message::where('order_id', $orderId)->where('sender_id', '!=', $userId);

        if ($lastSeen) {
            $query->where('created_at', '>', $lastSeen);
        }

        return $query->count();
    }

    private function isConversationRead($orderId, $userId, $isAdmin = false)
    {
        $sessionKey = $isAdmin ? 'chat_last_seen_at_admin' : 'chat_last_seen_at_user';
        $lastSeen = session($sessionKey);

        if (!$lastSeen) return false;

        $latestMessage = Message::where('order_id', $orderId)
            ->where('sender_id', '!=', $userId)
            ->orderByDesc('created_at')
            ->first();

        return !$latestMessage || $latestMessage->created_at <= $lastSeen;
    }

    public function createGroup(Request $request) {
        $group = ChatGroup::create(['name' => $request->name]);
        
        // Masukkan user yang dipilih + Admin yang buat + Owner otomatis
        $userIds = $request->user_ids; // Array ID dari checklist
        $userIds[] = auth()->id(); // Admin
        
        // Tambahkan semua Owner secara otomatis
        $owners = User::where('role', 'owner')->pluck('id')->toArray();
        $allParticipants = array_unique(array_merge($userIds, $owners));
        
        $group->users()->attach($allParticipants);
        return response()->json(['success' => true]);
    }
}