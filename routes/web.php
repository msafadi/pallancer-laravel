<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'as' => 'admin.',
], function() {

    Route::resource('products', 'ProductsController');

    Route::prefix('categories')->as('categories.')->group(function() {
        Route::get('/', 'CategoriesController@index')->name('index');
        Route::get('/create', 'CategoriesController@create')->name('create');
        Route::get('/{id}', 'CategoriesController@show')->name('show');
        Route::get('/{id}/edit', 'CategoriesController@edit')->name('edit');
        Route::put('/{id}', 'CategoriesController@update')->name('update');
        Route::post('/', 'CategoriesController@store')->name('store');
        Route::delete('/{id}/delete', 'CategoriesController@delete')->name('delete');
    });

    

});


