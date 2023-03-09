<?php

namespace App\Providers;

use Modules\AudioLanguage\Entities\AudioLanguage;
use Modules\AdminPlaylist\Entities\AdminPlaylist;
use Modules\Language\Entities\Language;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Modules\Setting\Entities\Settings;
use Modules\Setting\Entities\Currency;
use Modules\Coupon\Entities\Coupon;
use Modules\Plan\Entities\Plan;
use Illuminate\Support\Carbon;
use App\UserPurchasedPlan;
use App\Playlist;
use App\User;
use Session;
use Auth;

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
    public function boot(){
        
        Schema::defaultStringLength(191);
        
        view()->composer('*', function($view){

            $is_youtube = '';
            $is_dashboard = Settings::where('name', 'is_dashboard')->first();
            $check_youtube = Settings::where('name', 'is_youtube')->first();

            if(isset($is_dashboard) && !empty($is_dashboard->value) && $is_dashboard->value != 'dashboard') $homepage = 'home_2'; else $homepage = 'home';            
            if(!empty($check_youtube) && $check_youtube['value'] == 1) $is_youtube = 1; else $is_youtube = 0;       

            $setDefaultLan = Language::select('language_code')->where('is_default', 1)->first();
            if(!empty($setDefaultLan)){               
                Session::put('locale', $setDefaultLan->language_code);
                \App::setLocale(Session::get('locale'));
            }

            $dc = Settings::where('name', 'default_currency_id')->first();
            $defaultCurrency = [];
            if(!empty($dc)){
                $defaultCurrency = Currency::where('id',$dc->value)->first();
            }
            $settings = Settings::pluck('value','name');
            if(!empty($settings)){
                $title = '';
                if(isset($settings['w_title'])){
                    $title = $settings['w_title'];
                    if(isset($settings['meta_desc'])){
                        $title .= '-'.$settings['meta_desc'];
                    }
                }else{
                    $title = 'Musioo-Dream your moments';
                }
            }
            $language = Language::where('status',1)->pluck('language_name','id')->all();
            $audioLanguage = AudioLanguage::where('status',1)->pluck('language_name','id')->all();
            $countAdminPlaylist = AdminPlaylist::where('status','1')->count();

            $playlist = [];
            $planDetail = [];
            $plan_expired_ago = '';
            if(isset(Auth::user()->id)){
                $playlist = Playlist::where('user_id',Auth::user()->id)->orderBy('id','desc')->get();
                $planData = UserPurchasedPlan::where(['user_id' => Auth::user()->id ,  ['expiry_date', '>=', date('Y-m-d')] ])->orderBy('id', 'desc')->limit(1)->get();
                
                if(!$planData->isEmpty()){
                    $plan_expired_ago = Carbon::parse($planData[0]->expiry_date)->diffForHumans(Carbon::now());
                    $planDetail = json_decode($planData[0]->plan_data);
                }
            }
            
            $view->with(['settings' => $settings, 'language'=>$language, 'defaultCurrency' => $defaultCurrency, 'playlist'=>$playlist, 
            'userPlan' => $planDetail, 'title' => $title,'audioLanguage'=> $audioLanguage, 'plan_expired_ago' => $plan_expired_ago,'countAdminPlaylist'=>$countAdminPlaylist,'homepage'=>$homepage,
            'is_youtube' => $is_youtube]);
        });
    }
}
