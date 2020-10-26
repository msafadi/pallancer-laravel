<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (App::environment('production')) {
            app()->bind('path.public', function($app) {
                return base_path('public_html');
            });
        }

        URL::defaults([
            'locale' => config('app.locale'),
        ]);
        
        Paginator::useBootstrap();
        //Paginator::defaultView('vendor.pagination.bootstrap-4');
        //Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-4');
    }
}
