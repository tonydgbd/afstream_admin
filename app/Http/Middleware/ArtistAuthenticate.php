<?php

namespace App\Http\Middleware;

use Modules\Setting\Entities\Settings;
use Illuminate\Http\Request;
use Closure;
use Auth;

class ArtistAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $home = getSelectedHomepage();
        
        if (Auth::check()) {

            if(Auth::user()->role == 2){
                
                if(Auth::check() && Auth::user()->status == '1'){ 

                    if(Auth::check() && Auth::user()->artist_verify_status == 'P'){ 
                        toastr()->error( __('adminWords.profile_isin_review') );    
                        return redirect()->route($home);
                    }elseif(Auth::check() && Auth::user()->artist_verify_status == 'R'){
                        toastr()->error( __('adminWords.profile_is_rejected') );    
                        return redirect()->route($home);
                    }elseif(Auth::check() && Auth::user()->artist_verify_status == 'A'){   
                        return $next($request);
                    }
                }else{
                    Auth::logout();
                    toastr()->error( __('frontWords.login_success'), '', ['timeOut' => 8000, 'progressBar' =>false] );
                    return redirect()->route($home);
                }
            }   
        }else{
            toastr()->error(__('frontWords.login_err'), '', ['timeOut' => 8000, 'progressBar' =>false]);
            return redirect()->route($home);
        }
        return $next($request);
    }
}
