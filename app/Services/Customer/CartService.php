<?php

namespace App\Services\Customer;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart(): array
    {
        $cart = Session::get('cart', []);

        // 1. Ambil ID produk dari keranjang
        $productIds = array_keys($cart);

        // 2. Ambil stok terbaru dari database (hanya field yang diperlukan)
        // Menggunakan pluck untuk mendapatkan array [id => stock]
        $productStocks = Product::whereIn('id', $productIds)
            ->pluck('stock', 'id')
            ->toArray();

        // 3. Iterasi dan validasi/perbarui
        foreach ($cart as $productId => &$item) { // Menggunakan reference (&) untuk modifikasi langsung
            $currentStock = $productStocks[$productId] ?? null;

            if ($currentStock === null) {
                // Produk tidak ditemukan, hapus dari keranjang
                unset($cart[$productId]);
                continue;
            }

            // A. Update stok dan validasi kuantitas
            $item['stock'] = (int) $currentStock;

            if ($item['quantity'] > $item['stock']) {
                $item['quantity'] = $item['stock']; // Batasi quantity ke stok yang tersedia
            }

            // B. Recalculate subtotal
            $item['subtotal'] = $this->calculateSubtotal($item['price'], $item['quantity']);
        }

        // Hapus reference setelah loop selesai (praktik yang baik)
        unset($item);

        // Simpan keranjang yang sudah divalidasi/diperbaiki
        Session::put('cart', $cart);

        return $cart;
    }

    public function addToCart(Product $product): void
    {
        $cart = $this->getCart();
        $productId = $product->id;

        if (!isset($cart[$productId])) {
            $cart[$productId] = [
                'product_id' => $productId,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => 0,
                'stock' => (int) $product->stock,
                'subtotal' => 0,
            ];
        }

        // Proses increment qty, hanya jika belum mencapai stok
        if ($cart[$productId]['quantity'] < $product->stock) {
            $cart[$productId]['quantity']++;
        }

        // calculating subtotal
        $cart[$productId]['subtotal'] = $this->calculateSubtotal($cart[$productId]['price'], $cart[$productId]['quantity']);

        Session::put('cart', $cart);
    }

    public function removeItem(int $productId): void
    {
        $cart = $this->getCart();
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }
    }

    public function updateQuantity(int $productId, int $newQuantity): bool
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            return false; // Item tidak ditemukan
        }

        $item = &$cart[$productId]; // Gunakan reference untuk modifikasi langsung
        $stock = $item['stock'];

        // 1. Validasi kuantitas: harus > 0 dan <= stock
        if ($newQuantity <= 0) {
            unset($cart[$productId]); // Jika 0 atau kurang, hapus item
        } elseif ($newQuantity > $stock) {
            $item['quantity'] = $stock; // Jika melebihi stok, set ke max stok
        } else {
            $item['quantity'] = $newQuantity;
        }

        // 2. Jika item masih ada, hitung ulang subtotal
        if (isset($cart[$productId])) {
            $cart[$productId]['subtotal'] = $this->calculateSubtotal($item['price'], $item['quantity']);
        }
        
        unset($item);
        Session::put('cart', $cart);
        return true;
    }

    private function calculateSubtotal(float $price, int $quantity): float
    {
        return round($price * $quantity, 2);
    }
}
