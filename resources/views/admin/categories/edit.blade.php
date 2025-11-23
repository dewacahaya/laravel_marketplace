@extends('dashboard')

@section('admin_content')
    <div class="p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Edit Category</h2>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1">Category Name</label>
                <input type="text" name="name" class="w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700"
                    value="{{ old('name', $category->name) }}">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
@endsection
