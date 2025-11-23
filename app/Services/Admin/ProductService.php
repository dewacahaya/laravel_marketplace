<?php

namespace App\Services\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getAllPaginated($perPage = 10)
    {
        return Product::with(['category', 'vendor'])
            ->latest()
            ->paginate($perPage, );
    }

    public function find($id)
    {
        return Product::with('category.parent')->findOrFail($id);
    }

    public function create(array $data)
    {
        $user = auth()->user();

        // === SLUG ===
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // === VENDOR HANDLING ===
        if ($user->role === 'admin') {
            // Admin menentukan vendor secara manual
            // (validasinya sudah dijaga di ProductRequest)
        } else {
            // Vendor membuat produk → vendor_id otomatis
            $data['vendor_id'] = $user->id;
        }

        // === IMAGE ===
        if (isset($data['image']) && $data['image']->isValid()) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->find($id);
        $user = auth()->user();

        // === SLUG ===
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // === VENDOR HANDLING ===
        if ($user->role !== 'admin') {
            $data['vendor_id'] = $product->vendor_id;
        }

        // === IMAGE HANDLING ===
        if (isset($data['image']) && $data['image']->isValid()) {

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $data['image']->store('products', 'public');
        }

        $product->update($data);

        return $product;
    }

    public function updateForAdmin(Product $product, array $data)
    {
        // Tentukan category final
        $finalCategoryId = $data['child_category_id']
            ? $data['child_category_id']
            : $data['parent_category_id'];

        // Update product
        $product->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? $product->description,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'category_id' => $finalCategoryId,
        ]);

        return $product;
    }

    public function delete($id)
    {
        $product = $this->find($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        return $product->delete();
    }

    public function deleteByVendor($id)
    {
        $product = Product::where('user_id', auth()->id())
            ->findOrFail($id);

        $product->delete();
    }

    public function restore($id)
    {
        return Product::withTrashed()->find($id)->restore();
    }
}
