<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    //protected $fillable = ['path', 'product_id'];
    protected $guarded = [];


    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'product_id',
            'id'
        );
    }
}
