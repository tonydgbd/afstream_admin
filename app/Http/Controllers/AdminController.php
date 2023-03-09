<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Modules\Audio\Entities\Audio;
use Modules\Artist\Entities\Artist;
use Modules\Album\Entities\Album;
use Modules\Setting\Entities\Settings;
use Modules\General\Entities\InvoiceSetting;
use Modules\Language\Entities\Language;
use Modules\Coupon\Entities\Coupon;
use Modules\Plan\Entities\Plan;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Mail;
use App\UserPurchasedPlan;
use App\Mail\WelcomeMail;
use App\paymentGateway;
use App\SuccessPayment;
use App\AdminAudioPayment;
use App\ArtistAudioPayment;
use Validator;
use Socialite;
use Redirect;
use Session;
use Auth;
use Hash;
use Str;
use DB;

class AdminController extends Controller
{
    public function index(){

        $data['countUser'] = User::where(['status' => 1, 'role' => 0])->count();
        $data['countAudio'] = Audio::where('status', 1)->count();
        $data['countArtist'] = Artist::where('status', 1)->count();
        $data['artistRequest'] = User::where(['status'=> 1,'role'=>2,'artist_verify_status'=>'P'])->count();
        $data['artistReject'] = User::where(['status'=> 1,'role'=>2,'artist_verify_status'=>'R'])->count();
        $data['approvedArtist'] = User::where(['status'=> 1,'role'=>2,'artist_verify_status'=>'A'])->count();
        $data['countAlbum'] = Album::where('status', 1)->count();
        $data['countCoupon'] = Coupon::where('status', 1)->count();
        $data['countActivePlan'] = Plan::where('status', 1)->count();
        
        $data['totalAudioSalesAmount'] = select(['column' => ['*'],'table' => 'admin_audio_payment'])->sum('amount');
        
        $data['countArtistRequest'] = select(['column' => ['artists.id','artists.image','artists.audio_language_id','artists.artist_name','artists.dob', 'artist_genres.genre_name', 'users.artist_verify_status','artists.status','artists.user_id', 'artists.created_at'], 'table' => 'artists', 'join' => [['artist_genres','artists.artist_genre_id','=','artist_genres.id'],['users','artists.user_id','=','users.id']] ])->where('artist_verify_status','P')->count();
        
        $totalAudioSalesAmount = AdminAudioPayment::where('status','1')->get()->toArray();
        $adminEarning = 0;
        if(isset($totalAudioSalesAmount) && !empty($totalAudioSalesAmount)){
            foreach ($totalAudioSalesAmount as $adminEarnings) {
                if($adminEarnings['commission_type'] == 'percent'){
                    $adminEarning += intval(($adminEarnings['commission'])*$adminEarnings['amount']/100);
                }elseif($adminEarnings['commission_type'] == 'flat'){
                    $adminEarning += $adminEarnings['amount']-$adminEarnings['commission'];
                }                
            }
        }     
        $data['totalAdminEarnAmount'] = $adminEarning;
        
        $getSetting = Settings::pluck('value','name')->all(); 
        if(!empty($getSetting) && isset($getSetting['rcnt_add_track']) && $getSetting['rcnt_add_track'] == 1){
            $data['recent_track'] = Audio::where('status', 1)->orderBy('created_at','desc')->limit($getSetting['max_rcnt_add_track'])->get();
        }else{
            $data['recent_track'] = Audio::where('status', 1)->orderBy('created_at','desc')->limit(10)->get();
        }
        if(!empty($getSetting) && isset($getSetting['rcnt_add_user'])){
            $data['recent_users'] = User::where(['status' => 1, 'role' => 0])->orderBy('created_at','desc')->limit($getSetting['max_rcnt_add_user'])->get();
        }else{
            $data['recent_users'] = User::where(['status' => 1, 'role' => 0])->orderBy('created_at','desc')->limit(10)->get();
        }
        if(!empty($getSetting) && isset($getSetting['rcnt_add_album'])){
            $data['recent_album'] = Album::where('status', 1)->orderBy('created_at','desc')->limit($getSetting['max_rcnt_add_album'])->get();
        }else{
            $data['recent_album'] = Album::where('status', 1)->orderBy('created_at','desc')->limit(10)->get();
        }
        if(!empty($getSetting) && isset($getSetting['latest_subs'])){
            $data['recent_subscription'] = SuccessPayment::orderBy('created_at','desc')->limit($getSetting['max_latest_subs'])->get();
        }else{
            $data['recent_subscription'] = SuccessPayment::orderBy('created_at','desc')->limit(10)->get();
        }
        $userArr = $subsArr = [];
        for($i=1; $i<=date('m'); $i++){
            $userDetail = DB::table('users')->where([['created_at', 'LIKE', '%'.date('Y').'-'.($i<10 ? '0'.$i : $i).'-%'], ['role','=',0]])->count('id');
            $subscriptionDetail = DB::table('success_payments')->where('created_at', 'LIKE', '%'.date('Y').'-'.($i<10 ? '0'.$i : $i).'-%')->count('id');
            array_push($userArr, $userDetail);
            array_push($subsArr, $subscriptionDetail);
        }
        $data['userMonthCount'] = $userArr;
        $data['subsMonthCount'] = $subsArr;
        
        return view('admin.dashboard', $data);
    }

