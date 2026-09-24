<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;

class Orders extends Component
{
    public function render()
    {
        $orders = Order::query()
            ->with('customer')
            ->withCount('details')
            ->latest()
            ->get();

        return view('livewire.admin.orders', [
            'orders' => $orders,
        ]);
    }
}