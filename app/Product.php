<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'category_id', 'price', 'image', 'description', 'user_id'
    ];

    protected $appends = [
        'image_url'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getImageUrlAttribute()
    {
        if ($this->attributes['image']) {
            return asset('images/' . $this->attributes['image']);
        }
    }

    public function category()
    {
        return $this->belongsTo(
            Category::class, // realted model
            'category_id', // forigen key in products table for category
            'id' // id of categories table
        );
    }

    public function images()
    {
        return $this->hasMany(
            ProductImage::class,
            'product_id',
            'id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class, // realted model
            'user_id', // forigen key in products table for user
            'id' // id of users table
        );
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'products_tags',
            'product_id',
            'tag_id',
            'id',
            'id'
        );
    }

    public function comments()
    {
        return $this->morphMany(
            Comment::class,
            'commentable'
        );
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
            ->using(OrderProduct::class)
            ->withPivot([
                'quantity', 'price'
            ]);
    }

    public function orderedProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public static function getBestSales($limit = 10)
    {
        /*
SELECT store_products.id, store_products.name, 
(SELECT SUM(store_order_products.quantity) FROM store_order_products WHERE store_order_products.product_id = store_products.id) as sales
FROM store_products
ORDER BY sales DESC
LIMIT 3;
        */
        return Product::select([
            'id', 
            'name',
            'price',
            'image',
            DB::raw('(SELECT SUM(store_order_products.quantity) FROM store_order_products WHERE store_order_products.product_id = store_products.id) as sales'),
        ])
        ->selectRaw('(SELECT store_categories.name FROM store_categories WHERE store_categories.id = store_products.category_id) as category_name')
        ->orderBy('sales', 'DESC')
        ->limit($limit)
        ->get();
    }

    public function scopeHighPrice($query, $min, $max = null)
    {
        $query->where('price', '>=', $min);
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    protected static function booted()
    {
        //parent::booted();

        /*static::addGlobalScope('ordered', function($query) {
            $query->has('orders');
        });*/
        
    }

}
