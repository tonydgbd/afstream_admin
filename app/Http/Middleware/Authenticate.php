<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    
            
        public function redirectTo($request){
            
            if ($request->expectsJson()) {
                if (!Auth::check()) { 
                    $response = [];
                    $response['status'] = false;
                    $response['msg'] = 'Unauthenticated';
                    return response()->json($response);
                }

            }else{
                if (! $request->expectsJson()) { 
                    return route('login');
                }
            }
        }
}       
