<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Elektronik' => ['Laptop', 'Handphone', 'Televisi'],
            'Furniture' => ['Meja', 'Kursi', 'Lemari'],
            'Fashion' => ['Pakaian Pria', 'Pakaian Wanita', 'Aksesoris'],
            'Olahraga' => ['Sepeda', 'Peralatan Gym', 'Pakaian Olahraga'],
            'Kecantikan' => ['Skincare', 'Makeup', 'Parfum'],
            'Makanan' => ['Snack', 'Mie Instan', 'Frozen Food'],
            'Buku' => ['Novel', 'Notebook', 'Komik'],
            'Mainan & Hobi' => ['Action Figure', 'Lego', 'RC Toys'],
            'Minuman' => ['Kopi', 'Teh', 'Yogurt'],
            'Alat Tulis' => ['Pensil', 'Penghapus', 'Bolpoin'],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::create([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
                'parent_id' => null,
            ]);

            foreach ($children as $childName) {
                Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
