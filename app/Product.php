<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'category_id', 'price', 'image', 'description',
    ];

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
}
