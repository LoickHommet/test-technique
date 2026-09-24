<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'active',
    ];

     protected $casts = [
        'active' => 'boolean',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
