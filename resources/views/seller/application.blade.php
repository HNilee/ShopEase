@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-4xl px-6 py-8">
    <div class="bg-white rounded-xl shadow-soft p-6">
        <h2 class="text-2xl font-semibold mb-6">Become a Seller</h2>
        
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('seller.application.submit') }}" method="POST" enctype="multipart/form-data" id="sellerApplicationForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required 
                           class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    @error('full_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Age *</label>
                    <input type="number" name="age" value="{{ old('age') }}" required min="18" max="100"
                           class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    @error('age')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">KTP Upload *</label>
                    <input type="file" name="ktp" accept="image/*" required
                           class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="text-sm text-gray-500 mt-1">Please upload a clear photo of your KTP. Max size: 2MB</p>
                    @error('ktp')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    1. What is your purpose for wanting to become a seller on ShopEase? *
                </label>
                <textarea name="purpose" rows="4" required 
                          class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                          placeholder="Please describe your purpose in detail (minimum 50 characters)">{{ old('purpose') }}</textarea>
                @error('purpose')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    2. How confident are you in the security of transactions with Buyers and Admins? *
                </label>
                <textarea name="security_confidence" rows="4" required 
                          class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                          placeholder="Please describe your confidence in transaction security (minimum 50 characters)">{{ old('security_confidence') }}</textarea>
                @error('security_confidence')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-6">
                <div class="flex items-center">
                    <input type="checkbox" name="agree_to_sop" id="agree_to_sop" required 
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    <label for="agree_to_sop" class="ml-2 text-sm text-gray-700">
                        I agree to follow ShopEase SOP by avoiding illegal actions (Scam, Fraud, and Arguments) *
                    </label>
                </div>
                @error('agree_to_sop')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4">
                <div class="flex items-center">
                    <input type="checkbox" name="agree_to_terms" id="agree_to_terms" required 
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    <label for="agree_to_terms" class="ml-2 text-sm text-gray-700">
                        I have read and agree to the 
                        <a href="{{ route('seller.terms') }}" target="_blank" class="text-primary hover:text-primary-hover underline">
                            Terms and Conditions
                        </a>
                        *
                    </label>
                </div>
                @error('agree_to_terms')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" 
                        class="bg-primary text-white px-6 py-2 rounded-full hover:bg-primary-hover transition-colors">
                    Submit Application
                </button>
                <a href="{{ route('home') }}" 
                   class="bg-gray-300 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-400 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.getElementById('sellerApplicationForm').addEventListener('submit', function(e) {
    const purpose = document.querySelector('textarea[name="purpose"]').value;
    const securityConfidence = document.querySelector('textarea[name="security_confidence"]').value;
    
    if (purpose.length < 50) {
        e.preventDefault();
        alert('Purpose description must be at least 50 characters.');
        return;
    }
    
    if (securityConfidence.length < 50) {
        e.preventDefault();
        alert('Security confidence description must be at least 50 characters.');
        return;
    }
    
    if (!document.getElementById('agree_to_sop').checked) {
        e.preventDefault();
        alert('You must agree to follow ShopEase SOP.');
        return;
    }
    
    if (!document.getElementById('agree_to_terms').checked) {
        e.preventDefault();
        alert('You must agree to the Terms and Conditions.');
        return;
    }
});
</script>
@endsection