<?php

// CRUD : Create, Read, Update, Delete

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\CssSelector\Node\FunctionNode;

class CategoriesController extends Controller
{
    public function index()
    {
        //$categories = Category::all(); // Collection Iteratable

        /*$categories = Category::leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->select([
                'categories.*',
                'parents.name as parent_name',

            ])
            ->paginate(2);
        
        return Category::leftJoin('products', 'products.category_id', '=', 'categories.id')
                ->select([
                    'categories.id',
                    'categories.name',
                    DB::raw('COUNT(store_products.id) as prodcuts_count')
                ])
                ->groupBy([
                    'categories.id',
                    'categories.name',
                ])->get();*/
        
        $categories = Category::with('parent')->withCount('products')->paginate(2);

        return view('admin.categories.index', [
            'entries' => $categories,
        ]);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.show', [
            'category' => $category,
        ]);
    }

    public function create()
    {
        Gate::authorize('categories.create');

        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('categories.create');
        $this->checkRequest($request);

        $category = Category::create([
            'name' => $request->name,
            'parent_id' => $request->input('parent_id'),
            'status' => $request->post('status'), // Recommended
        ]);
        return redirect()
            ->route('admin.categories.index')
            ->with('alert.success', "Category \"{$category->name}\" created!");
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        //$this->checkRequest($request);
        $validator = Category::getValidator($request->all(), $id);
        //$validator->validate();
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        //Category::where('id', $id)->update($request->all());
        $category = Category::findOrFail($id);
        $category->update($request->all());
        
        return redirect()
            ->route('admin.categories.index')
            ->with('alert.success', "Category \"{$category->name}\" updated!");
    }

    public function delete($id)
    {
        //Category::where('id', $id)->delete();
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()
            ->route('admin.categories.index')
            ->with('alert.success', "Category \"{$category->name}\" deleted!");
    }

    public function products(Category $category)
    {
        $request = request();
        return $category->products()
            ->with('user.store')
            ->get();
    }

    protected function checkRequest(Request $request, $except = 0)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'unique:categories,name,' . $except,
            ],
            'parent_id' => [
                'nullable',
                'int',
                'exists:categories,id'
            ],
            'status' => [
                'required',
                'string',
                'in:published,draft'
            ],
        ]);
    }
}
