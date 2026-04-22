@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-6 py-8 md:py-16">
    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'owner'))
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h1 class="text-3xl font-semibold">Welcome, Admin</h1>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('home') }}" class="rounded-full bg-primary text-white px-4 py-2 hover:bg-primary-hover text-sm">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="rounded-full border border-gray-300 px-4 py-2 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800 text-sm">Manage Products</a>
                <a href="{{ route('admin.purchases') }}" class="rounded-full border border-gray-300 px-4 py-2 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800 text-sm">Purchases</a>
                <a href="{{ route('admin.announcement.create') }}" class="rounded-full border border-gray-300 px-4 py-2 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800 text-sm">Announcement</a>
            </div>
        </div>
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="rounded-xl bg-white dark:bg-gray-800 shadow-soft p-6 flex flex-col justify-between border border-gray-100 dark:border-gray-700">
                <div>
                    <div class="text-sm text-text-secondary dark:text-gray-400">Users</div>
                    <div class="text-3xl font-semibold mt-1">{{ $usersCount }}</div>
                </div>
                <a href="{{ route('admin.users') }}" class="mt-4 inline-block text-center rounded-lg bg-gray-100 dark:bg-gray-700 px-4 py-2 text-sm font-medium text-text-primary dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Manage Users
                </a>
            </div>
            <div class="rounded-xl bg-white dark:bg-gray-800 shadow-soft p-6 border border-gray-100 dark:border-gray-700">
                <div class="text-sm text-text-secondary dark:text-gray-400">Orders</div>
                <div class="text-3xl font-semibold mt-1">{{ $ordersCount }}</div>
            </div>
            <div class="rounded-xl bg-white dark:bg-gray-800 shadow-soft p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm text-text-secondary dark:text-gray-400">Total Income</div>
                    <button type="button" id="toggleIncomeBtn" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none" title="Toggle Visibility">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                <div class="text-3xl font-semibold">
                    <span id="incomeValue" class="hidden">{{ 'Rp ' . number_format($totalIncome, 0, ',', '.') }}</span>
                    <span id="incomeHidden">Rp ••••••••</span>
                </div>
            </div>
        </div>
        <div class="mt-12">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <h2 class="text-xl font-semibold">Sales Analytics</h2>
                <div class="flex flex-wrap gap-2 md:gap-4">
                    <div class="relative" id="chartTypeMenu">
                        <button type="button" class="flex items-center gap-2 rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm">
                            <span id="chartTypeLabel">Line Chart</span>
                            <span>▾</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 rounded-xl bg-white dark:bg-gray-800 shadow-soft border border-gray-200 dark:border-gray-700 hidden z-10" id="chartTypeDropdown">
                            <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm" data-value="line">Line Chart</button>
                            <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm" data-value="bar">Bar Chart</button>
                        </div>
                    </div>
                    <div class="relative" id="metricMenu">
                        <button type="button" class="flex items-center gap-2 rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm">
                            <span id="metricLabel">Total Purchase Income</span>
                            <span>▾</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-64 rounded-xl bg-white dark:bg-gray-800 shadow-soft border border-gray-200 dark:border-gray-700 hidden z-10" id="metricDropdown">
                            <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm" data-value="income">Total Purchase Income</button>
                            <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm" data-value="quantity">Total Quantity Sold</button>
                        </div>
                    </div>
                    <div class="relative" id="periodMenu">
                        <button type="button" class="flex items-center gap-2 rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm">
                            <span id="periodLabel">Daily</span>
                            <span>▾</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 rounded-xl bg-white dark:bg-gray-800 shadow-soft border border-gray-200 dark:border-gray-700 hidden z-10" id="periodDropdown">
                            <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm" data-value="daily">Daily</button>
                            <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm" data-value="weekly">Weekly</button>
                            <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm" data-value="monthly">Monthly</button>
                            <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm" data-value="yearly">Yearly</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-white dark:bg-gray-800 shadow-soft p-6 h-96 relative border border-gray-100 dark:border-gray-700">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('salesChart')?.getContext('2d');
                const chartData = @json($chartData ?? []);
                let currentChart = null;

                function initChart(type, metric, period = 'daily') {
                    if (!ctx) return;
                    if (currentChart) currentChart.destroy();
                    const dataSet = chartData[period] || { labels: [], income: [], quantity: [] };
                    const labels = dataSet.dates;
                    const data = metric === 'income' ? dataSet.income : dataSet.quantity;
                    const label = metric === 'income' ? 'Total Income (Rp)' : 'Quantity Sold';
                    const color = '#0070F3';

                    const config = {
                        type: type === 'pie' ? 'pie' : (type === 'line' ? 'line' : 'bar'),
                        data: {
                            labels: labels,
                            datasets: [{
                                label: label,
                                data: data,
                                backgroundColor: type === 'pie' ? generateColors(data.length) : (type === 'line' ? 'rgba(0, 112, 243, 0.1)' : 'rgba(0, 112, 243, 0.7)'),
                                borderColor: type === 'pie' ? '#ffffff' : color,
                                borderWidth: 2,
                                fill: type === 'line',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: {
                                legend: { display: type === 'pie' },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            if (metric === 'income') {
                                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y || context.parsed);
                                            } else {
                                                label += context.parsed.y || context.parsed;
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: type === 'pie' ? {} : {
                                y: { beginAtZero: true, grid: { borderDash: [2, 4], color: 'rgba(156, 163, 175, 0.2)' } },
                                x: { grid: { display: false } }
                            }
                        }
                    };
                    currentChart = new Chart(ctx, config);
                }

                function generateColors(count) {
                    const colors = [];
                    for(let i=0; i<count; i++) {
                        const hue = (i * 137.508) % 360;
                        colors.push(`hsla(${hue}, 70%, 60%, 0.7)`);
                    }
                    return colors;
                }

                initChart('line', 'income', 'daily');

                const toggleIncomeBtn = document.getElementById('toggleIncomeBtn');
                const incomeValue = document.getElementById('incomeValue');
                const incomeHidden = document.getElementById('incomeHidden');
                const eyeIcon = document.getElementById('eyeIcon');
                const eyeOffIcon = document.getElementById('eyeOffIcon');
                let isIncomeVisible = false;

                if(toggleIncomeBtn) {
                    toggleIncomeBtn.addEventListener('click', function() {
                        isIncomeVisible = !isIncomeVisible;
                        if (isIncomeVisible) {
                            incomeValue.classList.remove('hidden'); incomeHidden.classList.add('hidden');
                            eyeIcon.classList.add('hidden'); eyeOffIcon.classList.remove('hidden');
                        } else {
                            incomeValue.classList.add('hidden'); incomeHidden.classList.remove('hidden');
                            eyeIcon.classList.remove('hidden'); eyeOffIcon.classList.add('hidden');
                        }
                    });
                }

                function setupDropdown(menuId, dropdownId, labelId, onChange) {
                    const menu = document.getElementById(menuId);
                    const btn = menu.querySelector('button');
                    const dropdown = document.getElementById(dropdownId);
                    const label = document.getElementById(labelId);
                    
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        document.querySelectorAll('.z-10').forEach(el => {
                            if(el.id !== dropdownId) el.classList.add('hidden');
                        });
                        dropdown.classList.toggle('hidden');
                    });

                    dropdown.querySelectorAll('button').forEach(b => {
                        b.addEventListener('click', function() {
                            label.textContent = this.textContent;
                            dropdown.classList.add('hidden');
                            onChange(this.dataset.value);
                        });
                    });
                }

                let currentType = 'line', currentMetric = 'income', currentPeriod = 'daily';
                setupDropdown('chartTypeMenu', 'chartTypeDropdown', 'chartTypeLabel', val => { currentType = val; initChart(currentType, currentMetric, currentPeriod); });
                setupDropdown('metricMenu', 'metricDropdown', 'metricLabel', val => { currentMetric = val; initChart(currentType, currentMetric, currentPeriod); });
                setupDropdown('periodMenu', 'periodDropdown', 'periodLabel', val => { currentPeriod = val; initChart(currentType, currentMetric, currentPeriod); });

                document.addEventListener('click', function() {
                    document.querySelectorAll('.z-10').forEach(el => el.classList.add('hidden'));
                });
            });
        </script>

    @elseif(auth()->check() && (auth()->user()->role === 'buyer' || auth()->user()->role === 'seller'))
        <div class="space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-text-primary dark:text-white">Welcome back, {{ auth()->user()->username }}!</h1>
                    <p class="text-text-secondary mt-1">Ready to find something new?</p>
                </div>
                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-primary text-white rounded-full hover:bg-primary-hover transition shadow-soft text-center">
                    Browse Products
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-soft border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-text-secondary dark:text-gray-400">Shopping Cart</p>
                            <h3 class="text-2xl font-bold text-text-primary dark:text-white">{{ $cartItemCount }} Items</h3>
                        </div>
                    </div>
                    <a href="{{ route('cart.index') }}" class="mt-4 block text-sm text-primary hover:underline">View Cart &rarr;</a>
                </div>
                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-soft border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-text-secondary dark:text-gray-400">Recent Activity</p>
                            <h3 class="text-2xl font-bold text-text-primary dark:text-white">{{ $recentOrders->count() }} Orders</h3>
                        </div>
                    </div>
                    <a href="{{ route('purchases.index') }}" class="mt-4 block text-sm text-primary hover:underline">View History &rarr;</a>
                </div>
                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-soft border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-text-secondary dark:text-gray-400">My Account</p>
                            <h3 class="text-2xl font-bold text-text-primary dark:text-white">Profile</h3>
                        </div>
                    </div>
                    <a href="{{ route('account.settings') }}" class="mt-4 block text-sm text-primary hover:underline">Manage Account &rarr;</a>
                </div>
            </div>

            @if($recentOrders->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-text-primary dark:text-white">Recent Orders</h3>
                    <a href="{{ route('purchases.index') }}" class="text-sm text-primary hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-4 font-medium text-text-secondary dark:text-gray-400">Order ID</th>
                                <th class="px-6 py-4 font-medium text-text-secondary dark:text-gray-400">Date</th>
                                <th class="px-6 py-4 font-medium text-text-secondary dark:text-gray-400">Total</th>
                                <th class="px-6 py-4 font-medium text-text-secondary dark:text-gray-400">Status</th>
                                <th class="px-6 py-4 font-medium text-text-secondary dark:text-gray-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 text-text-primary dark:text-gray-200">#{{ substr($order->order_number, -8) }}</td>
                                <td class="px-6 py-4 text-text-secondary dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-text-primary dark:text-gray-200">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($order->status == 'completed') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                        @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('purchases.show', $order) }}" class="font-semibold text-primary hover:text-primary-hover transition-colors underline">Lihat Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(auth()->user()->role === 'buyer')
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-text-primary dark:text-white mb-2">Want to Become a Seller?</h3>
                        <p class="text-text-secondary dark:text-gray-400">Join thousands of sellers on ShopEase and start selling your gaming products today!</p>
                        <ul class="mt-3 text-sm text-text-secondary dark:text-gray-400 space-y-1">
                            <li>✓ Reach thousands of gaming enthusiasts</li>
                            <li>✓ Secure transaction system</li>
                            <li>✓ 24/7 customer support</li>
                            <li>✓ Easy product management</li>
                        </ul>
                    </div>
                    <div class="md:text-right">
                        @if(auth()->user()->sellerApplication)
                            <a href="{{ route('seller.application.status') }}" class="inline-block bg-primary text-white px-6 py-3 rounded-full hover:bg-primary-hover transition shadow-soft">
                                Check Application Status
                            </a>
                        @else
                            <a href="{{ route('seller.application.form') }}" class="inline-block bg-primary text-white px-6 py-3 rounded-full hover:bg-primary-hover transition shadow-soft">
                                Apply Now
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

    @else
        <style>
            /* CSS Animasi untuk efek scroll ala portofolio */
            .reveal-on-scroll {
                opacity: 0;
                transform: translateY(40px);
                transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
            }
            .reveal-on-scroll.is-visible {
                opacity: 1;
                transform: translateY(0);
            }
        </style>

        <div class="min-h-[70vh] flex items-center pt-8 pb-20 reveal-on-scroll is-visible">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center w-full">
                <div>
                    <h1 class="text-5xl font-bold leading-tight text-text-primary dark:text-white">Smart Shopping,<br><span class="text-primary">Made Simple</span></h1>
                    <p class="mt-6 text-lg text-text-secondary dark:text-gray-400 max-w-xl leading-relaxed">
                        A mini e-commerce platform designed to provide a seamless, fast, and efficient online shopping experience through a clean and intuitive interface.
                    </p>
                    <div class="mt-10 flex flex-wrap items-center gap-4">
                        <button type="button" id="btnTutorial" class="rounded-full bg-primary hover:bg-primary-hover text-white px-8 py-3.5 font-medium shadow-soft transition-all hover:shadow-lg hover:-translate-y-1">Get Started</button>
                        <a href="{{ route('products.index') }}" class="rounded-full border-2 border-gray-200 dark:border-gray-700 hover:border-primary dark:hover:border-primary hover:text-primary text-text-primary dark:text-white px-8 py-3.5 font-medium transition-all hover:shadow-lg hover:-translate-y-1">Start Shopping</a>
                    </div>
                </div>
                <div class="relative hidden md:block">
                    <div class="absolute -top-10 -right-8 w-72 h-56 rounded-2xl overflow-hidden shadow-2xl z-0 transform translate-x-4 -rotate-3">
                        <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=640&auto=format&fit=crop" alt="Gaming hero 1" class="w-full h-full object-cover">
                    </div>
                    <div class="relative w-80 h-64 rounded-2xl overflow-hidden shadow-2xl z-10 border-4 border-white dark:border-gray-800 transform -translate-x-4 rotate-2">
                        <img src="{{ asset('images/ohnepixel.png') }}" alt="Gaming hero 2" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <div class="py-24 border-t border-gray-100 dark:border-gray-800 reveal-on-scroll">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-text-primary dark:text-white">Why Choose ShopEase?</h2>
                <p class="mt-4 text-text-secondary dark:text-gray-400">Everything you need for a perfect digital shopping experience, built right into the core of our platform.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-soft hover:shadow-lg transition-shadow border border-gray-50 dark:border-gray-700">
                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-6 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-text-primary dark:text-white mb-3">Lightning Fast</h3>
                    <p class="text-text-secondary dark:text-gray-400 leading-relaxed">Experience zero lag shopping. Our platform is optimized to load products and process transactions instantly.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-soft hover:shadow-lg transition-shadow border border-gray-50 dark:border-gray-700">
                    <div class="w-14 h-14 bg-green-50 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-6 text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-text-primary dark:text-white mb-3">Secure Payments</h3>
                    <p class="text-text-secondary dark:text-gray-400 leading-relaxed">Your peace of mind is our priority. We use industry-standard encryption to protect your data and money.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-soft hover:shadow-lg transition-shadow border border-gray-50 dark:border-gray-700">
                    <div class="w-14 h-14 bg-purple-50 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-6 text-purple-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-text-primary dark:text-white mb-3">Verified Sellers</h3>
                    <p class="text-text-secondary dark:text-gray-400 leading-relaxed">Every seller goes through a strict verification process. Buy with confidence from our trusted community.</p>
                </div>
            </div>
        </div>

        <div class="py-24 border-t border-gray-100 dark:border-gray-800 reveal-on-scroll">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="order-2 lg:order-1 relative h-96 rounded-2xl overflow-hidden shadow-soft">
                    <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover" alt="Setup">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <p class="font-bold text-xl">Instant Delivery</p>
                        <p class="text-sm opacity-90">Start playing within seconds.</p>
                    </div>
                </div>
                <div class="order-1 lg:order-2">
                    <h2 class="text-3xl font-bold text-text-primary dark:text-white mb-8">How it works</h2>
                    <div class="space-y-8">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary font-bold text-lg flex items-center justify-center shrink-0">1</div>
                            <div>
                                <h4 class="text-xl font-bold text-text-primary dark:text-white">Find Your Game</h4>
                                <p class="mt-2 text-text-secondary dark:text-gray-400">Search through our extensive catalog of digital products, game keys, and accounts.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary font-bold text-lg flex items-center justify-center shrink-0">2</div>
                            <div>
                                <h4 class="text-xl font-bold text-text-primary dark:text-white">Secure Checkout</h4>
                                <p class="mt-2 text-text-secondary dark:text-gray-400">Pay using our secure gateway. We hold your funds until you confirm the order is received.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary font-bold text-lg flex items-center justify-center shrink-0">3</div>
                            <div>
                                <h4 class="text-xl font-bold text-text-primary dark:text-white">Play Immediately</h4>
                                <p class="mt-2 text-text-secondary dark:text-gray-400">Get your product details instantly via our real-time chat system with the seller.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-24 text-center reveal-on-scroll">
            <div class="bg-gradient-to-br from-primary to-purple-600 rounded-3xl p-12 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-white opacity-5"></div>
                <h2 class="text-4xl font-bold text-white mb-6 relative z-10">Ready to level up your gaming?</h2>
                <p class="text-white/80 text-lg max-w-2xl mx-auto mb-10 relative z-10">Join thousands of gamers who already use ShopEase for their digital needs. Creating an account is 100% free.</p>
                <div class="flex justify-center gap-4 relative z-10">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="rounded-full bg-white text-primary px-8 py-3.5 font-bold hover:shadow-lg hover:-translate-y-1 transition-all">Create Free Account</a>
                    @endif
                </div>
            </div>
        </div>

        <div id="tourOverlay" class="fixed inset-0 z-[100] hidden">
            <div id="tourHighlight" class="absolute border-2 border-primary rounded-xl shadow-[0_0_0_9999px_rgba(0,0,0,0.6)] pointer-events-none transition-all duration-300 bg-transparent z-[101]"></div>
            <div id="tourTooltip" class="absolute bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 px-5 py-4 w-72 md:w-80 transition-all duration-300 z-[102]">
                <div class="text-sm text-text-primary dark:text-gray-200 font-medium leading-relaxed" id="tourText"></div>
                <div class="mt-4 flex items-center justify-between">
                    <button id="tourPrev" class="text-xs font-semibold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Previous</button>
                    <div class="flex items-center gap-3">
                        <span id="tourStepIndicator" class="text-xs text-gray-400 font-medium">1/5</span>
                        <button id="tourNext" class="rounded-full bg-primary text-white px-4 py-1.5 text-xs font-semibold hover:bg-primary-hover transition-colors">Next</button>
                        <button id="tourClose" class="hidden rounded-full bg-success text-white px-4 py-1.5 text-xs font-semibold hover:opacity-90 transition-colors">Finish</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // --- 1. SCRIPT UNTUK EFEK MUNCUL SAAT DI-SCROLL (PORTFOLIO STYLE) ---
                const observerOptions = {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.15 // Elemen akan muncul saat 15% bagiannya masuk layar
                };

                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target); // Hanya animasi 1x saat muncul
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.reveal-on-scroll').forEach((el) => {
                    observer.observe(el);
                });

                // --- 2. SCRIPT UNTUK TOUR / TUTORIAL ---
                const btnTutorial = document.getElementById('btnTutorial');
                const overlay = document.getElementById('tourOverlay');
                const highlight = document.getElementById('tourHighlight');
                const tooltip = document.getElementById('tourTooltip');
                const textEl = document.getElementById('tourText');
                const btnPrev = document.getElementById('tourPrev');
                const btnNext = document.getElementById('tourNext');
                const btnClose = document.getElementById('tourClose');
                const stepIndicator = document.getElementById('tourStepIndicator');

                const steps = [
                    { selector: '#sidebar-toggle', text: 'Ini adalah tombol Menu. Klik untuk membuka navigasi utama website.' },
                    { selector: '#customSearchBar', text: 'Gunakan kolom pencarian ini untuk menemukan produk game favoritmu dengan cepat.' },
                    { selector: '#theme-toggle', text: 'Suka tampilan gelap? Klik tombol ini untuk mengubah tema website (Dark/Light Mode).' },
                    { selector: 'a[href*="/cart"]', text: 'Ini adalah Keranjang Belanjamu. Semua produk yang ingin kamu beli akan masuk ke sini.' },
                    { selector: '#userMenu, a[href*="/login"]', text: 'Di sini kamu bisa masuk (login) atau mengelola akunmu untuk mulai bertransaksi!' }
                ];

                let currentStep = 0;

                function isElementVisible(el) {
                    if (!el) return false;
                    const rect = el.getBoundingClientRect();
                    return rect.width > 0 && rect.height > 0;
                }

                function getTargetElement(selector) {
                    const selectors = selector.split(',');
                    for (let sel of selectors) {
                        const el = document.querySelector(sel.trim());
                        if (isElementVisible(el)) return el;
                    }
                    return null;
                }

                function showStep(index) {
                    if (index < 0 || index >= steps.length) return;
                    currentStep = index;
                    
                    const step = steps[currentStep];
                    const targetEl = getTargetElement(step.selector);

                    if (!targetEl) {
                        if (currentStep < steps.length - 1) showStep(currentStep + 1);
                        else endTour();
                        return;
                    }

                    textEl.textContent = step.text;
                    stepIndicator.textContent = `${currentStep + 1}/${steps.length}`;

                    btnPrev.style.visibility = currentStep === 0 ? 'hidden' : 'visible';
                    if (currentStep === steps.length - 1) {
                        btnNext.classList.add('hidden'); btnClose.classList.remove('hidden');
                    } else {
                        btnNext.classList.remove('hidden'); btnClose.classList.add('hidden');
                    }

                    targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    setTimeout(() => {
                        const rect = targetEl.getBoundingClientRect();
                        const pad = 10; 
                        const scrollY = window.scrollY || document.documentElement.scrollTop;
                        const scrollX = window.scrollX || document.documentElement.scrollLeft;

                        highlight.style.left = `${rect.left + scrollX - pad}px`;
                        highlight.style.top = `${rect.top + scrollY - pad}px`;
                        highlight.style.width = `${rect.width + (pad * 2)}px`;
                        highlight.style.height = `${rect.height + (pad * 2)}px`;
                        
                        let tooltipTop = rect.bottom + scrollY + pad + 15;
                        let tooltipLeft = rect.left + scrollX;

                        if (tooltipLeft + tooltip.offsetWidth > window.innerWidth) {
                            tooltipLeft = window.innerWidth - tooltip.offsetWidth - 20;
                        }

                        tooltip.style.top = `${tooltipTop}px`;
                        tooltip.style.left = `${tooltipLeft}px`;
                        
                    }, 300); 
                }

                function startTour(e) {
                    if(e) e.preventDefault();
                    overlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; 
                    showStep(0);
                }

                function endTour() {
                    overlay.classList.add('hidden');
                    document.body.style.overflow = ''; 
                    window.scrollTo({ top: 0, behavior: 'smooth' }); 
                }

                if (btnTutorial) btnTutorial.addEventListener('click', startTour);
                if (btnNext) btnNext.addEventListener('click', (e) => { e.preventDefault(); showStep(currentStep + 1); });
                if (btnPrev) btnPrev.addEventListener('click', (e) => { e.preventDefault(); showStep(currentStep - 1); });
                if (btnClose) btnClose.addEventListener('click', (e) => { e.preventDefault(); endTour(); });
            });
        </script>
    @endif
</section>
@endsection