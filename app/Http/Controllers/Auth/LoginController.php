<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use App\User;
use Illuminate\Support\MessageBag;
class LoginController extends Controller
{
   
    use AuthenticatesUsers;
    public function __construct(){
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(){
        return view('auth.login');
    } 
    
    public function authenticated(Request $request){
        $checkValidate = validation($request->except('_token'), ['email' => 'required|email', 'password' => 'required']);
        if($request->email == '' || strpos($request->email, '@') == '')
            $errVar = 'email';
        else
            $errVar = 'password';
        if($checkValidate['status'] == 1){
            $arr = ['email' => $request->email , 'password' => $request->password];
           
            
            if (Auth::attempt($arr, $request->remember)){
                if(Auth::user()->role == 1){
                    return Redirect::intended('/admin');
                }else{
                    Auth::logout();
                    $errors = new MessageBag([$errVar => [__('frontWords.deactivate_acc')]]);
                    return back()->withInput($request->except('password'))->withErrors($errors);
                }
            }else{
                Auth::logout();
                $errors = new MessageBag([$errVar => [__('frontWords.credential_err')]]);
                return back()->withInput($request->except('password'))->withErrors($errors);
            }
        }else{
            Auth::logout();
            $errors = new MessageBag([$errVar => [$checkValidate['msg']]]);
            return back()->withInput($request->except('password'))->withErrors($errors);
            
        }
    }

    Public function logout(Request $request){
        Auth::logout();
        return redirect()->route('login');
    }
}