    public function getAdminDashColor(Request $request){ 
        if(isset(Auth::user()->id)){
            $response = [];            
            session()->put('dashColor', $request->dash_color);
            $response['status'] = true; 
            return response()->json($response);            
        }
    }

    public function user_invoice($purchase_id, $order_id, $type){
        $data['order_id'] = $order_id;
        $data['invoice_setting'] = InvoiceSetting::all();
        if($type == '0'){ /////// type == 0 when payment is cancelled
            $data['invoiceData'] = paymentGateway::find($purchase_id);
        }else{ ///////// success payment
            $data['invoiceData'] = SuccessPayment::find($purchase_id);
        }
        $data['type'] = $type;
        if(!empty($data['invoiceData']))
            return view('admin.invoice', $data);
        else
            return redirect('admin');
    }

    public function purchase_audio_invoice($order_id){
        $purchaseDetail = AdminAudioPayment::where('order_id',$order_id)->first();
        $data['order_id'] = $order_id;
        $data['invoice_setting'] = InvoiceSetting::all();        
        $data['invoiceData'] = $purchaseDetail;
        $audio_name = Audio::select('audio_title')->find($purchaseDetail->audio_id); // $purchaseDetail;        
        $data['audio_name'] = $audio_name->audio_title;
        $data['type'] = $purchaseDetail->status;
        if(!empty($data['invoiceData']))
            if(Auth::user()->role == 1){
                return view('admin.admin_audio_invoice', $data);
            }else{
                return view('admin.audio_invoice', $data);
            }
        else
            return redirect()->back();
    }
    
    public function artist_payment_invoice($order_id){ 
        
        $purchaseDetail = ArtistAudioPayment::where('order_id',$order_id)->first();
        $data['order_id'] = $order_id;
        $data['invoice_setting'] = InvoiceSetting::all();        
        $data['invoiceData'] = $purchaseDetail;
        $data['type'] = $purchaseDetail->status;
        if(!empty($data['invoiceData']))
            if(Auth::user()->role == 1){
                return view('admin.admin_artist_payment_invoice', $data);
            }else{
                return view('admin.artist_payment_invoice', $data);
            }
        else
            return redirect()->back();
    }
    
