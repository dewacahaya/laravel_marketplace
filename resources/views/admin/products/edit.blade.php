@extends('dashboard')

@section('admin_content')
    <div class="p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Edit Product</h2>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
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

            {{-- Parent Category --}}
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Parent Category</label>
                <select name="parent_category_id"
                    class="form-select w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700" x-model="parent"
                    @change="fetchChildren">
                    <option value="">-- Select Parent Category --</option>
                    @foreach ($parentCategories as $cat)
                        <option value="{{ $cat->id }}" {{ $cat->id == $parentCategorySelected ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Child Category --}}
            <div class="mb-4">
                <label class="block mb-1">Child Category</label>
                <select name="child_category_id"
                    class="form-select w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700" id="child_category">
                    <option value="">-- Pilih Child Category --</option>
                    @foreach ($childCategories as $child)
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

            {{-- Image --}}
            <div class="mb-4">
                <label class="block mb-1">Product Image</label>
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
                    <option value="active">Active</option>
                    <option value="draft">Draft</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                Save Product
            </button>
        </form>
    </div>
@endsection
