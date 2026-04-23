<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ShopEase</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='40' fill='none' stroke='%23000000' stroke-width='8'/%3E%3Cpath d='M30 55 L42 40 L56 55 L70 40' fill='none' stroke='%23000000' stroke-width='8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <script>
        // Dark mode init
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bg-main text-text-primary antialiased dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300 min-h-screen flex flex-col" style="min-height:100vh;display:flex;flex-direction:column;">
<style>
/* Menghilangkan background warna aneh bawaan browser pada semua input */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active{
    -webkit-box-shadow: 0 0 0 30px transparent inset !important;
    transition: background-color 5000s ease-in-out 0s;
}
.dark input:-webkit-autofill {
    -webkit-text-fill-color: #fff !important;
}

/* Menghilangkan garis kotak (outline) bawaan browser saat input diklik */
input:focus, textarea:focus, select:focus {
    outline: none !important;
    box-shadow: none !important;
    border-color: transparent !important;
}

main { flex:1; }
footer { margin-top:auto; }

/* Additional responsive fixes */
@media (max-width:768px) {
    footer .grid { grid-template-columns:1fr; gap:2rem; }
    footer .md\:col-span-2 { grid-column:span 1; }
}

/* ========================================== */
/* GLOBAL SMOOTH SCROLL REVEAL ANIMATIONS     */
/* ========================================== */
.reveal-item {
    opacity: 0 !important;
    transform: translateY(40px) !important;
    transition: opacity 0.8s ease-out, transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    will-change: opacity, transform;
}

