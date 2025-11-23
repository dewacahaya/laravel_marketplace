<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $categories = $this->service->getPaginatedWithChildren();
        return view('admin.categories.index', compact('categories'));
    }


    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $parents = null;

        if ($parentId) {
            $parents = Category::find($parentId);
        }

        return view('admin.categories.create', compact('parents', 'parentId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $this->service->create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function addChild(Request $request)
    {
        $parentId = $request->query('parent_id');
        $parent = $this->service->find($parentId);

        // form ini tidak perlu dropdown parent, cukup hidden input
        return view('admin.categories.addChild', compact('parent'));
    }

    public function storeChild(CategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['parent_id'] = $request->input('parent_id'); // pastikan fixed ke parent-nya

        $this->service->create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Child category created successfully.');
    }

    public function edit($id)
    {
        $category = $this->service->find($id);
        $parents = $this->service->getParentsExcept($id);

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(CategoryRequest $request, $id)
    {
        $this->service->update($id, $request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
