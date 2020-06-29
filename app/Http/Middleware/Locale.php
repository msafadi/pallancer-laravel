<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $locale = Route::getCurrentRoute()->parameter('locale');
        if (!$locale) {
            $locale = ($user && $user->locale)
                ? $user->locale
                : config('app.locale');
        }

        URL::defaults([
            'locale' => $locale,
        ]);
        
        App::setLocale($locale);
        
        return $next($request);
    }
}
