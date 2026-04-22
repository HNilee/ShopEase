@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-6 py-12 relative">
    <div class="mb-6">
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    <div class="flex items-center gap-4 mb-6">
        <h2 class="text-2xl font-semibold">Payment Method</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 rounded-xl bg-white shadow-soft overflow-hidden flex flex-col min-h-[500px]">
            <div class="p-6 border-b flex justify-between items-center bg-gray-50">
                <h3 class="text-xl font-semibold">Select Payment Method</h3>
                <div class="text-danger font-mono font-bold text-lg" id="paymentTimer">05:00</div>
            </div>
            <div class="flex flex-1 overflow-hidden flex-col md:flex-row">
                <!-- Sidebar Methods -->
                <div class="w-full md:w-1/3 border-r bg-gray-50 overflow-y-auto">
                    <button class="w-full text-left px-6 py-4 hover:bg-gray-100 border-b border-l-4 border-l-transparent focus:border-l-primary focus:bg-white" onclick="showMethod('va')">
                        <div class="font-semibold">Virtual Account</div>
                        <div class="text-xs text-text-secondary">OVO, GoPay, ShopeePay...</div>
                    </button>
                    <button class="w-full text-left px-6 py-4 hover:bg-gray-100 border-b border-l-4 border-l-transparent focus:border-l-primary focus:bg-white" onclick="showMethod('qr')">
                        <div class="font-semibold">QR Code</div>
                        <div class="text-xs text-text-secondary">Scan to pay</div>
                    </button>
                    <button class="w-full text-left px-6 py-4 hover:bg-gray-100 border-b border-l-4 border-l-transparent focus:border-l-primary focus:bg-white" onclick="showMethod('debit')">
                        <div class="font-semibold">Debit Card</div>
                        <div class="text-xs text-text-secondary">BCA, Mandiri, BRI...</div>
                    </button>
                    <button class="w-full text-left px-6 py-4 hover:bg-gray-100 border-b border-l-4 border-l-transparent focus:border-l-primary focus:bg-white" onclick="showMethod('crypto')">
                        <div class="font-semibold">Crypto Currency</div>
                        <div class="text-xs text-text-secondary">BTC, ETH, DOGE...</div>
                    </button>
                </div>
                <!-- Content -->
                <div class="w-full md:w-2/3 p-6 overflow-y-auto" id="methodContent">
                    <div class="text-center text-text-secondary mt-10">Select a payment method to proceed</div>
                </div>
            </div>
        </div>
        
        <div class="rounded-xl bg-white shadow-soft p-6 h-fit">
            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span>Order Number</span><span class="font-mono">{{ $order->order_number }}</span></div>
                <div class="border-t my-2"></div>
                <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Tax</span><span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span></div>
                <div class="flex justify-between font-bold text-lg text-primary mt-2"><span>Total</span><span>Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
            </div>
            
            <form id="paymentForm" action="{{ route('checkout.pay', $order) }}" method="POST" class="mt-6">
                @csrf
                <input type="hidden" name="payment_method" id="inputPaymentMethod">
                <!-- Button will be inserted by JS -->
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
            let timerInterval;
            let totalAmount = Number("{{ $order->total }}");
            startTimer();

            function startTimer() {
                let duration = 300; // 5 minutes
                const display = document.getElementById('paymentTimer');
                if (!display) return;
                
                clearInterval(timerInterval);
                updateDisplay(duration);
                
                timerInterval = setInterval(function () {
                    if (--duration < 0) {
                        clearInterval(timerInterval);
                        alert("Transaksi Anda telah dibatalkan karena waktu estimasi pembayaran sudah melewati batas (5 Menit)");
                        window.location.href = "{{ route('home') }}"; // Redirect home on timeout
                    } else {
                        updateDisplay(duration);
                    }
                }, 1000);

                function updateDisplay(time) {
                    let minutes = parseInt(time / 60, 10);
                    let seconds = parseInt(time % 60, 10);
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;
                    display.textContent = minutes + ":" + seconds;
                }
            }

            window.showMethod = function(type) {
                const content = document.getElementById('methodContent');
                if (!content) return;
                
                content.innerHTML = '';
                
                if (type === 'va') {
                    const providers = [
                        { name: 'OVO', num: '39358087775933022' },
                        { name: 'GOPAY', num: '70001087775933022' },
                        { name: 'SHOPEEPAY', num: '126087775933022' },
                        { name: 'BCA Virtual Account', num: generateRandomNum(17) },
                        { name: 'DANA', num: generateRandomNum(17) },
                        { name: 'SEABANK', num: generateRandomNum(17) }
                    ];
                    let html = '<h4 class="text-lg font-semibold mb-4">Virtual Account</h4><div class="space-y-3">';
                    providers.forEach(p => {
                        html += `<div class="p-3 border rounded-lg cursor-pointer hover:bg-blue-50" onclick="selectVA('${p.name}', '${p.num}')">
                            <div class="font-medium">${p.name}</div>
                        </div>`;
                    });
                    html += '</div>';
                    content.innerHTML = html;
                } else if (type === 'qr') {
                    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=Order-${Date.now()}`;
                    content.innerHTML = `
                        <div class="text-center">
                            <h4 class="text-lg font-semibold mb-4">QR Code Payment</h4>
                            <img src="${qrUrl}" class="mx-auto border p-2 rounded-lg" />
                            <p class="mt-4 text-sm text-text-secondary">Scan QR Code ini untuk melanjutkan pembayaran</p>
                            <button onclick="confirmPayment('QR Code')" class="mt-6 rounded-full bg-primary text-white px-6 py-2 hover:bg-primary-hover">Simulate Scan Success</button>
                        </div>
                    `;
                } else if (type === 'debit') {
                    const banks = ['BCA', 'BANK MANDIRI', 'BANK BRI', 'CIMB NIAGA'];
                    let html = '<h4 class="text-lg font-semibold mb-4">Debit Card</h4><div class="space-y-3">';
                    banks.forEach(b => {
                        html += `<div class="p-3 border rounded-lg cursor-pointer hover:bg-blue-50" onclick="selectDebit('${b}')">
                            <div class="font-medium">${b}</div>
                        </div>`;
                    });
                    html += '</div>';
                    content.innerHTML = html;
                } else if (type === 'crypto') {
                    content.innerHTML = '<div class="text-center p-10">Loading crypto prices...</div>';
                    fetchCryptoPrices();
                }
            };

            function generateRandomNum(length) {
                let result = '';
                for (let i = 0; i < length; i++) result += Math.floor(Math.random() * 10);
                return result;
            }

            window.selectVA = function(name, num) {
                const content = document.getElementById('methodContent');
                content.innerHTML = `
                    <div class="text-center p-6">
                        <h4 class="text-lg font-semibold text-primary mb-2">Instruksi Pembayaran</h4>
                        <p class="mb-4">Transfer ke <strong>${name}</strong> dengan virtual number:</p>
                        <div class="bg-gray-100 p-4 rounded-lg font-mono text-xl tracking-wider select-all mb-6">${num}</div>
                        <p class="text-sm text-text-secondary mb-6">untuk melanjutkan pembayaran.</p>
                        <button onclick="confirmPayment('VA - ${name}')" class="rounded-full bg-primary text-white px-8 py-3 hover:bg-primary-hover">Saya Sudah Transfer</button>
                    </div>
                `;
            };

            window.selectDebit = function(bank) {
                const num = generateRandomNum(17);
                const content = document.getElementById('methodContent');
                content.innerHTML = `
                    <div class="text-center p-6">
                        <h4 class="text-lg font-semibold text-primary mb-2">Debit Card ${bank}</h4>
                        <p class="mb-4">Gunakan nomor kartu virtual berikut:</p>
                        <div class="bg-gray-100 p-4 rounded-lg font-mono text-xl tracking-wider select-all mb-6">${num}</div>
                        <button onclick="confirmPayment('Debit - ${bank}')" class="rounded-full bg-primary text-white px-8 py-3 hover:bg-primary-hover">Konfirmasi Pembayaran</button>
                    </div>
                `;
            };

            async function fetchCryptoPrices() {
                try {
                    const response = await fetch('https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,ethereum,dogecoin,solana,sui,turbo&vs_currencies=idr');
                    const data = await response.json();
                    
                    const map = {
                        'bitcoin': { symbol: 'BTC', name: 'Bitcoin' },
                        'ethereum': { symbol: 'ETH', name: 'Ethereum' },
                        'dogecoin': { symbol: 'DOGE', name: 'Dogecoin' },
                        'solana': { symbol: 'SOL', name: 'Solana' },
                        'sui': { symbol: 'SUI', name: 'Sui' },
                        'turbo': { symbol: 'TURBO', name: 'Turbo' }
                    };


                    let html = '<h4 class="text-lg font-semibold mb-4">Crypto Currency</h4><div class="space-y-3">';
                    for (const [key, val] of Object.entries(data)) {
                        if(map[key]) {
                            const price = val.idr;
                            const amount = (totalAmount / price).toFixed(8);
                            html += `<div class="p-3 border rounded-lg cursor-pointer hover:bg-blue-50" onclick="selectCrypto('${map[key].name}', '${amount}', '${map[key].symbol}')">
                                <div class="flex justify-between items-center">
                                    <div class="font-medium">${map[key].name} (${map[key].symbol})</div>
                                    <div class="text-sm text-text-secondary">1 ${map[key].symbol} = Rp ${price.toLocaleString('id-ID')}</div>
                                </div>
                                <div class="text-xs text-primary mt-1">Pay: ${amount} ${map[key].symbol}</div>
                            </div>`;
                        }
                    }
                    html += '</div>';
                    const content = document.getElementById('methodContent');
                    if (content) content.innerHTML = html;
                } catch (e) {
                    console.error(e);
                    const content = document.getElementById('methodContent');
                    if (content) content.innerHTML = '<div class="text-danger text-center">Gagal memuat harga crypto. Silakan coba lagi nanti.</div>';
                }
            }

            window.selectCrypto = function(name, amount, symbol) {
                const content = document.getElementById('methodContent');
                content.innerHTML = `
                    <div class="text-center p-6">
                        <h4 class="text-lg font-semibold text-primary mb-2">Pembayaran Crypto</h4>
                        <p class="mb-4">Silakan transfer sejumlah:</p>
                        <div class="bg-gray-100 p-4 rounded-lg font-mono text-xl tracking-wider select-all mb-2">${amount} ${symbol}</div>
                        <p class="text-sm text-text-secondary mb-6">ke alamat wallet kami (Demo).</p>
                        <button onclick="confirmPayment('Crypto - ${name}')" class="rounded-full bg-primary text-white px-8 py-3 hover:bg-primary-hover">Konfirmasi Transfer</button>
                    </div>
                `;
            };

            window.confirmPayment = function(methodName) {
                clearInterval(timerInterval);
                const form = document.getElementById('paymentForm');
                const input = document.getElementById('inputPaymentMethod');
                if (input) input.value = methodName;
                if (form) form.submit();
            };
        });
    </script>
@endsection