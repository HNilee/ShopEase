@auth
<style>
    /* Floating Button */
    .chat-floating-btn {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background-color: #ee4d2d;
        color: white;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        cursor: pointer;
        z-index: 9999;
        transition: 0.3s;
    }
    .chat-floating-btn:hover { transform: scale(1.05); }

    /* Popup Window */
    .chat-popup-window {
        position: fixed;
        bottom: 95px;
        right: 25px;
        width: 800px;
        max-width: 90vw;
        height: 600px;
        max-height: 80vh;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        display: none; /* Disembunyikan secara default */
        z-index: 9998;
        overflow: hidden;
        flex-direction: row; /* 2 panel layout */
    }

    @media (max-width: 768px) {
        .chat-popup-window {
            bottom: 0;
            right: 0;
            width: 100vw;
            max-width: 100vw;
            height: 100vh;
            max-height: 100vh;
            border-radius: 0;
        }
        .chat-sidebar {
            width: 100% !important;
            display: flex;
        }
        .chat-main {
            display: none !important;
        }
        .chat-popup-window.active-chat .chat-sidebar {
            display: none !important;
        }
        .chat-popup-window.active-chat .chat-main {
            display: flex !important;
        }
        .chat-floating-btn {
            bottom: 15px;
            right: 15px;
            width: 50px;
            height: 50px;
        }
    }

    /* Header Popup (Untuk Close Button) */
    .chat-popup-header-global {
        position: absolute;
        top: 0; right: 0; left: 0;
        height: 50px;
        background: #fff;
        border-bottom: 1px solid #e5e5e5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 15px;
        z-index: 10;
    }
    .chat-popup-header-global .title { font-weight: bold; color: #ee4d2d; font-size: 18px; }
    .chat-popup-header-global .close-btn { background: none; border: none; font-size: 24px; cursor: pointer; color: #888; }

    /* Panel Kiri & Kanan (Menyesuaikan dengan Header) */
    .chat-sidebar { width: 300px; border-right: 1px solid #e5e5e5; display: flex; flex-direction: column; background: #fafafa; margin-top: 50px; }
    .chat-list { flex: 1; overflow-y: auto; }
    .chat-item { display: flex; padding: 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: 0.2s; }
    .chat-item:hover, .chat-item.active { background: #f5f5f5; border-left: 3px solid #ee4d2d; }
    .chat-avatar { width: 40px; height: 40px; border-radius: 50%; background: #ccc; margin-right: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #fff;}
    .chat-info { flex: 1; overflow: hidden; }
    .chat-name { font-weight: bold; font-size: 14px; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .chat-preview { font-size: 12px; color: #757575; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    
    .chat-main { flex: 1; display: flex; flex-direction: column; background: #fff; margin-top: 50px; }
    .empty-state { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #888; }
    .empty-state img { width: 150px; opacity: 0.5; margin-bottom: 20px; }
    
    .active-chat-header { padding: 10px 15px; border-bottom: 1px solid #e5e5e5; font-weight: bold; font-size: 15px; background: #fff; }
    
    /* Order Card Ala Shopee */
    .order-card { display: flex; padding: 10px 15px; border-bottom: 1px solid #e5e5e5; background: #fafafa; align-items: center; justify-content: space-between; }
    .order-card-left { display: flex; align-items: center; gap: 10px; }
    .order-img { width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
    .order-details { font-size: 12px; color: #333; }
    .order-status { font-size: 12px; color: #ee4d2d; font-weight: bold;}
    .order-btn { padding: 5px 10px; background: transparent; border: 1px solid #ccc; border-radius: 4px; font-size: 12px; cursor: pointer; text-decoration: none; color: #333;}
    .order-btn:hover { background: #f0f0f0; }

    .messages-area { flex: 1; padding: 15px; overflow-y: auto; background: #f9f9f9; display: flex; flex-direction: column; gap: 10px; }
    .bubble { max-width: 75%; padding: 8px 12px; border-radius: 12px; font-size: 13px; line-height: 1.4; position: relative;}
    .bubble.me { background: #dcf8c6; align-self: flex-end; border-bottom-right-radius: 2px;}
    .bubble.them { background: #fff; border: 1px solid #e5e5e5; align-self: flex-start; border-bottom-left-radius: 2px;}
    
    .chat-input { padding: 12px; background: #fff; border-top: 1px solid #e5e5e5; display: flex; gap: 10px; }
    .chat-input input { flex: 1; padding: 10px 15px; border: 1px solid #ccc; border-radius: 20px; outline: none; }
    .chat-input button { padding: 0 15px; background: #ee4d2d; color: #fff; border: none; border-radius: 20px; cursor: pointer; font-weight: bold;}
</style>

<div class="chat-floating-btn" onclick="toggleChatPopup()">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
</div>

<div class="chat-popup-window" id="chatPopupWindow">
    <div class="chat-popup-header-global">
        <div class="flex items-center gap-2">
            <button id="chatPopupBackBtn" class="md:hidden text-gray-500 hover:text-gray-700" style="display: none;" onclick="closePopupActiveChat()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <div class="title">Chat</div>
        </div>
        <button class="close-btn" onclick="toggleChatPopup()">&times;</button>
    </div>

    <div class="chat-sidebar">
        <div class="chat-list" id="popupChatList">
            <div style="padding: 20px; text-align: center; color: #888;">Memuat obrolan...</div>
        </div>
    </div>

    <div class="chat-main">
        <div id="popupEmptyState" class="empty-state">
            <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Chat Icon">
            <h3>Welcome to ShopEase Chat</h3>
            <p>Start chatting regarding your orders now!</p>
        </div>

        <div id="popupActiveState" style="display: none; flex-direction: column; height: 100%;">
            <div class="active-chat-header" id="popupActiveChatName">Nama User / Toko</div>
            
            <div class="order-card" id="popupOrderCard"></div>

            <div class="messages-area" id="popupMessagesArea"></div>

            <form class="chat-input" id="popupChatForm">
                <input type="text" id="popupChatMessage" placeholder="Type a message here..." autocomplete="off" required>
                <button type="submit">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let isChatPopupOpen = false;
    let activeOrderIdPopup = null;
    let chatPollingInterval = null;
    const currentUserIdPopup = {{ auth()->id() }};

    function toggleChatPopup() {
        const popup = document.getElementById('chatPopupWindow');
        isChatPopupOpen = !isChatPopupOpen;
        
        if (isChatPopupOpen) {
            popup.style.display = 'flex';
            loadPopupConversations();
            
            // Mulai polling untuk update chat secara real-time
            chatPollingInterval = setInterval(() => {
                if(activeOrderIdPopup) loadPopupMessages(activeOrderIdPopup, false);
                loadPopupConversations(false);
            }, 5000);
        } else {
            popup.style.display = 'none';
            // Hentikan polling saat popup ditutup untuk menghemat server
            if (chatPollingInterval) clearInterval(chatPollingInterval);
        }
    }

    function loadPopupConversations(showLoading = true) {
        fetch('/chat/conversations')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('popupChatList');
                if(showLoading) list.innerHTML = '';
                
                if(data.length === 0 && showLoading) {
                    list.innerHTML = '<div style="padding: 20px; text-align: center; color: #888;">Belum ada percakapan.</div>';
                    return;
                }

                let html = '';
                data.forEach((conv, index) => {
                    const isActive = conv.order_id === activeOrderIdPopup ? 'active' : '';
                    // Simpan data di window agar bisa diakses tanpa masalah quote di HTML
                    if(!window.chatConversations) window.chatConversations = {};
                    window.chatConversations[conv.id] = conv;

                    html += `
                        <div class="chat-item ${isActive}" onclick="openPopupChatById('${conv.id}')">
                            <div class="chat-avatar" style="background: #${Math.floor(Math.random()*16777215).toString(16)}">${conv.title.charAt(0)}</div>
                            <div class="chat-info">
                                <div class="chat-name">${conv.title}</div>
                                <div class="chat-preview">${conv.last_message}</div>
                            </div>
                            ${conv.unread_count > 0 ? `<div style="background: #ee4d2d; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px;">${conv.unread_count}</div>` : ''}
                        </div>
                    `;
                });
                list.innerHTML = html;
            })
            .catch(err => {
                console.error('Chat error:', err);
                document.getElementById('popupChatList').innerHTML = '<div style="padding: 20px; text-align: center; color: #888;">Gagal memuat obrolan.</div>';
            });
    }

    function openPopupChatById(id) {
        const conv = window.chatConversations[id];
        if(conv) openPopupChat(conv);
    }

    function openPopupChat(conv) {
        activeOrderIdPopup = conv.order_id;
        
        document.getElementById('chatPopupWindow').classList.add('active-chat');
        if(window.innerWidth <= 768) {
            document.getElementById('chatPopupBackBtn').style.display = 'block';
        }

        document.getElementById('popupEmptyState').style.display = 'none';
        document.getElementById('popupActiveState').style.display = 'flex';
        document.getElementById('popupActiveChatName').innerText = conv.title;

        // Render Order Card ala Shopee
        const imgSrc = conv.product_image ? conv.product_image : 'https://via.placeholder.com/40';
        document.getElementById('popupOrderCard').innerHTML = `
            <div class="order-card-left">
                <img src="${imgSrc}" class="order-img" alt="Product">
                <div class="order-details">
                    <div class="order-status">${conv.order_status.toUpperCase()}</div>
                    <div>${conv.item_count} items, Total: Rp ${parseInt(conv.order_total).toLocaleString('id-ID')}</div>
                </div>
            </div>
            <a href="/admin/orders/${conv.order_id}" class="order-btn">Details</a>
        `;

        loadPopupConversations(false); // Highlight active chat
        loadPopupMessages(conv.order_id, true);
    }

    function closePopupActiveChat() {
        activeOrderIdPopup = null;
        document.getElementById('chatPopupWindow').classList.remove('active-chat');
        document.getElementById('chatPopupBackBtn').style.display = 'none';
        document.getElementById('popupEmptyState').style.display = 'flex';
        document.getElementById('popupActiveState').style.display = 'none';
        loadPopupConversations(false);
    }

    function loadPopupMessages(orderId, scrollToBottom = false) {
        fetch(`/chat/purchase/${orderId}/messages`)
            .then(res => res.json())
            .then(messages => {
                const area = document.getElementById('popupMessagesArea');
                let html = '';
                messages.forEach(msg => {
                    const type = msg.sender_id === currentUserIdPopup ? 'me' : 'them';
                    html += `<div class="bubble ${type}">${msg.body}</div>`;
                });
                
                const isNewContent = area.innerHTML !== html;
                area.innerHTML = html;
                
                if(scrollToBottom && isNewContent) {
                    area.scrollTop = area.scrollHeight;
                }
            });
    }

    document.getElementById('popupChatForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('popupChatMessage');
        const text = input.value.trim();
        if(!text || !activeOrderIdPopup) return;

        input.value = ''; // Kosongkan input
        
        // Optimistic UI
        const area = document.getElementById('popupMessagesArea');
        area.innerHTML += `<div class="bubble me" style="opacity: 0.7">${text}</div>`;
        area.scrollTop = area.scrollHeight;

        let formData = new FormData();
        formData.append('body', text);
        formData.append('_token', '{{ csrf_token() }}');

        fetch(`/chat/purchase/${activeOrderIdPopup}`, {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            loadPopupMessages(activeOrderIdPopup, true); 
            loadPopupConversations(false); 
        });
    });
</script>
@endauth
