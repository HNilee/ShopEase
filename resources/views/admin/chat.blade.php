@extends('layouts.app')

@section('content')
@php
    // Menentukan Nama Lawan Bicara (Admin lihat nama Buyer, Buyer lihat nama Admin)
    $chatTitle = auth()->user()->role === 'admin' || auth()->user()->role === 'owner' 
        ? ($order->user->username ?? 'Buyer') 
        : 'Admin ShopEase';
    
    // Mengambil gambar produk pertama dari order (Pengaman anti-error)
    $firstItem = $order->items->first();
    $productImg = $firstItem && $firstItem->product && $firstItem->product->image 
        ? asset('storage/'.$firstItem->product->image) 
        : 'https://via.placeholder.com/60?text=Produk';
    
    $totalPrice = number_format($order->total, 0, ',', '.');
    $itemCount = $order->items->count();
    $orderStatus = strtoupper(str_replace('_', ' ', $order->status));
    $orderDate = $order->created_at->format('d M Y');
@endphp

<style>
    .chat-container { height: calc(100vh - 120px); min-height: 500px; max-height: 800px; }
    .chat-sidebar { border-right: 1px solid #e5e5e5; background: #fafafa; }
    .chat-item { display: flex; padding: 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: 0.2s; text-decoration: none; }
    .chat-item:hover, .chat-item.active { background: #f5f5f5; border-left: 4px solid #0070F3; }
    .chat-avatar { width: 40px; height: 40px; border-radius: 50%; background: #0070F3; margin-right: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #fff;}
    
    /* Bubble Chat & Meta (Jam + Centang) */
    .bubble { max-width: 75%; min-width: 100px; padding: 8px 14px; border-radius: 12px; font-size: 14px; line-height: 1.4; position: relative; display: flex; flex-direction: column; gap: 4px; }
    .bubble.me { background: #e3f2fd; align-self: flex-end; border-bottom-right-radius: 2px; color: #333;}
    .bubble.them { background: #fff; border: 1px solid #e5e5e5; align-self: flex-start; border-bottom-left-radius: 2px; color: #333;}
    .bubble-meta { display: flex; align-items: center; justify-content: flex-end; gap: 4px; font-size: 11px; opacity: 0.7; }
    
    .tick-icon { width: 14px; height: 14px; stroke-width: 2.5; }
    .tick-blue { stroke: #0070F3; }
    .tick-gray { stroke: #888; }
    
    /* Dark Mode Overrides */
    .dark .chat-sidebar { background: #111827; border-color: #374151; }
    .dark .chat-item { border-color: #374151; }
    .dark .chat-item:hover, .dark .chat-item.active { background: #1F2937; border-color: #3B82F6; }
    .dark .bubble.them { background: #374151; border-color: #4B5563; color: #F3F4F6; }
    .dark .bubble.me { background: #1E3A8A; color: #F3F4F6; border: none; }
</style>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="chat-container flex bg-white dark:bg-gray-800 rounded-2xl shadow-soft overflow-hidden border border-gray-200 dark:border-gray-700">
        
        <div class="chat-sidebar w-72 md:w-80 flex flex-col z-10 relative hidden md:flex">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 font-bold text-lg text-primary">
                Pesan Saya
            </div>
            <div class="flex-1 overflow-y-auto" id="fullChatList">
                <div class="p-10 text-center text-gray-400 text-sm animate-pulse">Memuat obrolan...</div>
            </div>
        </div>

        <div class="flex-1 flex flex-col bg-gray-50 dark:bg-gray-900 relative">
            
            <div class="px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 font-bold text-lg text-text-primary dark:text-white flex items-center gap-3 shadow-sm z-10">
                <span class="md:hidden text-primary cursor-pointer mr-2" onclick="window.history.back()">← Back</span>
                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm">{{ substr($chatTitle, 0, 1) }}</div>
                {{ $chatTitle }}
            </div>

            <div class="flex px-6 py-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 items-start justify-between shadow-sm z-10">
                <div class="flex items-start gap-4">
                    <img src="{{ $productImg }}" class="w-14 h-14 object-cover rounded border border-gray-200 dark:border-gray-600" alt="Product">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-danger">{{ $orderStatus }}</span>
                        <span class="text-sm text-text-primary dark:text-gray-200">{{ $itemCount }} item, Total: Rp {{ $totalPrice }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Date: {{ $orderDate }}</span>
                    </div>
                </div>
                <a href="{{ route('purchases.show', $order) }}" class="px-4 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition text-text-primary dark:text-white">Details</a>
            </div>

            <div class="flex-1 p-6 overflow-y-auto flex flex-col gap-3" id="fullMessagesArea">
                @foreach($messages as $msg)
                    @php
                        $isMe = $msg->sender_id === auth()->id();
                        $type = $isMe ? 'me' : 'them';
                        $tickColor = $msg->is_read ? 'tick-blue' : 'tick-gray';
                        $timeStr = $msg->created_at->format('h:i A');
                    @endphp
                    <div class="bubble {{ $type }}">
                        <div>{{ $msg->body }}</div>
                        <div class="bubble-meta">
                            <span>{{ $timeStr }}</span>
                            @if($isMe)
                                <svg class="tick-icon {{ $tickColor }}" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 6 7 17 2 12"></polyline><polyline points="22 10 15 17 13 15"></polyline></svg>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <form id="fullChatForm" class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex gap-3">
                <input type="text" id="fullChatMessage" placeholder="Ketik pesan..." autocomplete="off" required class="flex-1 px-5 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-full outline-none focus:border-primary dark:text-white text-sm">
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white w-12 h-12 rounded-full flex items-center justify-center transition shadow-soft">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let activeChatId = '{{ $order->id }}';
    let fetchController = null;
    const currentUserId = {{ auth()->id() }};
    const msgArea = document.getElementById('fullMessagesArea');

    // Paksa gulir ke paling bawah saat pertama dibuka
    msgArea.scrollTop = msgArea.scrollHeight;

    document.addEventListener('DOMContentLoaded', () => {
        loadConversations();
        // Polling background update
        setInterval(loadConversations, 10000);
        setInterval(() => loadMessages(activeChatId, false), 4000);
    });

    function formatTime(dateString) {
        const d = dateString ? new Date(dateString) : new Date();
        return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    function getTicks(isMe, isRead) {
        if (!isMe) return '';
        const colorClass = isRead ? 'tick-blue' : 'tick-gray';
        return `<svg class="tick-icon ${colorClass}" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 6 7 17 2 12"></polyline><polyline points="22 10 15 17 13 15"></polyline></svg>`;
    }

    function loadConversations() {
        fetch('/chat/conversations')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('fullChatList');
                if(data.length === 0) return list.innerHTML = '<div class="p-10 text-center text-gray-400 text-sm">Belum ada chat lain.</div>';
                
                let html = '';
                data.forEach(conv => {
                    const safeId = conv.order_id || conv.id;
                    const isActive = String(safeId) === String(activeChatId) ? 'active' : '';
                    const init = conv.title.charAt(0).toUpperCase();
                    
                    html += `
                        <a href="/chat/purchase/${safeId}" class="chat-item ${isActive}">
                            <div class="chat-avatar">${init}</div>
                            <div class="flex-1 overflow-hidden">
                                <div class="font-bold text-sm truncate dark:text-gray-200">${conv.title}</div>
                                <div class="text-xs text-gray-500 truncate">${conv.last_message}</div>
                            </div>
                            ${conv.unread_count > 0 ? `<div class="bg-danger text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full">${conv.unread_count}</div>` : ''}
                        </a>
                    `;
                });
                list.innerHTML = html;
            }).catch(e => console.error(e));
    }

    function loadMessages(id, scrollToBottom = false) {
        if (fetchController) fetchController.abort();
        fetchController = new AbortController();

        fetch(`/chat/purchase/${id}/messages`, { signal: fetchController.signal })
            .then(res => res.json())
            .then(messages => {
                let html = '';
                messages.forEach(msg => {
                    const isMe = msg.sender_id === currentUserId;
                    const type = isMe ? 'me' : 'them';
                    html += `
                        <div class="bubble ${type}">
                            <div>${msg.body}</div>
                            <div class="bubble-meta">
                                <span>${formatTime(msg.created_at)}</span>
                                ${getTicks(isMe, msg.is_read)}
                            </div>
                        </div>
                    `;
                });
                
                const isNew = msgArea.innerHTML !== html;
                msgArea.innerHTML = html;
                if(scrollToBottom && isNew) msgArea.scrollTop = msgArea.scrollHeight;
            }).catch(err => { if(err.name !== 'AbortError') console.error(err); });
    }

    document.getElementById('fullChatForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('fullChatMessage');
        const text = input.value.trim();
        if(!text) return;

        input.value = '';
        
        // Munculkan Centang 1 Secara Instan (Optimistic UI)
        msgArea.innerHTML += `
            <div class="bubble me" style="opacity: 0.6">
                <div>${text}</div>
                <div class="bubble-meta"><span>${formatTime()}</span>${getTicks(true, false)}</div>
            </div>
        `;
        msgArea.scrollTop = msgArea.scrollHeight;

        let formData = new FormData();
        formData.append('body', text);
        formData.append('_token', '{{ csrf_token() }}');

        fetch(`/chat/purchase/${activeChatId}`, { method: 'POST', body: formData, headers: { 'Accept': 'application/json' } })
            .then(() => {
                loadMessages(activeChatId, true);
                loadConversations();
            });
    });
</script>
@endsection