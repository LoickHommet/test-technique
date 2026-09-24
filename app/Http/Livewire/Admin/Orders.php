<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;

class Orders extends Component
{
    public $search = '';
    public $status = '';
    public $dateFrom = '';
    public $dateTo = '';

    public function render()
    {
        $orders = Order::query()
            ->with('customer')
            ->withCount('details')

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
            })

            ->latest()
            ->get();

        return view('livewire.admin.orders', [
            'orders' => $orders,
        ]);
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'status',
            'dateFrom',
            'dateTo',
        ]);
    }
}