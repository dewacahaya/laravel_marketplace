<?php

namespace App\Services\Vendor;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductService
{
    public function getAll()
    {
        return Product::where('vendor_id', Auth::id())->paginate(10);
    }

    public function create(array $data)
    {
        $user = auth()->user();

        // SLUG
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // vendor id otomatis
        $data['vendor_id'] = $user->id;

        // kategori adalah child, bukan parent
        $data['category_id'] = $data['child_category_id'];
        unset($data['child_category_id']);

        // IMAGE upload
        if (isset($data['image']) && $data['image']->isValid()) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        return Product::create($data);
    }


    public function find($id)
    {
        return Product::where('id', $id)
            ->where('vendor_id', Auth::id())
            ->firstOrFail();
    }

    public function update(Product $product, array $data)
    {
        // Handle kategori
        $finalCategoryId = $data['child_category_id'] ?? $data['parent_category_id'];

        // Update image jika upload baru
        if (isset($data['image']) && $data['image']->isValid()) {
            $data['image'] = $data['image']->store('products', 'public');
        } else {
            unset($data['image']); // jaga agar tidak overwrite jadi null
        }

        // Update langsung 1x saja
        return $product->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? $product->description,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'category_id' => $finalCategoryId,
            'image' => $data['image'] ?? $product->image,
            'status' => $data['status'],
        ]);
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }
}
