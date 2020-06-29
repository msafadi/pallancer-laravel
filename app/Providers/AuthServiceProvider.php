<?php

namespace App\Providers;

use App\Product;
use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        //\App\Product::class => \App\Policies\ProductPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function($user, $ability) {
            if ($user->type == 'super-admin') {
                return true;
            }
            //return false;
        });

        foreach (config('permissions') as $name => $label) {
            Gate::define($name, function(User $user) use ($name) {
                return $user->hasPermission($name);
            });
        }

        Gate::define('products.delete', function(User $user, Product $product) {
            if ($product->user_id != $user->id) {
                return Response::deny('You are not the owner of the product!');
            }
            return $user->hasPermission('products.delete');
        });

        Gate::define('categories.create', 'App\Policies\CategoryPolicy@create');

        /*
        Gate::define('products.edit', function(User $user) {
            return $user->hasPermission('products.edit');
        });

        Gate::define('categories.edit', function(User $user) {
            return $user->hasPermission('products.edit');
        });*/

        //dd(Gate::abilities());

        //
    }
}
