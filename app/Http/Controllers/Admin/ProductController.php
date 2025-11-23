<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Http\Requests\Vendor\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\Admin\CategoryService;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $service;
    protected $categoryService;

    public function __construct(ProductService $service, CategoryService $categoryService)
    {
        $this->service = $service;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $products = $this->service->getAllPaginated();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $vendors = User::where('role', 'vendor')->get();
        $categories = $this->categoryService->getParents();
        return view('admin.products.create', compact('vendors', 'categories'));
    }

    public function store(ProductRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = $this->service->find($id);

        // ALL parent categories
        $parentCategories = Category::whereNull('parent_id')->get();

        // Cek apakah produk memakai child
        $childCategory = Category::find($product->category_id);
        $parentCategorySelected = $childCategory->parent_id ?? $product->category_id;

        // Ambil child dari parent yang dipilih
        $childCategories = Category::where('parent_id', $parentCategorySelected)->get();

        return view('admin.products.edit', compact(
            'product',
            'parentCategories',
            'parentCategorySelected',
            'childCategories'
        ));
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $this->service->updateForAdmin($product, $request->validated());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
