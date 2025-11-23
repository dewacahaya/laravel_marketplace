<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function getPaginatedWithChildren($perPage = 5)
    {
        return Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    public function getParents()
    {
        return Category::whereNull('parent_id')->orderBy('name')->get();
    }

    public function getParentsExcept($id)
    {
        return Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->orderBy('name')
            ->get();
    }

    public function getChildren($parentId)
    {
        return Category::where('parent_id', $parentId)
            ->orderBy('name')
            ->get();
    }


    public function find($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $category = $this->find($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = $this->find($id);

        $category->children()->delete();
        return $category->delete();
    }

    public function restore($id)
    {
        Category::withTrashed()->find($id)->restore();
    }

}
