@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-4xl px-6 py-8">
    <div class="bg-white rounded-xl shadow-soft p-6">
        <h2 class="text-2xl font-semibold mb-6">Seller Application Status</h2>
        
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                {{ session('info') }}
            </div>
        @endif

        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-500">Application Status</span>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($application->status === 'pending')
                        bg-yellow-100 text-yellow-800
                    @elseif($application->status === 'approved')
                        bg-green-100 text-green-800
                    @else
                        bg-red-100 text-red-800
                    @endif
                ">
                    {{ ucfirst($application->status) }}
                </span>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-medium text-gray-900 mb-2">Application Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Full Name:</span>
                        <span class="ml-2 font-medium">{{ $application->full_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Age:</span>
                        <span class="ml-2 font-medium">{{ $application->age }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-gray-500">Email:</span>
                        <span class="ml-2 font-medium">{{ $application->email }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-gray-500">Submitted:</span>
                        <span class="ml-2 font-medium">{{ $application->created_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>

                @if($application->status === 'rejected' && $application->rejection_reason)
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded">
                        <span class="text-red-800 font-medium">Rejection Reason:</span>
                        <p class="text-red-700 mt-1">{{ $application->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('home') }}" 
               class="bg-gray-300 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-400 transition-colors">
                Back to Home
            </a>
            
            @if($application->status === 'rejected')
                <a href="{{ route('seller.application.form') }}" 
                   class="bg-primary text-white px-6 py-2 rounded-full hover:bg-primary-hover transition-colors">
                    Reapply
                </a>
            @endif
        </div>
    </div>
</section>
@endsection