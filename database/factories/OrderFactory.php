<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'reference' => strtoupper($this->faker->unique()->bothify('ORD-########')),
            'status' => $this->faker->randomElement(Order::STATUSES),
            'total_amount' => 0,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}