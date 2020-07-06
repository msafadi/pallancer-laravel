<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductsController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api')->except('index', 'show');
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = request();
        $category_id = $request->input('category_id');
        $keyword = $request->input('q');

        $products = Product::when($category_id, function($query, $category_id) {
            return $query->where('category_id', $category_id);
        })
        ->when($keyword, function($query, $keyword) {
            return $query->where('name', 'LIKE', "%$keyword%")
                         ->orWhere('description', 'LIKE', "%$keyword%");
        })
        ->with('category')
        ->paginate();

        return $products;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'category_id' => 'required|int|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'image',
            'gallery.*' => 'image',
        ]);

        $image_path = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $image_path = $image->store('products', 'images');
        }

        $data = $request->all();
        $data['image'] = $image_path;
        $data['user_id'] = Auth::id();

        //$data['description'] = strip_tags($data['description'], '<p><h1><h2>');

        DB::beginTransaction();
        try {
            $product = Product::create($data);

            if ($request->hasFile('gallery')) {
                $images = $request->file('gallery');
                foreach ($images as $image) {
                    if ($image->isValid()) {
                        $image_path = $image->store('products', 'images');
                        ProductImage::create([
                            'product_id' => $product->id,
                            'path' => $image_path,
                        ]);
                    }
                }
            }            

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'code' => 422,
                'message' => $e->getMessage(),
            ], 422);
        }

        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'code' => 404,
                'message' => 'Product not found.'
            ], 404);
        }
        //return $product->image_url;
        return $product->load('category', 'images', 'tags');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->user()->tokenCan('products.create')) {
            return 'UPDATED';
        }
        return 'NOT AUTHORIZED!';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
