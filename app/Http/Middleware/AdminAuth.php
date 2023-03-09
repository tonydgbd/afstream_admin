<?php

namespace App\Http\Middleware;

use Modules\Setting\Entities\Settings;
use Illuminate\Http\Request;
use Closure;
use Auth;

class AdminAuth
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
       
        $home = getSelectedHomepage();
        if (Auth::check()) {

            if(Auth::user()->role == 1){
                return $next($request);
            }else{
                return redirect()->route($home);
            }
            
        }else{
            toastr()->error(__('frontWords.login_err'), '', ['timeOut' => 6000, 'progressBar' =>false]);
            return redirect()->route($home);
        }
        
    }
}
