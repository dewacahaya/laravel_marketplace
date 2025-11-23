@extends('dashboard')

@section('admin_content')
    <div class="p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold">Category List</h2>
            <a href="{{ route('admin.categories.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                + Add Category
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-600 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if ($categories->isEmpty())
            <p class="text-gray-500 italic">No categories available.</p>
        @else
            <div class="space-y-4">
                @foreach ($categories as $category)
                    {{-- Parent Category --}}
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-lg text-gray-900 dark:text-gray-100">
                                    {{ $category->name }}
                                </span>
                                <span class="text-sm text-gray-500 ml-2">
                                    (created: {{ $category->created_at->format('Y-m-d') }})
                                </span>
                            </div>

                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                    class="text-indigo-600 hover:underline">Edit</a>

                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this parent category (and its children)?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>

                                <a href="{{ route('admin.categories.addChild', ['parent_id' => $category->id]) }}"
                                    class="text-green-600 hover:underline">+ Add Child</a>
                            </div>
                        </div>

                        {{-- Child Categories --}}
                        @if ($category->children->isNotEmpty())
                            <div class="mt-3 ml-6 border-l border-gray-300 dark:border-gray-600 pl-4 space-y-2">
                                @foreach ($category->children as $child)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-gray-800 dark:text-gray-200">
                                                • {{ $child->name }}
                                            </span>
                                            <span class="text-sm text-gray-500 ml-2">
                                                ({{ $child->created_at->format('Y-m-d') }})
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.categories.edit', $child->id) }}"
                                                class="text-indigo-600 hover:underline text-sm">Edit</a>

                                            <form action="{{ route('admin.categories.destroy', $child->id) }}"
                                                method="POST" onsubmit="return confirm('Delete this child category?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:underline text-sm">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
