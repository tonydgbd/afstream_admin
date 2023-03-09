<?php

namespace Modules\Setting\Http\Controllers;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Modules\Setting\Entities\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Modules\Album\Entities\Album;
use Modules\Setting\Entities\Currency;
use Modules\Setting\Entities\GoogleAd;
use Modules\General\Entities\Pages;
use Modules\Setting\Entities\Menu;
use Modules\Setting\Entities\Settings;
use Stevebauman\Purify\Facades\Purify;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Artisan;
use DataTables;
use App\User;
use DB;


class SettingController extends Controller
{
    public function seo(){
        return view('setting::seo');
    }

    public function seo_update(Request $request){
        $data= $request->except('_token');
        $rules = [
            'author_name' => 'required',
            'keywords' => 'required',
            'meta_desc' => 'required',
        ];
        $checkValidate = validation($data, $rules);
        if($checkValidate['status'] == 1){
            $success = 0; $msg ='';
            foreach($data as $key=>$val){
                $date = date('Y-m-d h:i:s');
                $insert = updateOrInsert(['table'=>'settings', 'data'=>[['name'=>$key],['value'=>Purify::clean($val)],['created_at'=>$date],['updated_at'=>$date] ]]);
                $success = 1;
            }
            if($success == 1){
                $resp = array('status'=>1, 'msg'=>__('adminWords.seo').' '.__('adminWords.settings').' '.__('adminWords.success_msg') );
            }else{
                $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg'));
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function seo_add(Request $request){
        $rules = ['name'=> 'required', 'value' => 'required'];
        $checkValidate = validation($request->all(), $rules);
        if($checkValidate['status'] == 1){
            $insert = insert(array('table'=>'settings', 'data'=>array('name'=>$request->name, 'value'=>$request->value)));
            if($insert)
                $resp = array('status'=>1, 'msg'=> __('adminWords.settings').' '.__('adminWords.success_msg') );
            else
                $resp = array('status'=>0, 'msg'=> __('adminWords.error_msg') );
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function site(){
        return view('setting::site');
    }

    public function site_update(Request $request){      
        $rules = [
            'mini_logo' => 'nullable|image|mimes:jpg,jpeg,png',
            'large_logo' => 'nullable|image|mimes:jpg,jpeg,png',
            'preloader' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png',
            'w_email' => 'nullable|email'
        ];
        $input = $request->except('_token');        

        $checkValidate = validation($input, $rules);
        if($checkValidate['status'] == 1){
            $input['is_artist_register'] = (!isset($input['is_artist_register']) ? 0 : 1);
            $input['is_preloader'] = (!isset($input['is_preloader']) ? 0 : 1);
            $input['is_gotop'] = (!isset($input['is_gotop']) ? 0 : 1);
            $input['right_click'] = (!isset($input['right_click']) ? 0 : 1);
            $input['inspect'] = (!isset($input['inspect']) ? 0 : 1);
            
            if ($file = $request->file('mini_logo')){
                $getData = select(['table'=>'settings', 'column'=>['name','value'], 'where'=>['name'=>'mini_logo'],'single'=>1]);
                $name = 'mini_logo.webp';
                if(!empty($getData->value)){                    
                    delete_file_if_exist(public_path().'/images/sites/'.$getData->value);    
                }
                upload_image($file, public_path().'/images/sites/', $name, '');
                $input['mini_logo'] = str_replace(' ','',$name);
            }

            if ($file = $request->file('large_logo')){
                $getData = select(['table'=>'settings', 'column'=>['name','value'], 'where'=>['name'=>'large_logo'],'single'=>1]);
                $name = 'large_logo.webp';
                if(!empty($getData->value)){
                    delete_file_if_exist(public_path().'/images/sites/'.$getData->value);    
                }
                upload_image($file, public_path().'/images/sites/', $name, '');
                $input['large_logo'] = str_replace(' ','',$name);
            }

            if ($file = $request->file('preloader')){

                $getData = select(['table'=>'settings', 'column'=>['name','value'], 'where'=>['name'=>'preloader'],'single'=>1]);
                $name = 'preloader.webp';
                if(!empty($getData)){ 
                    delete_file_if_exist(public_path().'/images/sites/'.$getData->value);    
                }
                $imgName = $file->getClientOriginalName();
                
                $file->move(public_path().'/images/sites/', $name);
                $input['preloader'] = str_replace(' ','',$name);
            }
            
            if ($file = $request->file('favicon')){
                $getData = select(['table'=>'settings', 'column'=>['name','value'], 'where'=>['name'=>'favicon'],'single'=>1]);
                $name = 'favicon.webp';
                if(!empty($getData)){
                    delete_file_if_exist(public_path().'/images/sites/'.$getData->value);    
                }
                upload_image($file, public_path().'/images/sites/', $name, '34x34');
                $input['favicon'] = str_replace(' ','',$name);
            }
            $success = 0;
            foreach($input as $key => $val){
                if($key == 'w_title'){
                    $file = DotenvEditor::setKey('APP_NAME', $val);
                    $file = DotenvEditor::save();
                }
                if(!isset($val)){
                    $val = '';
                }
                $insert = updateOrInsert(['table'=>'settings', 'data'=>[['name'=>$key], ['value'=>$val]]]);
                $success = 1;
            }
            
            if($success == 1){
                $resp= array('status'=>1, 'msg'=>__('adminWords.settings').' '.__('adminWords.success_msg'));
            }else{
                $resp= array('status'=>0, 'msg'=>__('adminWords.error_msg'));
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
        
    }

    public function notifications(){ 
        $userData = User::pluck('name','id')->all();
        $userData = User::where('role','!=','1')->pluck('name','id')->all();
        DB::table('notifications')->where(['admin_view' => '0'])->update(['admin_view' => '1']);
        $data['userData'] = [];
        if(sizeof($userData) > 0){
            $data['userData'] = ['all' => 'All'];
            foreach($userData as $key=>$value){
                $data['userData'][$key] = $value;
            }
        }
        return view('setting::notification', $data); 
    }

    public function notificationData(){
        $notification = select(['column' => ['notifications.id','notifications.notifiable_id','notifications.data','notifications.created_at', 'users.name'], 'table' => 'notifications', 'order'=>['notifications.created_at','desc'], 'join' => [['users','users.id','=','notifications.notifiable_id']] ]);
        $notify = [];
        if(sizeof($notification) > 0){
            foreach($notification as $noti){
                array_push($notify, ['user_id' => $noti->notifiable_id, 'id' => $noti->id, 'message' => json_decode($noti->data)->data ,'created_at' => $noti->created_at, 'user_name' => $noti->name]);
            }
        }
        return DataTables::of($notify)
            ->editColumn('checkbox',function($notify){
                return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$notify['id'].'" type="checkbox" class="CheckBoxes" value="'.$notify['id'].'" data-user="'.$notify['user_id'].'"><label for="checkboxAll'.$notify['id'].'"></label></div>';
            })
            ->editColumn('created_at', function($notify){
                return date('d-m-Y', strtotime($notify['created_at']));
            })
            ->addColumn('action', function ($notify){
                return '<a class="action-btn " href="javascript:void(0); ">
                    <svg class="default-size "  viewBox="0 0 341.333 341.333 ">
                        <g>
                            <g>
                                <g>
                                    <path d="M170.667,85.333c23.573,0,42.667-19.093,42.667-42.667C213.333,19.093,194.24,0,170.667,0S128,19.093,128,42.667 C128,66.24,147.093,85.333,170.667,85.333z "></path>
                                    <path d="M170.667,128C147.093,128,128,147.093,128,170.667s19.093,42.667,42.667,42.667s42.667-19.093,42.667-42.667 S194.24,128,170.667,128z "></path>
                                    <path d="M170.667,256C147.093,256,128,275.093,128,298.667c0,23.573,19.093,42.667,42.667,42.667s42.667-19.093,42.667-42.667 C213.333,275.093,194.24,256,170.667,256z "></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
                <div class="action-option ">
                    <ul>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('notification/destroy/'.$notify['id'].'/'.$notify['user_id']).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
            })
            ->rawColumns(['checkbox','action'])->make(true);
    }

    public function destroyNotification($noti_id, $user_id){
        $user = User::find($user_id);
        if(empty($user)){
            $delete = Notification::where('id', $noti_id)->get()->first()->delete();
        }else{
            $delete = $user->notifications()->where('id', $noti_id)->get()->first()->delete();
        }
        
        if($delete){
            $resp = ['status' => 1, 'msg' => __('adminWords.notification').' '.__('adminWords.delete_success')];
        }else{
            $resp = ['status' => 0, 'msg' => __('adminWords.error_msg')];
        }
        echo json_encode($resp);
    }

    public function bulkDeleteNotification(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').' '.__('adminWords.notification').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $notification_id = $request->checked;
            for($i=0; $i<sizeof($notification_id); $i++){
                $delete = Notification::where('id', $notification_id[$i])->get()->first()->delete();
            }            
            $resp = ['status' => 1, 'msg' => __('adminWords.notification').' '.__('adminWords.delete_success')];
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }


    public function showAddNotification(){
        $data['albumData'] = Album::pluck('album_name','id')->all();
        $data['userData'] = User::pluck('name','id')->all();
        return view('setting::index', $data);
    }

    public function addNotification(Request $request){
        $rules = [ 'user_id' => 'required', 'message' => 'required' ];
        $checkValidate = validation($request->except('_token'), $rules);
        if($checkValidate['status'] == 1){
            $data = $request->except('_token');
            $index = array_search('all',$request->user_id);
            
            if($index == 0){
                $users = User::all();
            }else if(sizeof($request->user_id) > 1){
                $userid = $request->user_id;
                $users = [];
                for($i=0; $i<sizeof($userid); $i++){
                    array_push($users, User::find($userid[$i]));
                }
            }else{
                $users = User::find($request->user_id);
            }
            
            \Notification::send($users, new UserNotification($request->message));
            $data['user_id'] = json_encode($request->user_id);
            $resp = array('status'=>1, 'msg'=>__('adminWords.notification').' '.__('adminWords.success_msg'));
        }else{
           $resp = $checkValidate;
        }
       echo json_encode($resp);
    }

    public function tax(){
        return view('setting::tax');
    }

    public function saveTax(Request $request){
        $checkValidate = validation($request->except('_token'), ['tax' => 'required']);
        if($checkValidate['status'] == 1){
            foreach($request->except('_token') as $key=>$val){
                $insert = Settings::updateOrCreate(['name'=>$key],['value'=>$val]);
                $success = 1;
            }
            $resp = ['status' => 1, 'msg' => __('adminWords.data').' '.__('adminWords.success_msg')];            
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);        
    }

    public function saveCommission(Request $request){
        
        $checkValidate = validation($request->except('_token'), ['is_commission' => 'required','commission_type'=>'required','commission_val'=>'required']);
        if($checkValidate['status'] == 1){
            foreach($request->except('_token') as $key=>$val){
                $insert = Settings::updateOrCreate(['name'=>$key],['value'=>$val]);
                $success = 1;
            }
            $resp = ['status' => 1, 'msg' => __('adminWords.data').' '.__('adminWords.success_msg')];            
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);        
    }
    

    public function google_ad(){
        $data['google_ad'] = GoogleAd::all();
        return view('setting::google_ad', $data);
    }

    public function saveGoogleAd(Request $request, $id){

        $checkValidate = validation($request->except('_token'), ['google_ad_script' => 'required']);
        if($checkValidate['status'] == 1){
            $checkScript = (is_numeric($id) ? GoogleAd::find($id) : []);             
            if(!empty($checkScript)){
                $saveAd = GoogleAd::where('id', $checkScript->id)->update(['google_ad_script' => $request->google_ad_script, 'status' => 1]);
            }else{
                $saveAd = GoogleAd::create(['google_ad_script' => $request->google_ad_script, 'status' => 1]);
            }
            $resp = ['status' => 1, 'msg' => __('adminWords.google_adsense_script').' '.__('adminWords.success_msg') ];
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function changeAdsenseStatus(Request $request, $id){
        
        $checkValidate = validation($request->except('_token'),['status'=>'required']);
        if($checkValidate['status'] == 1){
            $checkScript = (is_numeric($id) ? GoogleAd::find($id) : []);     
            if(!empty($checkScript)){
                $saveAd = $checkScript->update(['status' => 0]);
            }
            $resp = ['status'=>1,'msg'=>__('adminWords.data').' '.__('adminWords.success_msg')];
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function common_setting(){
        return view('setting::commonSetting');
    }

    public function saveCommonSetting(Request $request, $type){
        $data= $request->except('_token');
        if($type == 'footer'){
            $rules = [ 'section_1_heading' => 'required', 'section_1_description' => 'required', 'section_2_heading' => 'required', 'section_2_description' => 'required', 'section_3_heading' => 'required', 'section_3_description' => 'required', 'section_4_heading' => 'required', 'w_address' => 'required', 'w_email' => 'required', 'w_phone' => 'required', 'facebook_url' => 'required', 'linkedin_url' => 'required', 'twitter_url' => 'required', 'google_plus_url' => 'required', 'copyrightText' => 'required' ];
        }else{
            $rules = [ 'header_title' => 'required' , 'header_description' => 'required' ];
        }
        $checkValidate = validation($data, $rules);
        if($checkValidate['status'] == 1){
            $success = 0; $msg ='';
            foreach($data as $key=>$val){
                $date = date('Y-m-d h:i:s');
                $insert = updateOrInsert(['table'=>'settings', 'data'=>[['name'=>$key],['value'=>Purify::clean($val)],['created_at'=>$date],['updated_at'=>$date] ]]);
                if($insert)
                    $success = 1;
            }
            if($success == 1){
                $resp = array('status'=>1, 'msg'=> __('adminWords.settings').' '.__('adminWords.success_msg') );
            }else{
                $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg'));
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function menuSetting(){
        return view('setting::menu');
    }

    public function create_menu(){
        $data['pages'] = Pages::where('is_active', 1)->pluck('title', 'id')->all();
        return view('setting::addMenu', $data);
    }

    public function menuData(){
        $menus = select(['table'=>'menus','column'=>['menus.menu_heading', 'menus.id', 'menus.status', 'pages.title'], 'order'=>['id','desc'], 'join'=>[['pages', 'menus.page_id', '=', 'pages.id']] ]);
        $menu = [];
        if(sizeof($menus) > 0){
            foreach($menus as $menuData){
                array_push($menu, ['menu_heading' => $menuData->menu_heading, 'page_name' => $menuData->title, 'id' => $menuData->id, 'status' => $menuData->status]);
            }
        }
        return DataTables::of($menu)
        ->editColumn('checkbox', function($menu){
            return '<div class="checkbox danger-check"><input id="checkboxAll'.$menu['id'].'" type="checkbox" class="CheckBoxes" value="'.$menu['id'].'"><label for="checkboxAll'.$menu['id'].'"></label></div>';
        })
        ->editColumn('status', function($menu){
            return '<div class="checkbox success-check"><input id="checkboxc'.$menu['id'].'" class="updateStatus" '.($menu['status'] == 1 ? 'checked':'').' type="checkbox" data-url="'.url('update_menu_status/'.$menu['id']).'"><label for="checkboxc'.$menu['id'].'"></label></div>';
        })
        ->editColumn('action', function($menu){
            return '<a class="action-btn " href="javascript:void(0); ">
                    <svg class="default-size "  viewBox="0 0 341.333 341.333 ">
                        <g>
                            <g>
                                <g>
                                    <path d="M170.667,85.333c23.573,0,42.667-19.093,42.667-42.667C213.333,19.093,194.24,0,170.667,0S128,19.093,128,42.667 C128,66.24,147.093,85.333,170.667,85.333z "></path>
                                    <path d="M170.667,128C147.093,128,128,147.093,128,170.667s19.093,42.667,42.667,42.667s42.667-19.093,42.667-42.667 S194.24,128,170.667,128z "></path>
                                    <path d="M170.667,256C147.093,256,128,275.093,128,298.667c0,23.573,19.093,42.667,42.667,42.667s42.667-19.093,42.667-42.667 C213.333,275.093,194.24,256,170.667,256z "></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
                <div class="action-option ">
                    <ul>
                        <li>
                            <a class="blogCategoryPopupToggle" href="'.url('edit_menu/'.$menu['id']).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('destroyMenu/'.$menu['id']).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>'; 
        })
        ->rawColumns(['checkbox','status','action'])->make(true);
    }

    public function saveMenu(Request $request, $id){
        $checkValidate = validation($request->except('_token'), ['menu_heading' => 'required', 'page_id' => 'required']);
        if($checkValidate['status'] == 1){
            $data = $request->except('_token');
            $data['status'] = (isset($data['status']) ? 1 : 0);
            if(is_numeric($id)){
                $checkMenu = Menu::where([['id','!=',$id],['menu_heading','=',$request->menu_heading]])->get();
            }else{
                $checkMenu = Menu::where('menu_heading',$request->menu_heading)->get();
            }    
            if(count($checkMenu) > 0){
                $resp = ['status'=>0, 'msg'=>__('adminWords.menu').' '.__('adminWords.already_exist')];
            }else{
                $menu = (is_numeric($id) ? Menu::find($id) : []);
                if(!empty($menu)){
                    $update = $menu->update($data);
                    $msg = __('adminWords.menu').' '.__('adminWords.updated_msg');
                }else{
                    $update = Menu::create($data);
                    $msg = __('adminWords.menu').' '.__('adminWords.success_msg');
                }
                $resp = ['status'=>1, 'msg'=>$msg];
            }
            
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function edit_menu($id){
        $data['pages'] = Pages::where('is_active', 1)->pluck('title', 'id')->all();
        $data['menuData'] = Menu::find($id);
        return view('setting::addMenu', $data);
    }

    function updateMenuStatus(Request $request, $id){
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'menus', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function destroyMenu($id){
        $resp = singleDelete([ 'table'=>'menus','column'=>['menu_heading'], 'where'=>['id'=>$id], 'msg'=> __('adminWords.menu').' '.__('adminWords.delete_success') ]);
        echo $resp;        
    }

    function bulkDeleteMenu(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').__('adminWords.menu').__('adminWords.must_selected'));
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'menus','column'=>'id',  'msg'=>__('adminWords.menu').' '.__('adminWords.delete_success'), 'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function adminsetting(){
        return view('setting::admin');
    }

    public function dashboardSetting(Request $request){

        $rules = [];
        $data = $request->except('_token');

        if(isset($request->is_dashboard) && !empty($request->is_dashboard)){           
            $data['is_dashboard'] = $request->is_dashboard;
        }else{
            $data['is_dashboard'] = '';
        }      
        if(isset($request->latest_subs)){
            $rules['max_latest_subs'] = 'numeric|min:1';
            $data['latest_subs'] = 1;
        }else{
            $data['latest_subs'] = 0;
        }
        if(isset($request->rcnt_add_track)){
            $rules['max_rcnt_add_track'] = 'numeric|min:1';
            $data['rcnt_add_track'] = 1;
        }else{
            $data['rcnt_add_track'] = 0;
        }
        if(isset($request->rcnt_add_album)){
            $rules['max_rcnt_add_album'] = 'numeric|min:1';
            $data['rcnt_add_album'] = 1;
        }else{
            $data['rcnt_add_album'] = 0;
        }
        if(isset($request->rcnt_add_user)){
            $rules['max_rcnt_add_user'] = 'numeric|min:1';
            $data['rcnt_add_user'] = 1;
        }else{
            $data['rcnt_add_user'] = 0;
        }
        $checkValidate = validation($data, $rules);
        if($checkValidate['status'] == 1){

            $success = 0; $msg ='';

            foreach($data as $key=>$val){
                $date = date('Y-m-d h:i:s');
                $insert = updateOrInsert(['table'=>'settings', 'data'=>[['name'=>$key],['value'=>Purify::clean($val)],['created_at'=>$date],['updated_at'=>$date] ]]);
                if($insert)
                    $success = 1;
            }
            if($success == 1){
                $resp = array('status'=>1, 'msg'=> __('adminWords.settings').' '.__('adminWords.success_msg') );
            }else{
                $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg'));
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }


    public function currency_setting(){
        return view('setting::currency');
    }

    public function currencyData(){
        $curr = Currency::all();
        $setting = Settings::where('name', 'default_currency_id')->first();
        $currency = [];
        if(sizeof($curr) > 0){
            foreach($curr as $cur){
                $arr = ['id' => $cur->id, 'rate' => $cur->exchange_rate, 'symbol' => $cur->symbol, 'code' => $cur->code];
                $arr['curId'] = '';
                if(!empty($setting)){
                    $arr['curId'] = $setting->value;
                } 
                array_push($currency, $arr);
            }
        }
        return DataTables::of($currency)
        ->addIndexColumn() 
        ->editColumn('code', function($currency){
            return $currency['code'].' '.($currency['curId'] != '' && $currency['curId'] == $currency['id'] ? '<span class="badge badge-success">Default</span>' : '');
        })
        ->editColumn('rate', function($currency){
            return round($currency['rate'], 2);
        })
        ->editcolumn('default_currency', function($currency){
            return '<div class="form-group custom-radio-ml">
                        <div class="radio radio-primary">
                            <input type="radio" class="currency_radio makeCurrencyDefault" name="default_currency" value="'.$currency['id'].'" data-name="'.$currency['code'].'" '.($currency['curId'] != '' && $currency['curId'] == $currency['id'] ? 'checked' : ($currency['code'] == 'USD' ? 'checked' : '')).' id="'.$currency['id'].'">
                            <label for="'.$currency['id'].'"></label>
                        </div>
                    </div>'; 
        })
        
        ->addColumn('action', function ($currency) {       
                return ($currency['curId'] != '' && $currency['curId'] == $currency['id']) || $currency['code'] == 'USD' ? '' : '<div class="button-list"><a href="javascript:void(0)" data-url="'.url('currency/destroy/'.$currency['id']).'" data-msg="'.__('adminWords.delete_curr').$currency['code'].'?" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i></button></div>';
            })
            ->rawColumns(['default_currency', 'action', 'code'])->make(true);
    }

    public function currency_detail($id){
        $getCurrency = Currency::find($id);
        if(!empty($getCurrency)){
            $resp = ['status'=>1, 'data'=>$getCurrency];
        }else
            $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        echo json_encode($resp);
    }

    public function saveCurrency(Request $request){
        $validate = validation($request->except('_token'), ['currency_code' => 'required|alpha|max:3']);
        if($validate['status'] == 1){
            try{
                if(!currency()->hasCurrency('USD')){
                    Artisan::call('currency:manage add USD,' . $request->currency_code);
                }else{
                    Artisan::call('currency:manage add ' . $request->currency_code);
                }

                $output = Artisan::output();
                
                if(!strstr($output,'success')){
                    echo json_encode(['status' =>0, 'msg' => $output]); exit;
                }
                Artisan::call('currency:update -o');
                echo json_encode(['status' => 1, 'msg' => __('adminWords.currency').' '.$request->currency_code.' '.__('adminWords.added').'.']); exit;
            }catch(\Exception $e){
                echo json_encode(['status' =>0 ,'msg' => $e->getMessage()]); exit;
            }
        }else{
            echo json_encode($validate); exit;
        }
    }

    public function updateCurrency(Request $request, $code){
        $validate = validation($request->except('_token'), ['currency_code' => 'required', 'additional_fee' => 'required']);
        if($validate['status'] == 1){
            $currency = Currency::where('code','=',$code)->first();
            if(!empty($currency)){
                $updateCur = $currency->update(['exchange_rate' => $request->additional_fee]);
                if($updateCur){
                    echo json_encode(['status' =>1, 'msg' => __('adminWords.currency').' '.$currency->code.' '.__('adminWords.updated').'.']); exit;
                }else{
                    echo json_encode(['status' =>0, 'msg' => __('adminWords.error_msg')]); exit;
                }
            }else{
                echo json_encode(['status' =>0, 'msg' => __('adminWords.currency_not_found')]); exit;
            }
        }else{
            echo json_encode($validate);
        }
    }

    public function auto_update_rate(Request $request){
        if($request->ajax()){
            try{
                Artisan::call('currency:update -o');
                echo json_encode(['status'=>1, 'msg' => __('adminWords.rate').' '.__('adminWords.updated_msg'), 'swal' => 1]); exit;
            }catch(\Exception $e){
                echo json_encode(['status'=>0, 'msg' => $e->getMessage(), 'swal' => 1]); exit;
            }   
        }
    }

    public function make_default_curr(Request $request){
        $validate = validation($request->except('_token'), ['id' => 'required']);
        if($validate['status'] == 1){
            Settings::updateOrCreate(['name'=>'default_currency_id'],['value'=>$request->id]);
            DB::table('currencies')->where('active', '=', 1)->update(array('active' => 0));
            $currency = Currency::where('id',$request->id)->first();
            if(!empty($currency)){
                $updateCur = $currency->update(['active' => '1']);
            }
            $resp = ['status' => 1, 'msg' => __('adminWords.data').' '.__('adminWords.success_msg')];
        }else{
            $resp = $validate;
        }
        echo json_encode($resp);
    }

    public function destroyCurrency($id){
        $checkCurr = Currency::find($id);
        if(!empty($checkCurr)){
            currency()->delete($checkCurr->code);
            echo json_encode(['status' => 1, 'msg' => __('adminWords.currency').' '.__('adminWords.delete_success')]);
        }else{
            echo json_encode(['status' => 0, 'msg' => __('adminWords.currency_not_found')]);
        }
    }
    
}