.reveal-item.is-visible {
    opacity: 1 !important;
    transform: translateY(0) !important;
}
</style>

    <header class="sticky top-0 z-40 bg-white dark:bg-gray-800 shadow dark:shadow-gray-700/50 transition-colors duration-300">
        <div class="mx-auto max-w-7xl px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="text-text-primary dark:text-white hover:opacity-80 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="{{ route('home') }}" class="flex items-center gap-2 text-text-primary dark:text-white hover:opacity-80 ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="w-8 h-8 text-black dark:text-white" aria-hidden="true">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="8"/>
                        <path d="M30 55 L42 40 L56 55 L70 40" fill="none" stroke="currentColor" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="font-semibold tracking-wide text-lg">SHOPEASE</span>
                </a>
            </div>

            <div class="flex items-center gap-4">
                
                <div class="relative group" id="customSearchBar">
                    <form action="{{ route('products.index') }}" method="GET" id="searchForm" autocomplete="off">
                        <div class="flex items-center bg-transparent group-hover:bg-white dark:group-hover:bg-gray-700 rounded-full shadow-none group-hover:shadow-soft transition-all duration-300 border border-transparent group-hover:border-gray-200 dark:group-hover:border-gray-600 focus-within:bg-white dark:focus-within:bg-gray-700 focus-within:shadow-soft focus-within:border-gray-200 dark:focus-within:border-gray-600">
                            <input 
                                type="text" 
                                name="search" 
                                id="searchInput"
                                placeholder="Search..." 
                                autocomplete="off"
                                class="w-0 group-hover:w-32 md:group-hover:w-48 focus:w-32 md:focus:w-48 transition-all duration-300 ease-in-out bg-transparent border-none focus:ring-0 text-sm px-0 group-hover:px-4 focus:px-4 text-text-primary dark:text-white sm:w-0 sm:group-hover:w-32 sm:focus:w-32 md:group-hover:w-48 md:focus:w-48"
                            />
                            <button 
                                type="submit" 
                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary transition-colors rounded-full"
                                onclick="if(this.previousElementSibling.value.trim() === '') { event.preventDefault(); this.previousElementSibling.focus(); }"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>

                        <div id="searchHistoryDropdown" class="absolute top-full mt-3 right-0 w-48 md:w-56 bg-white dark:bg-gray-800 rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 hidden z-50 overflow-hidden">
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <span>Recent Searches</span>
                                <button type="button" onclick="clearSearchHistory()" class="hover:text-primary transition-colors">Clear</button>
                            </div>
                            <ul id="searchHistoryList" class="max-h-48 overflow-y-auto">
                                </ul>
                        </div>
                    </form>
                </div>

                <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>

                @if(!auth()->check() || auth()->user()->role !== 'admin')
                    <a href="{{ route('cart.index') }}" class="relative rounded-full p-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-text-primary dark:text-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @if (isset($cartQuantity) && $cartQuantity > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-danger rounded-full">{{ $cartQuantity }}</span>
                        @endif
                    </a>
                @endif

                @auth
                    <div class="relative" id="userMenu">
                        <button type="button" class="flex items-center gap-2 rounded-full border border-gray-300 dark:border-gray-600 pl-2 pr-4 py-1 hover:bg-gray-50 dark:hover:bg-gray-700 text-text-primary dark:text-gray-100 transition-colors">
                            @if (auth()->user()->profile_pic)
                                <img src="{{ asset(auth()->user()->profile_pic) }}" class="size-8 rounded-full object-cover" alt="Profile">
                            @else
                                <div class="size-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold uppercase">
                                    {{ substr(auth()->user()->username, 0, 1) }}
                                </div>
                            @endif
                            <span class="font-medium">{{ auth()->user()->username }}</span>
                            <span class="text-xs">▾</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 rounded-xl bg-white dark:bg-gray-700 shadow-soft border border-gray-200 dark:border-gray-600 hidden z-50" id="userDropdown">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-600">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->username }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('account.settings') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Account Settings</a>
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-b-xl">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="hidden sm:inline-block rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm text-text-primary dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Register</a>
                        @endif
                        <a href="{{ route('login') }}" class="inline-block rounded-full bg-primary px-4 py-2 text-white text-sm hover:bg-primary-hover shadow-lg shadow-primary/30">Login</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/50 hidden transition-opacity opacity-0"></div>
    <aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-64 -translate-x-full bg-white dark:bg-gray-800 shadow-xl transition-transform duration-300 ease-in-out">
        <div class="flex h-full flex-col">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-6 py-4">
                <span class="text-lg font-bold text-primary">Menu</span>
                <button id="sidebar-close" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
                <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('home') ? 'bg-gray-100 dark:bg-gray-700 text-primary dark:text-primary' : '' }}">
                    <span class="material-icons-outlined text-xl">home</span>
                    Home
                </a>

                @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner()))
                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Management</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('admin.products.*') ? 'bg-gray-100 dark:bg-gray-700 text-primary dark:text-primary' : '' }}">
                        <span class="material-icons-outlined text-xl">inventory_2</span>
                        Products
                    </a>
                    <a href="{{ route('admin.purchases') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('admin.purchases') ? 'bg-gray-100 dark:bg-gray-700 text-primary dark:text-primary' : '' }}">
                        <span class="material-icons-outlined text-xl">shopping_bag</span>
                        Orders
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('admin.users') ? 'bg-gray-100 dark:bg-gray-700 text-primary dark:text-primary' : '' }}">
                        <span class="material-icons-outlined text-xl">people</span>
                        Users
                    </a>
                    <a href="{{ route('admin.seller.applications') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('admin.seller.applications') ? 'bg-gray-100 dark:bg-gray-700 text-primary dark:text-primary' : '' }}">
                        <span class="material-icons-outlined text-xl">person_add</span>
                        Seller Applications
                    </a>
                    <a href="{{ route('admin.announcement.create') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('admin.announcement.create') ? 'bg-gray-100 dark:bg-gray-700 text-primary dark:text-primary' : '' }}">
                        <span class="material-icons-outlined text-xl">campaign</span>
                        Announcements
                    </a>
                @else
                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Shop</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('products.index') ? 'bg-gray-100 dark:bg-gray-700 text-primary dark:text-primary' : '' }}">
                        <span class="material-icons-outlined text-xl">storefront</span>
                        All Products
                    </a>
                    <a href="{{ route('cart.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('cart.index') ? 'bg-gray-100 dark:bg-gray-700 text-primary dark:text-primary' : '' }}">
                        <span class="material-icons-outlined text-xl">shopping_cart</span>
                        Shopping Cart
                    </a>
                    @auth
                        <a href="{{ route('purchases.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ request()->routeIs('purchases.index') ? 'bg-gray-100 dark:bg-gray-700 text-primary dark:text-primary' : '' }}">
                            <span class="material-icons-outlined text-xl">receipt_long</span>
                            My Orders
                        </a>
                    @endauth
                @endif
            </nav>

            <div class="border-t border-gray-100 dark:border-gray-700 p-4">
                @auth
                    <div class="flex items-center gap-3 mb-4 px-2">
                         @if (auth()->user()->profile_pic)
                            <img src="{{ asset(auth()->user()->profile_pic) }}" class="size-10 rounded-full object-cover" alt="Profile">
                        @else
                            <div class="size-10 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold uppercase">
                                {{ substr(auth()->user()->username, 0, 1) }}
                            </div>
                        @endif
                        <div class="overflow-hidden">
                            <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->username }}</p>
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="w-full rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">Logout</button>
                    </form>
                @else
                    <div class="space-y-2">
                        <a href="{{ route('login') }}" class="block w-full text-center rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary-hover">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block w-full text-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Register</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </aside>

    <main class="flex-1 bg-bg-main dark:bg-gray-900 transition-colors duration-300">
        @php
        try {
            $announcement = \App\Models\Announcement::where('active', true)
                ->when(auth()->check(), function ($q) {
                    $q->where(function ($qq) {
                        $qq->whereNull('user_id')->orWhere('user_id', auth()->id());
                    });
                })
                ->orderByDesc('created_at')
                ->first();
            } catch (\Exception $e) {
                $announcement = null;
            }
        @endphp
        @if ($announcement)
            <div class="mx-auto max-w-7xl px-6 pt-4">
                <div class="rounded-md bg-warning/10 border border-warning/20 text-warning px-4 py-3 flex items-start gap-3" data-announcement data-announcement-id="{{ $announcement->id }}">
                    <div class="flex-1">
                        <div class="font-semibold">{{ $announcement->title }}</div>
                        @if ($announcement->body)
                            <div class="text-sm mt-1">{{ $announcement->body }}</div>
                        @endif
                    </div>
                    <button type="button" class="ml-auto text-warning hover:opacity-70" data-announcement-close>✕</button>
                </div>
            </div>
        @endif
        
        <div class="text-text-primary dark:text-gray-200 flex-1">
             @yield('content')
        </div>
    </main>

    <footer id="contact" class="mt-auto w-full border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 transition-colors duration-300">
        <div class="mx-auto max-w-7xl px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-text-primary dark:text-white hover:opacity-80 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="w-8 h-8 text-black dark:text-white" aria-hidden="true">
                            <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="8"/>
                            <path d="M30 55 L42 40 L56 55 L70 40" fill="none" stroke="currentColor" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold tracking-wide text-lg">SHOPEASE</span>
                    </a>
                    <p class="text-text-secondary dark:text-gray-400 text-sm max-w-md">
                        Your trusted marketplace for digital gaming products. Secure transactions, verified sellers, and excellent customer service.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold text-text-primary dark:text-white mb-4">Quick Links</h4>
                    <nav class="space-y-2">
                        <a href="{{ route('home') }}" class="block text-sm text-text-secondary dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">Home</a>
                        <a href="{{ route('products.index') }}" class="block text-sm text-text-secondary dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">Products</a>
                        <a href="{{ route('cart.index') }}" class="block text-sm text-text-secondary dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">Cart</a>
                        <a href="{{ route('purchases.index') }}" class="block text-sm text-text-secondary dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">My Orders</a>
                    </nav>
                </div>

                <div>
                    <h4 class="font-semibold text-text-primary dark:text-white mb-4">Support</h4>
                    <nav class="space-y-2">
                        <a href="#contact" class="block text-sm text-text-secondary dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">Contact Us</a>
                        <a href="{{ route('seller.terms') }}" class="block text-sm text-text-secondary dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">Terms & Conditions</a>
                        <a href="{{ route('seller.application.form') }}" class="block text-sm text-text-secondary dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">Become a Seller</a>
                        <a href="#" class="block text-sm text-text-secondary dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">Help Center</a>
                    </nav>
                </div>
            </div>

            <hr class="my-6 border-gray-200 dark:border-gray-700">

            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-text-secondary dark:text-gray-500">
                    Copyright © {{ date('Y') }} ShopEase. All Rights Reserved.
                </p>
                <div class="flex items-center gap-4 text-text-secondary dark:text-gray-400">
                    <a href="#" class="hover:text-primary transition-colors" aria-label="Telegram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.35-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-1.4-.92-2.2-1.49-3.55-2.38-1.58-1.05-.23-1.63.92-2.58.23-.2.58-.6.28-.9-.3-.3-1.08.23-1.4.38-.23.12-.53.05-.73-.15l-1.2-1.2c-.28-.28-.28-.73 0-1.01.28-.28.73-.28 1.01 0l1.2 1.2c.28.28.73.28 1.01 0l1.2-1.2c.28-.28.73-.28 1.01 0 .28.28.28.73 0 1.01l-1.2 1.2c-.28.28-.73.28-1.01 0l-1.2-1.2c-.28-.28-.73-.28-1.01 0-.28.28-.28.73 0 1.01l1.2 1.2c.28.28.73.28 1.01 0l1.2-1.2c.28-.28.73-.28 1.01 0 .28.28.28.73 0 1.01l-1.2 1.2c-.28.28-.73.28-1.01 0l-1.2-1.2c-.28-.28-.73-.28-1.01 0-.28.28-.28.73 0 1.01l1.2 1.2c.28.28.73.28 1.01 0l1.2-1.2c.28-.28.73-.28 1.01 0z"/>
                        </svg>
                    </a>
                    <a href="#" class="hover:text-primary transition-colors" aria-label="WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.471.099-.174.05-.347-.025-.471-.075-.124-.67-1.612-.916-2.206-.242-.579-.487-.5-.67-.51-.173-.008-.297-.01-.47.099-.174.099-1.017.52-1.017 1.27s.446 1.275 1.018 1.45c.297.075.893.225 1.473.344.297.075.595.15.892.025.297-.124.52-.347.644-.595.124-.248.198-.52.198-.793 0-.273-.074-.545-.223-.768-.15-.223-.372-.396-.644-.52-.273-.124-.595-.198-.893-.198-.297 0-.595.074-.893.198-.297.124-.52.297-.644.52-.124.223-.198.495-.198.768 0 .273.074.545.223.768.149.223.372.396.644.52.272.124.595.198.893.198z"/>
                        </svg>
                    </a>
                    <a href="#" class="hover:text-primary transition-colors" aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

   <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. THEME TOGGLE (Aman dari error)
            const themeToggleBtn = document.getElementById('theme-toggle');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');

            if (darkIcon && lightIcon) {
                if (document.documentElement.classList.contains('dark')) {
                    lightIcon.classList.remove('hidden');
                } else {
                    darkIcon.classList.remove('hidden');
                }
            }

            if (themeToggleBtn && darkIcon && lightIcon) {
                themeToggleBtn.addEventListener('click', function() {
                    darkIcon.classList.toggle('hidden');
                    lightIcon.classList.toggle('hidden');
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                });
            }

            // 2. ALERT & ANNOUNCEMENT CLOSE
            document.querySelectorAll('[data-alert-close]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const c = btn.closest('[data-alert]');
                    if (c) c.style.display = 'none';
                });
            });

            const annEl = document.querySelector('[data-announcement]');
            if (annEl) {
                const annId = annEl.getAttribute('data-announcement-id');
                const dismissed = JSON.parse(localStorage.getItem('dismissedAnnouncements') || '[]');
                if (dismissed.includes(annId)) annEl.style.display = 'none';
                const annBtn = annEl.querySelector('[data-announcement-close]');
                if (annBtn) {
                    annBtn.addEventListener('click', function () {
                        annEl.style.display = 'none';
                        if (!dismissed.includes(annId)) {
                            dismissed.push(annId);
                            localStorage.setItem('dismissedAnnouncements', JSON.stringify(dismissed));
                        }
                    });
                }
            }

            // 3. SIDEBAR TOGGLE
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');

            function toggleSidebar() {
                if(sidebar) sidebar.classList.toggle('-translate-x-full');
                if(sidebarOverlay) {
                    sidebarOverlay.classList.toggle('hidden');
                    if (!sidebarOverlay.classList.contains('hidden')) {
                        setTimeout(() => sidebarOverlay.classList.remove('opacity-0'), 10);
                    } else {
                        sidebarOverlay.classList.add('opacity-0');
                    }
                }
            }
            if (sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
            if (sidebarClose) sidebarClose.addEventListener('click', toggleSidebar);
            if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);

            // 4. USER DROPDOWN
            const userMenu = document.getElementById('userMenu');
            const userDropdown = document.getElementById('userDropdown');
            if (userMenu && userDropdown) {
                const trigger = userMenu.querySelector('button');
                if(trigger) {
                    trigger.addEventListener('click', function () {
                        userDropdown.classList.toggle('hidden');
                    });
                }
                document.addEventListener('click', function (e) {
                    if (!userMenu.contains(e.target)) userDropdown.classList.add('hidden');
                });
            }

            // 5. SEARCH HISTORY
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            const historyDropdown = document.getElementById('searchHistoryDropdown');
            const historyList = document.getElementById('searchHistoryList');
            const customSearchBar = document.getElementById('customSearchBar');

            window.clearSearchHistory = function() {
                localStorage.removeItem('shopease_search_history');
                if(historyDropdown) historyDropdown.classList.add('hidden');
            };

            function loadSearchHistory() {
                if(!historyList || !historyDropdown) return;
                let history = JSON.parse(localStorage.getItem('shopease_search_history')) || [];
                historyList.innerHTML = '';
                if (history.length === 0) {
                    historyDropdown.classList.add('hidden');
                    return;
                }
                history.forEach(item => {
                    let li = document.createElement('li');
                    li.className = "px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors flex items-center gap-2";
                    li.innerHTML = `<svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <span class="truncate">${item}</span>`;
                    li.onclick = function() {
                        if(searchInput && searchForm) {
                            searchInput.value = item;
                            searchForm.submit();
                        }
                    };
                    historyList.appendChild(li);
                });
                historyDropdown.classList.remove('hidden');
            }

            if (searchForm && searchInput) {
                searchForm.addEventListener('submit', function(e) {
                    let val = searchInput.value.trim();
                    if (val !== '') {
                        let history = JSON.parse(localStorage.getItem('shopease_search_history')) || [];
                        history = history.filter(h => h !== val);
                        history.unshift(val);
                        if (history.length > 5) history.pop();
                        localStorage.setItem('shopease_search_history', JSON.stringify(history));
                    }
                });
                searchInput.addEventListener('focus', loadSearchHistory);
                document.addEventListener('click', function(e) {
                    if (customSearchBar && !customSearchBar.contains(e.target) && historyDropdown) {
                        historyDropdown.classList.add('hidden');
                    }
                });
            }

            // 6. ANTI CACHE BACK BUTTON
            window.addEventListener('pageshow', function(event) {
                if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                    window.location.reload();
                }
            });

            // 7. ANIMASI SCROLL (HANYA KARTU & CONTAINER BESAR)
            const scrollObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    } else {
                        entry.target.classList.remove('is-visible');
                    }
                });
            }, { root: null, rootMargin: '0px', threshold: 0.1 });

            // Hanya menargetkan elemen kartu utama (menghindari teks dan elemen kecil)
            const elementsToAnimate = document.querySelectorAll('main .rounded-xl.shadow-soft, main .bg-gradient-to-r, main table');
            
            elementsToAnimate.forEach((el) => {
                if (!el.closest('#chatPopupWindow') && !el.closest('#tourOverlay')) {
                    el.classList.add('reveal-item');
                    void el.offsetWidth; // Force reflow
                    scrollObserver.observe(el);
                }
            });
        });
    </script>
    
    @yield('scripts')
    @include('components.announcement-popup')
    @include('components.chat-popup')
</body>
</html>