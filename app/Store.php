<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class, // Related model
            User::class,    // Through model
            'store_id',     // F.K. in through table (users)
            'user_id',      // F.K. in related table (products)
            'id',           // P.K. in local (stores)
            'id'            // p.K. in through (users)
        );
    }
}
