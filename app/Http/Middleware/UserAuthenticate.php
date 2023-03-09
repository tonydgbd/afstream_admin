<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class UserAuthenticate
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
        if (Auth::check()) {
            return $next($request);
        }else{
            toastr()->error(__('frontWords.login_err'), '', ['timeOut' => 2000, 'progressBar' =>false]);
            return back();
        }
    }
}
