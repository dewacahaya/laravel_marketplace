<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'child_category_id' => [
                'required',
                'exists:categories,id'
            ],
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,draft,out_of_stock',
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'child_category_id.required' => 'Category is required.',
            'name.required' => 'Product name is required.',
            'slug.required' => 'Slug is required.',
            'slug.unique' => 'This slug already exists.',
            'price.numeric' => 'Price must be a number.',
            'stock.integer' => 'Stock must be an integer.',
            'image.image' => 'The file must be an image.',
            'status.in' => 'Invalid product status selected.',
        ];
    }
}
