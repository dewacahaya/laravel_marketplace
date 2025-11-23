<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Vendor\ProductService;
use App\Http\Requests\Vendor\ProductRequest;
use App\Models\Category;

class ProductController extends Controller
{
    protected $service, $categoryService;

    public function __construct(ProductService $service)
    {
        $this->middleware(['auth', 'role:vendor']);
        $this->service = $service;
    }

    public function index()
    {
        $products = $this->service->getAll();
        return view('vendor.products.index', compact('products'));
    }

    public function create()
    {
        $childs = Category::where('parent_id', '!=', null)->orderBy('name')->get();

        return view('vendor.products.create', compact( 'childs'));
    }

    public function store(ProductRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('vendor.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = $this->service->find($id);
        $childs = Category::where('parent_id', '!=', null)->orderBy('name')->get();

        return view('vendor.products.edit', compact('product', 'childs'));
    }

    public function update(ProductRequest $request, $id)
    {
        $product = $this->service->find($id);
        $this->service->update($product, $request->validated());

        return redirect()->route('vendor.products.index')->with('success', 'Product updated.');
    }

    public function destroy($id)
    {
        $product = $this->service->find($id);
        $this->service->delete($product);

        return redirect()->route('vendor.products.index')->with('success', 'Product deleted.');
    }
}
