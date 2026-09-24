<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::factory()
            ->count(100)
            ->create();

        $products = Product::factory()
            ->count(150)
            ->create();

        Order::factory()
            ->count(2000)
            ->make()
            ->each(function ($order) use ($customers, $products) {
                $order->customer_id = $customers->random()->id;
                $order->save();

                $detailsCount = rand(1, 4);
                $total = 0;

                for ($i = 0; $i < $detailsCount; $i++) {
                    $product = $products->random();
                    $quantity = rand(1, 5);

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                    ]);

                    $total += $quantity * $product->price;
                }

                $order->update([
                    'total_amount' => $total,
                ]);
            });
    }
}