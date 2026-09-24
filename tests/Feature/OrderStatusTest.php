<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\Orders;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_an_order_status()
    {
        $customer = Customer::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'paid',
            'total_amount' => 100,
        ]);

        Livewire::test(Orders::class)
            ->set('editingStatuses.' . $order->id, 'cancelled')
            ->call('updateStatus', $order->id);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
    }
}