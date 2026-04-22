<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Announcement;
use App\Models\BannedIp;
use App\Models\SellerApplication;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected function ensureAdmin()
    {
        if (!auth()->check() || !(auth()->user()->isAdmin() || auth()->user()->isOwner())) {
            abort(403);
        }
    }

    public function users()
    {
        $this->ensureAdmin();
        $users = User::orderBy('name')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function usersBlock(Request $request, User $user)
    {
        $this->ensureAdmin();
        
        $isBlocking = !$user->is_blocked;
        
        $data = [
            'is_blocked' => $isBlocking
        ];

        if ($isBlocking) {
            $data['block_reason'] = $request->input('reason');
        } else {
            $data['block_reason'] = null;
        }

        $user->update($data);

        $message = $isBlocking ? 'User blocked successfully.' : 'User unblocked successfully.';
        return back()->with('success', $message);
    }

    public function usersBan(Request $request, User $user)
    {
        $this->ensureAdmin();

        if (!$user->ip_address) {
            return back()->with('error', 'User has no recorded IP address to ban.');
        }

        BannedIp::create([
            'ip_address' => $user->ip_address,
            'reason' => $request->input('reason'),
        ]);

        return back()->with('success', 'User IP (' . $user->ip_address . ') has been banned permanently.');
    }

    public function usersDelete(Request $request, User $user)
    {
        $this->ensureAdmin();
        if ($user->role === 'owner') {
            return back()->with('error', 'Admin tidak memiliki akses untuk menghapus Owner!');
    }
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function products()
    {
        $this->ensureAdmin();
        $products = Product::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function productsCreate()
    {
        $this->ensureAdmin();
        return view('admin.products.create');
    }

    public function productsStore(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $imageUrl = '/uploads/products/' . $imageName;
        }

        $product = Product::create([
            'category_id' => null,
            'name' => $validated['name'],
            'slug' => \Illuminate\Support\Str::slug($validated['name']) . '-' . random_int(100, 999),
            'sku' => strtoupper(\Illuminate\Support\Str::random(8)),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image' => $imageUrl,
            'is_active' => true,
        ]);
        return redirect()->route('admin.products.index')->with('success', 'Product added successfully');
    }

    public function productsEdit(Product $product)
    {
        $this->ensureAdmin();
        return view('admin.products.edit', compact('product'));
    }

    public function productsUpdate(Request $request, Product $product)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists and is a local file
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $updateData['image'] = '/uploads/products/' . $imageName;
        }

        $product->update($updateData);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    public function productsDestroy(Product $product)
    {
        $this->ensureAdmin();
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    public function purchases()
    {
        $this->ensureAdmin();
        $orders = Order::orderByDesc('created_at')->paginate(20);
        return view('admin.purchases', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $this->ensureAdmin();
        $order->load('items', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'status' => 'required|in:wait_for_payment,payment_verified,shipping,completed,cancelled',
        ]);

        $order->status = $validated['status'];
        
        if ($validated['status'] === 'completed' && !$order->completed_at) {
            $order->completed_at = now();
            // Optional: Trigger announcement/notification logic if reused from completePurchase
             \App\Models\Announcement::create([
                'user_id' => $order->user_id,
                'title' => "Order {$order->order_number} Completed. Thanks for purchasing in ShopEase",
                'body' => null,
                'active' => true,
            ]);
        }

        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated successfully');
    }

    public function completePurchase(Order $order)
    {
        $this->ensureAdmin();
        $order->status = 'completed';
        $order->completed_at = now();
        $order->save();
        session()->flash('popup', "Purchase Completed {$order->id}, {$order->items()->first()->product_name}");
        \App\Models\Announcement::create([
            'user_id' => $order->user_id,
            'title' => "Purchase Completed {$order->id}, {$order->items()->first()->product_name}. Thanks for purchasing in ShopEase",
            'body' => null,
            'active' => true,
        ]);
        return back();
    }

    public function announcementCreate()
    {
        $this->ensureAdmin();
        return view('admin.announcement_create');
    }

    public function announcementStore(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'body' => 'nullable|string|max:2000',
            'active' => 'nullable|boolean',
        ]);
        Announcement::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'body' => $validated['body'] ?? null,
            'active' => (bool) ($validated['active'] ?? true),
        ]);
        return redirect()->route('admin.dashboard')->with('success', 'Announcement created');
    }

    public function sellerApplications()
    {
        $this->ensureAdmin();
        $applications = SellerApplication::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.seller-applications', compact('applications'));
    }

    public function approveSellerApplication(SellerApplication $application)
    {
        $this->ensureAdmin();
        
        $application->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Update user role to seller
        $application->user->update(['role' => 'seller']);

        // Notify the applicant
        Notification::create([
            'user_id' => $application->user_id,
            'type' => 'seller_application_approved',
            'title' => 'Seller Application Approved',
            'message' => 'Congratulations! Your seller application has been approved. You can now start selling products.',
        ]);

        return redirect()->back()->with('success', 'Seller application approved successfully.');
    }

    public function rejectSellerApplication(SellerApplication $application, Request $request)
    {
        $this->ensureAdmin();
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Notify the applicant
        Notification::create([
            'user_id' => $application->user_id,
            'type' => 'seller_application_rejected',
            'title' => 'Seller Application Rejected',
            'message' => 'Your seller application has been rejected. Reason: ' . $validated['rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Seller application rejected.');
    }
}
