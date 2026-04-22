@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-3xl px-6 py-12">
    <div class="mb-6">
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>
    <h2 class="text-2xl font-semibold mb-6">Account Settings</h2>

    @if (session('success'))
        <div class="mb-6 rounded-md bg-success/10 text-success px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl bg-white shadow-soft p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Profile Picture</h3>
        <form action="{{ route('account.update_picture') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-6">
            @csrf
            <div class="shrink-0">
                @if (auth()->user()->profile_pic)
                    <img src="{{ asset(auth()->user()->profile_pic) }}" class="size-20 rounded-full object-cover border border-gray-200" alt="Profile">
                @else
                    <div class="size-20 rounded-full bg-primary text-white flex items-center justify-center text-3xl font-bold uppercase border border-gray-200">
                        {{ substr(auth()->user()->username, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-text-primary mb-2">Change Profile Picture</label>
                <input type="file" name="profile_pic" accept="image/*" class="block w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                @error('profile_pic') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            </div>
            <button class="w-full sm:w-auto rounded-full bg-primary text-white px-6 py-2 hover:bg-primary-hover transition-colors">Upload</button>
        </form>
    </div>

    <div class="rounded-xl bg-white shadow-soft p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-sm text-text-secondary">Name</div>
                <div class="font-medium">{{ auth()->user()->name }}</div>
            </div>
            <div>
                <div class="text-sm text-text-secondary">Username</div>
                <div class="font-medium">{{ auth()->user()->username }}</div>
            </div>
            <div>
                <div class="text-sm text-text-secondary">Phone</div>
                <div class="font-medium">{{ auth()->user()->phone }}</div>
            </div>
            <div>
                <div class="text-sm text-text-secondary">Date of Birth</div>
                <div class="font-medium">{{ auth()->user()->date_of_birth }}</div>
            </div>
        </div>
        <div class="text-sm text-text-secondary">Role: <span class="font-medium">{{ auth()->user()->role }}</span></div>
    </div>
</section>
@endsection
