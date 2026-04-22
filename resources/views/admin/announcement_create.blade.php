@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-2xl px-6 py-12">
    <h2 class="text-2xl font-semibold">Create Announcement</h2>
    <form action="{{ route('admin.announcement.store') }}" method="POST" class="mt-6 space-y-4 rounded-xl bg-white shadow-soft p-6">
        @csrf
        <div>
            <label class="block text-sm text-text-secondary">Title</label>
            <input type="text" name="title" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm text-text-secondary">Body</label>
            <textarea name="body" rows="3" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"></textarea>
        </div>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="active" checked class="rounded border-gray-300">
            <span>Active</span>
        </label>
        <button class="rounded-full bg-primary text-white px-6 py-3 hover:bg-primary-hover">Publish</button>
    </form>
</section>
@endsection
