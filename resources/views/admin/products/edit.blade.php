@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-2xl px-6 py-12">
    <div class="mb-6">
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">" class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    <h2 class="text-2xl font-semibold">Edit Product</h2>
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4 rounded-xl bg-white shadow-soft p-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm text-text-secondary">Product Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
            @error('name') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="block text-sm text-text-secondary">Description</label>
            <textarea name="description" rows="3" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description', $product->description) }}</textarea>
            @error('description') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm text-text-secondary">Price</label>
                <input type="number" name="price" step="1" min="0" value="{{ old('price', $product->price) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
                @error('price') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm text-text-secondary">Quantity</label>
                <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" required>
                @error('stock') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm text-text-secondary">Product Image</label>
                @if($product->image)
                    <div class="mb-2">
                        <img src="{{ $product->image }}" alt="Current Image" class="h-16 w-16 object-cover rounded-md">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2">
                @error('image') <div class="text-danger text-sm mt-1">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-4">
            <a href="{{ route('admin.products.index') }}" class="rounded-full border border-gray-300 px-6 py-3 hover:bg-gray-50">Cancel</a>
            <button class="rounded-full bg-primary text-white px-6 py-3 hover:bg-primary-hover">Update Product</button>
        </div>
    </form>
</section>
@endsection