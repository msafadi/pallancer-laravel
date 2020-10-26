<?php

use App\Http\Middleware\CheckUserType;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('comments', function() {
    return Product::find(3)->comments;
});

Route::get('stores/{store}', 'StoresController@index');

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'as' => 'admin.',
    //'middleware' => 'auth:admnin',
    'middleware' => ['auth', 'checkuser:admin,super-admin'],
], function() {

    Route::resource('users', 'UsersController');
    //Route::get('users/{user}', 'UsersController@show');

    Route::get('products/trash', 'ProductsController@trash');
    Route::put('products/{id}/restore', 'ProductsController@restore')->name('products.restore');
    Route::delete('products/{id}/force-delete', 'ProductsController@forceDelete')->name('products.forceDelete');
    Route::resource('products', 'ProductsController');

    Route::prefix('categories')->as('categories.')->middleware('auth')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\CategoriesController::class, 'index'])->name('index');
        Route::get('/create', 'CategoriesController@create')->name('create');
        Route::get('/{id}', 'CategoriesController@show')->name('show');
        Route::get('/{id}/edit', 'CategoriesController@edit')->name('edit')->middleware('can:categories.edit');
        Route::put('/{id}', 'CategoriesController@update')->name('update');
        Route::post('/', 'CategoriesController@store')->name('store');
        Route::delete('/{id}/delete', 'CategoriesController@delete')->name('delete');
        
        Route::get('/{category}/products', 'CategoriesController@products');
    });    

});

Route::namespace('Admin\Auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function() {

        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login');

});





Route::get('custom/login', 'Auth\CustomLoginController@showLoginForm')->name('custom-login');
Route::post('custom/login', 'Auth\CustomLoginController@login');

Route::get('/home', 'HomeController@index')
    ->name('home')
    ->middleware('auth')
    ->middleware('verified');

Route::get('/', 'IndexController@index')->name('frontpage');
Route::prefix('{locale}')->where([
        'locale' => '[a-z]{2}',
    ])->group(function() {
    Route::get('/', 'IndexController@index')->name('frontpage.locale');
    /*Auth::routes([
        'register' => true,
        'verify' => true,
        'reset' => true,
    ]);*/
    Route::get('signout', 'Auth\LoginController@signout')->name('signout');

    Route::get('products/{product}', 'ProductsController@show')->name('products.show');
});

Route::get('notifications', 'NotificationsController@index');
Route::get('notifications/{id}', 'NotificationsController@read')->name('notification.read');

Route::get('cart', 'CartController@index')->name('cart');
Route::post('cart', 'CartController@store');

Route::get('checkout', 'CheckoutController@index')->name('checkout');
Route::post('checkout', 'CheckoutController@checkout');

Route::get('orders', 'OrdersController@index')->name('orders');

Route::get('paypal/return', 'CheckoutController@paypalReturn')->name('paypal.return');
Route::get('paypal/cancel', 'CheckoutController@paypalCancel')->name('paypal.cancel');

Route::get('storage/{file}', function($file) {
    $path = storage_path('app/public/' . $file);
    if (!is_file($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('file', '.*');

Route::get('public-path', function() {
    return public_path();
});

Route::get('reset-admin', function() {
    User::where('id', 1)->update([
        'password' => Hash::make('123456'),
    ]);

    
});
Route::middleware(['auth:sanctum'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
