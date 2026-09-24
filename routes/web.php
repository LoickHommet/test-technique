<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\Orders;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/orders', Orders::class)
    ->name('admin.orders');

    