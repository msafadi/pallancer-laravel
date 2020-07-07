<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Policies\ProductPolicy;
use App\Product;
use App\ProductImage;
use App\Tag;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Throwable;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Product::class);

        /*$products = Product::join('categories', 'categories.id', '=', 'products.category_id')
            ->select([
                'products.*',
                'categories.name as category_name'
            ])
            ->paginate();*/
        $products = Product::with('category')->withoutGlobalScope('ordered')->paginate();

        $cart = request()->cookie('cart');

        return View::make('admin.products.index', [
            'products' => $products,
            'locale' => App::getLocale(),
        ]); // view()
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        /*$request->validate([
            'name' => 'required|string|max:255|min:3',
            'category_id' => 'required|int|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'image',
            'gallery.*' => 'image',
        ]);
        */
        //$this->authorize('create', Product::class);

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

            $this->saveTags($product, $request);
            

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->route('admin.products.index')
                ->with('alert.error', $e->getMessage());
            throw $e;
        }

        // redirect()
        return Redirect::route('admin.products.index')
            ->with('alert.success', "Product ({$product->name}) created!");
    }

    protected function saveTags($product, $request)
    {
        $product_tags = [];
        $tags = explode(',', $request->post('tags'));
        foreach ($tags as $tag) {
            $tag = trim($tag);
            $tagModle = Tag::firstOrCreate([
                'name' => $tag,
            ]);
            /*DB::table('products_tags')->insert([
                'product_id' => $product->id,
                'tag_id' => $tagModle->id,
            ]);*/
            $product_tags[] = $tagModle->id;
        }
        $product->tags()->sync($product_tags);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //$product = Product::findOrFail($id);
        return View::make('admin.products.show', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::withoutGlobalScope('ordered')->find($id);
        /*if (!Gate::allows('products.edit')) {
            abort(403);
        }*/
        //Gate::authorize('products.edit');
        //$product = Product::findOrFail($id);
        $this->authorize('update', $product);

        $gallery = ProductImage::where('product_id',$product->id)->get();
        return View::make('admin.products.edit', [
            'product' => $product,
            'gallery' => $gallery,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        /*if (!Gate::allows('products.edit')) {
            abort(403);
        }*/

        //$product = Product::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'category_id' => 'required|int|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'image',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            if ($product->image && Storage::disk('images')->exists($product->image)) {
                $image_path = $image->storeAs('products', basename($product->image), 'images');
            } else {
                $image_path = $image->store('products', 'images');
            }
            $data['image'] = $image_path;
        }

        $product->update($data);

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

        $this->saveTags($product, $request);

        return Redirect::route('admin.products.index')
            ->with('alert.success', __("Product (:product) updated!", ['product' => $product->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //$product = Product::findOrFail($id);
        Gate::authorize('products.delete', $product);

        /*$response = Gate::inspect('products.delete', $product);

        if (!$response->allowed()) {
            abort(403, $response->message());
        }*/

        
        //$images = ProductImage::where('product_id', $product->id)->get();

        //ProductImage::where('product_id', $id)->delete();
        $product->delete();

        /*if ($product->image) {
            //unlink(public_path('images/' . $product->image));
            Storage::disk('images')->delete($product->image);
        }
        foreach ($images as $image) {
            Storage::disk('images')->delete($image->path);
        }*/

        return Redirect::route('admin.products.index')
            ->with('alert.success', "Product ({$product->name}) deleted!");
    }

    public function trash()
    {
        return view('admin.products.trash', [
            'products' => Product::onlyTrashed()->paginate(),
        ]);
    }

    public function restore(Request $request, $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return Redirect::route('admin.products.index')
            ->with('alert.success', "Product ({$product->name}) restored!");
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        
        $images = $product->images; //ProductImage::where('product_id', $product->id)->get();

        $product->forceDelete();

        if ($product->image) {
            Storage::disk('images')->delete($product->image);
        }
        foreach ($images as $image) {
            Storage::disk('images')->delete($image->path);
        }

        return Redirect::route('admin.products.index')
            ->with('alert.success', "Product ({$product->name}) deleted completely!");
    }

}
