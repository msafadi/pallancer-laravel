<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class IndexController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('index', [
            'products' => $products,
        ]);
    }
}
