@extends('dashboard')

@section('vendor_content')
    <div class="p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Edit Product</h2>

        <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="mb-4">
                <label class="block mb-1">Product Name</label>
                <input type="text" name="name" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700"
                    value="{{ old('name', $product->name) }}">
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div class="mb-4">
                <label class="block mb-1">Category</label>
                <select name="child_category_id"
                    class="form-select w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700">
                    @foreach ($childs as $child)
                        <option value="{{ $child->id }}" {{ $product->category_id == $child->id ? 'selected' : '' }}>
                            {{ $child->name }}
                        </option>
                    @endforeach
                </select>
                @error('child_category_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Price --}}
            <div class="mb-4">
                <label class="block mb-1">Price</label>
                <input type="number" name="price" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700"
                    value="{{ old('price', $product->price) }}">
                @error('price')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Stock --}}
            <div class="mb-4">
                <label class="block mb-1">Stock</label>
                <input type="number" name="stock" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700"
                    value="{{ old('stock', $product->stock) }}">
                @error('stock')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label class="block mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Show existing image --}}
            <div class="mb-4">
                <label class="block mb-1">Current Image</label>
                <img src="{{ asset('storage/' . $product->image) }}" class="h-20 rounded mb-2" alt="Product Image">
            </div>

            {{-- Upload new image --}}
            <div class="mb-4">
                <label class="block mb-1">Replace Image (optional)</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700">
                @error('image')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="block mb-1">Status</label>
                <select name="status" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700">
                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="out_of_stock" {{ $product->status == 'out_of_stock' ? 'selected' : '' }}>Out of Stock
                    </option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                Update Product
            </button>
        </form>
    </div>
@endsection
