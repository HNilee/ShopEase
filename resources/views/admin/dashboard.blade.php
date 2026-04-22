@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-6 py-12">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold">Admin Dashboard</h2>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}" class="rounded-full border border-gray-300 px-4 py-2 hover:bg-gray-50">Manage Products</a>
            <a href="{{ route('admin.users') }}" class="rounded-full border border-gray-300 px-4 py-2 hover:bg-gray-50">Manage Users</a>
            <a href="{{ route('admin.purchases') }}" class="rounded-full border border-gray-300 px-4 py-2 hover:bg-gray-50">Purchases</a>
            <a href="{{ route('admin.announcement.create') }}" class="rounded-full border border-gray-300 px-4 py-2 hover:bg-gray-50">Announcement</a>
        </div>
    </div>
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-xl bg-white shadow-soft p-6">
            <div class="text-sm text-text-secondary">Users</div>
            <div class="text-3xl font-semibold">{{ $usersCount }}</div>
        </div>
        <div class="rounded-xl bg-white shadow-soft p-6">
            <div class="text-sm text-text-secondary">Orders</div>
            <div class="text-3xl font-semibold">{{ $ordersCount }}</div>
        </div>
        <div class="rounded-xl bg-white shadow-soft p-6">
            <div class="text-sm text-text-secondary">Products</div>
            <div class="text-3xl font-semibold">{{ $productsCount }}</div>
        </div>
    </div>
</section>
@endsection
