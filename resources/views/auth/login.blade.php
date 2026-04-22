@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-6 py-16 flex justify-center">
    <div class="w-full max-w-md rounded-xl bg-white shadow-soft p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="size-8 rounded-full border border-gray-200 flex items-center justify-center">
                <div class="size-4 rounded-full border border-gray-400"></div>
            </div>
            <span class="font-semibold tracking-wide">SHOPEASE</span>
        </div>
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-text-secondary">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @error('username')
                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block text-sm text-text-secondary">Password</label>
                <input type="password" name="password" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @error('password')
                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex items-center justify-between text-sm text-text-secondary">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    <span>Keep me signed in</span>
                </label>
                <a href="#" class="hover:text-text-primary">Forgot your password?</a>
            </div>
            <button class="w-full rounded-full bg-primary text-white px-4 py-2 hover:bg-primary-hover">Login</button>
        </form>
        <div class="mt-4 flex items-center justify-center gap-4">
            <a href="#" class="rounded-full bg-white border px-3 py-2">G</a>
            <a href="#" class="rounded-full bg-white border px-3 py-2">f</a>
        </div>
        <div class="mt-4 text-center text-sm">
            <span>Don't have an account?</span>
            <a href="{{ route('register') }}" class="text-primary hover:text-primary-hover">Sign up</a>
        </div>
    </div>
</section>
@endsection
