<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $editingStatuses = [];

    public function render()
    {
        $orders = $this->filteredOrdersQuery()
            ->with('customer')
            ->withCount('details')
            ->latest()
            ->paginate(20);

        foreach ($orders as $order) {
            if (!array_key_exists($order->id, $this->editingStatuses)) {
                $this->editingStatuses[$order->id] = $order->status;
            }
        }

        $orderCount = $this->filteredOrdersQuery()->count();

        $revenue = $this->filteredOrdersQuery()
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $nonCancelledOrderCount = $this->filteredOrdersQuery()
            ->where('status', '!=', 'cancelled')
            ->count();

        $averageBasket = $nonCancelledOrderCount > 0
            ? $revenue / $nonCancelledOrderCount
            : 0;

        return view('livewire.admin.orders', [
            'orders' => $orders,
            'orderCount' => $orderCount,
            'revenue' => $revenue,
            'averageBasket' => $averageBasket,
        ]);
    }

    private function filteredOrdersQuery()
    {
        return Order::query()
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($query) use ($search) {
                    $query->where('reference', 'like', $search)
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('firstname', 'like', $search)
                                ->orWhere('lastname', 'like', $search)
                                ->orWhere('email', 'like', $search);
                        });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            });
    }

    public function updateStatus($orderId)
    {
        $this->validate([
            'editingStatuses.' . $orderId => [
                'required',
                'in:' . implode(',', Order::STATUSES),
            ],
        ]);

        $order = Order::findOrFail($orderId);

        $order->update([
            'status' => $this->editingStatuses[$orderId],
        ]);

        session()->flash(
            'success',
            'Le statut de la commande a été mis à jour.'
        );
    }


    public function resetFilters()
    {
        $this->reset([
            'search',
            'status',
            'dateFrom',
            'dateTo',
        ]);

        $this->resetPage();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }
}
