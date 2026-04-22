<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PurchaseController;

// ==========================================
// 1. PUBLIC ROUTES (GUEST & AUTH)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// ==========================================
// 2. PROTECTED ROUTES (HARUS LOGIN)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Auth & Account
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/account/settings', [AccountController::class, 'settings'])->name('account.settings');
    Route::post('/account/picture', [AccountController::class, 'updatePicture'])->name('account.update_picture');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/item/{item}', [CartController::class, 'update'])->name('cart.item.update');
    Route::delete('/cart/item/{item}', [CartController::class, 'remove'])->name('cart.item.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');
    Route::get('/checkout/{order}/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/{order}/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Purchases / Orders (History & Detail)
    Route::get('/purchases', [OrderController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/{order}', [PurchaseController::class, 'show'])->name('purchases.show'); // Rute Detail Dipindah ke Sini
    Route::get('/purchases/{order}/receipt', [OrderController::class, 'receipt'])->name('purchases.receipt');
    Route::post('/purchases/{order}/pay', [OrderController::class, 'pay'])->name('purchases.pay');
    Route::delete('/purchases/clear', [OrderController::class, 'clearHistory'])->name('purchases.clear');

    // Chat System (Order & General)
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/conversations', [ChatController::class, 'getConversations'])->name('chat.conversations');
    
    // --> General Chat (CS) Rute dipindah ke dalam Middleware
    Route::get('/chat/general/messages', [MessageController::class, 'fetchGeneralMessages']);
    Route::post('/chat/general/send', [MessageController::class, 'sendGeneralMessage']);
    
    // --> Order Chat
    Route::get('/chat/purchase/{order}', [MessageController::class, 'show'])->name('chat.show');
    Route::get('/chat/purchase/{order}/messages', [MessageController::class, 'fetchMessages'])->name('chat.messages');
    Route::post('/chat/purchase/{order}', [MessageController::class, 'send'])->name('chat.send');

    // --> Group Chat (Mediasi) - TARUH DI SINI!
    Route::post('/chat/group/create', [ChatController::class, 'createGroup']);
    Route::get('/chat/group/{group}/messages', [MessageController::class, 'fetchGroupMessages']);
    Route::post('/chat/group/{group}/send', [MessageController::class, 'sendGroupMessage']);
    
    // Announcements
    Route::get('/announcements/global', [AnnouncementController::class, 'getGlobalAnnouncements'])->name('announcements.global');
    Route::post('/announcements/{id}/viewed', [AnnouncementController::class, 'markAsViewed'])->name('announcements.viewed');

    // Notifications (Inline Functions diubah jadi Controller direkomendasikan, tapi ini dibiarkan sementara)
    Route::get('/notifications', function() {
        $notifications = auth()->user()->notifications()->where('is_read', false)->get();
        return response()->json($notifications);
    })->name('notifications.index');
    
    Route::post('/notifications/{notification}/read', function($notification) {
        $notification = auth()->user()->notifications()->findOrFail($notification);
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    })->name('notifications.read');


    // ==========================================
    // 3. SELLER ROUTES
    // ==========================================
    Route::prefix('seller')->name('seller.')->group(function () {
        Route::get('/application', [SellerController::class, 'showApplicationForm'])->name('application.form');
        Route::post('/application', [SellerController::class, 'submitApplication'])->name('application.submit');
        Route::get('/application/status', [SellerController::class, 'showApplicationStatus'])->name('application.status');
        Route::get('/terms', [SellerController::class, 'showTerms'])->name('terms');
    });


    // ==========================================
    // 4. ADMIN ROUTES
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {
        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}/block', [AdminController::class, 'usersBlock'])->name('users.block');
        Route::post('/users/{user}/ban', [AdminController::class, 'usersBan'])->name('users.ban');
        Route::delete('/users/{user}', [AdminController::class, 'usersDelete'])->name('users.delete');
        
        // Products
        Route::get('/products', [AdminController::class, 'products'])->name('products.index');
        Route::get('/products/create', [AdminController::class, 'productsCreate'])->name('products.create');
        Route::post('/products', [AdminController::class, 'productsStore'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminController::class, 'productsEdit'])->name('products.edit');
        Route::put('/products/{product}', [AdminController::class, 'productsUpdate'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'productsDestroy'])->name('products.destroy');
        
        // Purchases / Orders
        Route::get('/purchases', [AdminController::class, 'purchases'])->name('purchases');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::post('/orders/{order}/update', [AdminController::class, 'updateOrder'])->name('orders.update');
        Route::post('/purchases/{order}/complete', [AdminController::class, 'completePurchase'])->name('purchases.complete');
        
        // Announcements
        Route::get('/announcement/create', [AdminController::class, 'announcementCreate'])->name('announcement.create');
        Route::post('/announcement', [AdminController::class, 'announcementStore'])->name('announcement.store');
        
        // Seller Applications
        Route::get('/seller-applications', [AdminController::class, 'sellerApplications'])->name('seller.applications');
        Route::post('/seller-applications/{application}/approve', [AdminController::class, 'approveSellerApplication'])->name('seller.approve');
        Route::post('/seller-applications/{application}/reject', [AdminController::class, 'rejectSellerApplication'])->name('seller.reject');

        // Jalur Backend untuk Group Chat
        Route::post('/chat/group/create', [\App\Http\Controllers\ChatController::class, 'createGroup']);
        Route::get('/chat/group/{group}/messages', [\App\Http\Controllers\MessageController::class, 'fetchGroupMessages']);
        Route::post('/chat/group/{group}/send', [\App\Http\Controllers\MessageController::class, 'sendGroupMessage']);
    });
});