<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    //
    public function index(Store $store)
    {
        return $store->products;
    }
}
