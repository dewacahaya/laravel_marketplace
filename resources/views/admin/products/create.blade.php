@extends('dashboard')

@section('admin_content')
    <div class="p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Add New Product</h2>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Name --}}
            <div class="mb-4">
                <label class="block mb-1">Product Name</label>
                <input type="text" name="name" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700"
                    value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div class="mb-4">
                <label class="block mb-1">Category</label>
                <select name="category_id" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700">
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Child Category --}}
            {{-- <div class="mb-4">
                <label class="block mb-1">Child Category</label>
                <select name="child_category_id" id="child_category"
                    class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700">
                    <option value="">-- Select Child Category --</option>
                    @foreach ($categories as $cat => $children)
                        <option value="{{ $children->id }}" {{ old('category_id') == $children->id ? 'selected' : '' }}>
                            {{ $children->name }}
                        </option>
                    @endforeach
                </select>
                @error('child_category_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div> --}}

            {{-- Price --}}
            <div class="mb-4">
                <label class="block mb-1">Price</label>
                <input type="number" name="price" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700"
                    value="{{ old('price') }}">
                @error('price')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Stock --}}
            <div class="mb-4">
                <label class="block mb-1">Stock</label>
                <input type="number" name="stock" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700"
                    value="{{ old('stock') }}">
                @error('stock')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label class="block mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700">{{ old('description') }}</textarea>
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
