<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')
            ->using(OrderProduct::class)
            ->withPivot([
                'price', 'quantity'
            ])
            ->as('order_products');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
