<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $chunkSize = 100;

        collect(range(1, 500))->chunk($chunkSize)->each(function ($chunk) {
            $orders = Order::factory(count($chunk))->create();

            $orders->each(function ($order) {
                $itemsCount = rand(1, 5);
                $total = 0;

                $items = OrderItem::factory($itemsCount)->make();
                $items->each(function ($item) use (&$total, $order) {
                    $item->order_id = $order->id;
                    $total += $item->price * $item->qty;
                    $item->save();
                });

                $order->update(['total_price' => $total]);
            });
        });
    }
}
