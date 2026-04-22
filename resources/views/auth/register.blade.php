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
        <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-text-secondary">Profile Picture (Optional)</label>
                <input type="file" name="profile_pic" accept="image/*" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                @error('profile_pic') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm text-text-secondary">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @error('username') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm text-text-secondary">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @error('first_name') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm text-text-secondary">Last Name (Optional)</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-text-secondary">Password</label>
                <input type="password" name="password" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @error('password') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm text-text-secondary">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @error('phone') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm text-text-secondary">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @error('date_of_birth') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="rounded-md border bg-gray-50 px-4 py-3 text-sm text-text-secondary">
                <div class="font-medium text-text-primary">Terms and Conditions</div>
                <p class="mt-2">
                    Dengan mendaftar, Anda menyetujui penggunaan data sesuai kebijakan privasi, memberikan informasi yang benar, memahami bahwa transaksi bersifat final setelah pembayaran, dan pengembalian untuk produk digital tunduk pada kebijakan penjual. Anda dilarang melakukan penyalahgunaan layanan, penipuan, atau pelanggaran hak cipta. Akun bersifat pribadi dan kredensial tidak boleh dibagikan. Pelanggaran dapat mengakibatkan penangguhan atau penghentian akun.
                </p>
            </div>
            <label class="flex items-start gap-3 text-sm text-text-secondary">
                <input type="checkbox" name="terms" value="1" class="mt-1 rounded border-gray-300" {{ old('terms') ? 'checked' : '' }}>
                <span>Saya telah membaca dan menyetujui Terms and Conditions</span>
            </label>
            @error('terms') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            <button class="w-full rounded-full bg-primary text-white px-4 py-2 hover:bg-primary-hover">Sign up</button>
        </form>
    </div>
</section>
@endsection
