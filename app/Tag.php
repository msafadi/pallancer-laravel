<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'products_tags',
            'tag_id',
            'product_id',
            'id',
            'id'
        );
    }
}