    public function register(Request $request){
        
        
        $response = [];        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3', 
            'password' => 'required|min:6', 
            'cnf_password' => 'required|same:password',
            'email' => 'required|email|max:255|unique:users', 
        ],[
            'cnf_password.required'=>'Some required fields are missing.',
            'cnf_password.same'=>'The Confirm password and password must match.'
        ]);
        
        if(isset($request->accept_term_and_policy) && !empty($request->accept_term_and_policy) && $request->accept_term_and_policy == '1'){
            $validator = Validator::make($request->all(), [
                'accept_term_and_policy' => 'required', 
            ]);
        }
        
        if ($validator->fails()) {
            $response['status'] = 2;
            $response['msg'] = $validator->errors()->first();
            echo json_encode($response); die;
        }

            $pass = $request->password;
            $dataArr = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'mobile' => $request->mobile,
            ];

            if(isset($request->is_artist) && !empty($request->is_artist) && $request->is_artist == 'on'){
                $dataArr['role'] = '2';  // Check user Artist or not                
            }
            
            if(isset($request->accept_term_and_policy) && !empty($request->accept_term_and_policy) && $request->accept_term_and_policy == '1'){
                $dataArr['accept_term_and_policy'] = '1';  // Check term and privacy policy accepetance      
            }  

            $createUser = User::create($dataArr);

            if(!empty($createUser) && isset($request->is_artist) && !empty($request->is_artist) && $request->is_artist == 'on'){
                $artistsData = [];

                $slug = Str::slug($request->name,'-');
                $artistsData['user_id'] = $createUser->id;
                $artistsData['artist_name'] = $request->name;
                $artistsData['artist_slug'] = $slug;
                $artistsData['audio_language_id'] = isset($request->audio_language_id) ? json_encode($request->audio_language_id) : ''; 
                $artistsData['artist_genre_id'] = isset($request->artist_genre_id) ? $request->artist_genre_id : '';

                $createArtist = Artist::create($artistsData);
            }
            
            $checkPlan = Plan::where(['plan_amount'=>0,'status'=>'1'])->orderBy('id','desc')->get();
                        
            if(sizeof($checkPlan) > 0){
                $planValid = $checkPlan[0]->validity;
                $expiry_date = date("Y-m-d", strtotime("+".$planValid.' day', strtotime(date('Y-m-d'))));
                $addPlan = UserPurchasedPlan::create([
                    'user_id' => $createUser->id,
                    'plan_id' => $checkPlan[0]->id,
                    'plan_data' => json_encode($checkPlan[0]),
                    'payment_data' => json_encode([]),
                    'currency' => '$',
                    'expiry_date' => $expiry_date
                ]); 
                if($addPlan){
                    $artistCountUpdate = User::where('id', $createUser->id)->update(['plan_id' => $checkPlan[0]->id,'purchased_plan_date'=>date("Y-m-d", strtotime(date('Y-m-d')))]);
                }
            }


            if($createUser){
                
                $checkSetting = Settings::where('name', 'LIKE', '%wel_mail%')->first();
                $checkSmtpSetting = Settings::where('name', 'is_smtp')->first();
                if(!empty($checkSetting) && $checkSetting->value == 1 && !empty($checkSmtpSetting) && $checkSmtpSetting->value == 1){
                    $dataArr['url'] = url('/home');
                    $dataArr['password'] = $pass;
                    if(!empty(env('MAIL_PASSWORD'))){
                        try {
                            Mail::to($request->email)->send(new WelcomeMail($dataArr));
                        }catch (\Exception $e) {
                            
                        }
                    }                
                }
                $resp = ['status' => 1, 'msg' => __('frontWords.register_success') ];
                
            }else{
                $resp = ['status' => 0, 'msg' => __('frontWords.something_wrong')];
            }
       
        echo json_encode($resp); die;
    }

    public function authenticated(Request $request){
        
        $home = getSelectedHomepage();
        if (Auth::attempt([ 'email' => $request->get('email') , 'password' => $request->get('password')], $request->remember)){
 
            if(Auth::user()->status == 0){
                Auth::logout();
                toastr()->error( __('frontWords.deactivate_acc'), '', ['timeOut' => 2000, 'progressBar' =>false] );
                return redirect()->route($home);
            }else{
                
                if(Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 2){
                    toastr()->success( __('frontWords.login_success'), '', ['timeOut' => 2000, 'progressBar' =>false] );
                    return redirect()->route($home);
                }
            }
        }else{
            Auth::logout();
            toastr()->error( __('frontWords.credential_err'), '', ['timeOut' => 2000, 'progressBar' =>false] );
            return redirect()->route($home);
        }
    }

    public function socialLogin($service){
        return Socialite::driver($service)->redirect();
    }

    public function socialLoginRedirect(Request $request, $service){
        if(isset($request->error)){
            toastr()->error($request->error_description, '', ['timeOut' => 2000, 'progressBar' =>false]);
            return redirect()->route('home');
        }else{
            $user = Socialite::driver($service)->user();
            if($user->email == ''){
                $user->email = $user->id.'@'.$service.'.com';
            }
            $user = User::firstOrCreate([
                'email' => $user->email
            ],[
                'name' => $user->name,
                'password' => Hash::make(Str::random())
            ]);

            Auth::login($user, true);
            return redirect('home');
        }
    }

    public function setLanguage($locale){ 
        Session::put('locale', $locale);
        \App::setLocale(Session::get('locale'));
        $setNonedefault = Language::where('is_default', 1)->update(['is_default' => 0]);
        $setDefault = Language::where('Language_code', $locale)->update(['is_default' => 1]);
        $value = Session()->get('locale');
        return redirect()->back();        
    }
    
    public function logout(Request $request){
        Auth::logout();
        $home = getSelectedHomepage();
        toastr()->success(__('frontWords.logout_success'), '', ['timeOut' => 2000, 'progressBar' =>false]);
        return redirect()->route($home);
    }
}
