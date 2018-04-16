<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class setlang
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
        App::setLocale('en');
        if (session()->exists('lang')) {
//            echo(session('lang'));
            App::setLocale(session("lang"));
        }
        $res = $next($request);
        return $res;
    }
}
