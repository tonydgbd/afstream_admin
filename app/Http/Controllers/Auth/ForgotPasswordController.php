<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Modules\Setting\Entities\Settings;
use Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm(){
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request){
        
        $home = getSelectedHomepage();
        
        $credentials = request()->validate(['email' => 'required|email']);

        $checkSmtpSetting = Settings::where('name', 'is_smtp')->first();
        
        if(!empty($checkSmtpSetting) && $checkSmtpSetting->value == 1){
            try{           
                Password::sendResetLink($credentials);
                toastr()->success( 'Reset email link has been sent to email '.$request->email, '', ['timeOut' => 6000, 'progressBar' =>false] );
                return redirect()->route($home);
            }catch(\Exception $e){
                toastr()->error( __('adminWords.smtp_setting_error'), '', ['timeOut' => 6000, 'progressBar' =>false] );
                return redirect()->route($home);
            }                      
        }else{
            toastr()->error( __('adminWords.smtp_setting_error'), '', ['timeOut' => 6000, 'progressBar' =>false] );
            return redirect()->route($home);
        }       

    }
}
