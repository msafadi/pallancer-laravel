<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    //protected $fillable = ['path', 'product_id'];
    protected $guarded = [];

    protected $appends = [
        'image_url'
    ];

    public function getImageUrlAttribute()
    {
        if ($this->attributes['path']) {
            return asset('images/' . $this->attributes['path']);
        }
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'product_id',
            'id'
        );
    }
}
