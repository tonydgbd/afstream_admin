<?php
namespace Modules\Users\Http\Controllers;
use App\User;
use Auth;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Plan\Entities\Plan;
use Illuminate\Routing\Controller;
use Modules\Location\Entities\Country;
use Modules\Location\Entities\AllCountry;
use Modules\Location\Entities\AllState;
use Modules\Location\Entities\AllCity;
use Stevebauman\Purify\Facades\Purify;
use App\UserPurchasedPlan;
use Hash;

class UsersController extends Controller
{
    public function index()
    { 
        return view('users::index');
    }

    public function create(){
        $data['plans'] = Plan::where('status',1)->pluck('plan_name','id')->all();
        $data['country'] = Country::with('allCountries')->get()->pluck('allCountries.nicename','allCountries.id');
        $data['state'] = collect();
        $data['city'] = collect();
        $data['title'] = 'Create User';
        $data['passwordReq'] = '1';
        return view('users::edituser',$data);
    }

    public function show($id){
        return view('users::show');
    }

    public function edit($id)
    {
        return view('users::edit');
    }

    public function profile(){
        $user = User::where('id',Auth::user()->id)->first();
        return view('users::profile', compact('user'));
    }
    public function editUser($id){
        $data['plans'] = Plan::where('status',1)->pluck('plan_name','id')->all();
        $data['country'] = Country::with('allCountries')->get()->pluck('allCountries.nicename','allCountries.id');
        $data['user'] = User::where('id', $id)->first();
        $data['state'] = AllState::where('country_id', $data['user']->country_id)->pluck('name','id')->all();
        $data['city'] = AllCity::where('state_id', $data['user']->state_id)->pluck('name','id')->all();
        $data['title'] = 'Edit User';
        return view('users::edituser', $data);
    }

    public function updateUser(Request $request, $id=''){
        if(is_numeric($id))
            $user = User::find($id);
        else
            $user = [];
        $data = $request->all();
        
        if(!empty($user)){
            $rules = [
                'name' => 'required',
                'email' =>'required|string|email|max:255|unique:users,id,'.$user->id,
                'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
                'gender' => 'required',
                'mobile' => 'required',
                'password' => 'nullable|string|min:6',
            ];
            $checkMail = select(['table'=>'users', 'column'=>'id', 'where'=>[['email',$request->email],['id','!=', $id]], 'single'=>1]);
            if(!empty($checkMail)){
                echo json_encode(array('status'=>0, 'msg'=>__('adminWords.email').' '.__('adminWords.already_exist') ));
                return;
            }
        }else{
            $email = User::where('email',$request->email)->first();
            if(!empty($email)){
                echo json_encode(array('status'=>0, 'msg'=>__('adminWords.email').' '.__('adminWords.already_exist')));
                return;
            }else{
                $rules = [
                    'name' => 'required',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile' => 'required',
                    'gender' => 'required',
                    'password' => 'required|string|min:6',
                    'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
                ];
            }
        }    
        $checkValidate = validation($data, $rules);
        $addPlan = false;
        if($checkValidate['status'] == 1){
            $newArr = array(
                'name' => isset($data['name']) ? $data['name'] : NULL,
                'email' => $data['email'],
                'gender' => $data['gender'],
                'mobile' => $data['mobile'],
                'status' => 1,
                'address' => Purify::clean($data['address']),
                'country_id' => isset($data['state_id']) ?  $data['country_id'] : null,
                'state_id' => $data['state_id'],
                'city_id' => $data['city_id'],
                'pincode' =>$data['pincode'],
                'dob' => $data['dob']
            );
            
            if(isset($data['check_user_plan']) && !empty($data['check_user_plan'])){
                
                if(!is_numeric($id) && $data['check_user_plan'] == 'false' && !empty($data['plan_id'])){
                    $addPlan = true;
                    $newArr['plan_id'] = $data['plan_id'];
                    $newArr['purchased_plan_date'] = date("Y-m-d", strtotime(date('Y-m-d')));                  
                    
                }elseif(is_numeric($id) && $data['check_user_plan'] == 'true' && !empty($data['plan_id']) && !empty($user)){
                    if($user->plan_id != $data['plan_id']){
                        $addPlan = true;
                        $newArr['plan_id'] = $data['plan_id'];
                        $newArr['purchased_plan_date'] = date("Y-m-d", strtotime(date('Y-m-d')));                  
                    }
                }elseif(is_numeric($id) && $data['check_user_plan'] == 'false' && !empty($data['plan_id']) && !empty($user)){
                    if($user->plan_id != $data['plan_id']){
                        $addPlan = true;
                        $newArr['plan_id'] = $data['plan_id'];
                        $newArr['purchased_plan_date'] = date("Y-m-d", strtotime(date('Y-m-d')));                  
                    }
                }
            }
            
            $id = get_increment_id('users');  
            if($image = $request->file('userProfileImage')){
                $name = 'user'.$id.'-'.time().'.webp';
                $newArr['image'] = str_replace(' ','',$name);
                upload_image($image, public_path().'/images/user/', $name, '60x60');
                if(!empty($user) && $user->image != '') {
                    delete_file_if_exist(public_path().'/images/user/'.$user->image);
                }
            }
            if(isset($data['password'])){
                $newArr['password'] = Hash::make($data['password']);
            }
            if(!isset($data['status'])){
                $newArr['status'] = 0;
            }
            
            if(!empty($user)){
                $usersDetail = $user->update($newArr);
                if($addPlan == true){
                    $checkPlan = Plan::where('id',$data['plan_id'])->first();
                    if(!empty($checkPlan)){
                        $planValid = $checkPlan->validity;
                        $expiry_date = date("Y-m-d", strtotime("+".$planValid.' day', strtotime(date('Y-m-d'))));
                        $addPlan = UserPurchasedPlan::create([
                            'user_id' => $user->id,
                            'plan_id' => $checkPlan->id,
                            'plan_data' => json_encode($checkPlan),
                            'payment_data' => json_encode([]),
                            'currency' => '$',
                            'expiry_date' => $expiry_date
                        ]); 
                    }
                }
                $msg = __('adminWords.user').' '.__('adminWords.updated_msg');
            }else{
                $usersDetail = User::create($newArr); 
                if($addPlan == true){
                    $checkPlan = Plan::where('id',$data['plan_id'])->first();
                    if(!empty($checkPlan)){
                        $planValid = $checkPlan->validity;
                        $expiry_date = date("Y-m-d", strtotime("+".$planValid.' day', strtotime(date('Y-m-d'))));
                        $addPlan = UserPurchasedPlan::create([
                            'user_id' => $usersDetail->id,
                            'plan_id' => $checkPlan->id,
                            'plan_data' => json_encode($checkPlan),
                            'payment_data' => json_encode([]),
                            'currency' => '$',
                            'expiry_date' => $expiry_date
                        ]); 
                    }
                }
                $msg = __('adminWords.user').' '.__('adminWords.added_msg');
            }
            
            if($usersDetail){
                $resp = array('status'=>1, 'msg'=>$msg);
            }else{
                $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg'));
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function usersData(Request $request){
        
        if(isset($request->from_date) && !empty($request->from_date) && isset($request->to_date) && !empty($request->to_date)){
            $users = User::where('role', 0)->orderBy('id','desc')->whereBetween('created_at', [$request->from_date, $request->to_date])->get();
        }else{
            $users = User::where('role', 0)->orderBy('id','desc')->where('created_at', '>', now()->subDays(30)->endOfDay())->get();
        }

        return DataTables::of($users)
        ->editColumn('checkbox',function($user){
            return '<div class="checkbox danger-check"><input id="checkboxAll'.$user->id.'" type="checkbox" class=" CheckBoxes" value="'.$user->id.'"><label for="checkboxAll'.$user->id.'" class="custom-control-label"></label></div>';
        })
        ->addColumn('image', function ($user) {
            if($user->image != '' && file_exists(public_path("images/user/".$user->image)))
                $src = asset('public/images/user/'.$user->image);
            else
                $src = asset('public/assets/images/users/user1-1651222429.webp');
            return '<span class="img-thumb"><img src="'.$src.'" alt="" class="img-fluid" width="60px" height="60px"></span>';
        })
        ->addColumn('plan', function ($user) {
            $planName = Plan::find($user->plan_id);
            if(!empty($planName)){
                return '<label class="mb-0 badge badge-success toltiped userPlanName" title="'.$planName->plan_name.'">'.$planName->plan_name.'</label>';
            }else{
                return '<label class="mb-0 badge badge-warning toltiped noHaveAnyPlan" data-original-title="" title="No Plan">No Plan</label>';
            }
        })
        ->editColumn('created_at', function($artists){
            return date('d-m-Y', strtotime($artists->created_at));
        })
        ->addColumn('status',function($user){
            return '<div class="checkbox success-check"><input id="checkboxc'.$user->id.'" type="checkbox" class="updateStatus" '.($user->status == 1 ? 'checked':'').' data-url="'.url('updateStatus/'.$user->id).'"><label for="checkboxc'.$user->id.'"></label></div>';
        })
        ->addColumn('action', function ($user) {
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
                            <a href="'.url('edit/'.$user->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('destroy/'.$user->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','image','plan','status','action'])->make(true);
    }

    function updateStatus(Request $request, $id){
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'users', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function destroy($id, $bulk=''){
        $resp = singleDelete([ 'table'=>'users','column'=>['image','name'], 'where'=>['id'=>$id], 'msg'=>__('adminWords.user').' '.__('adminWords.delete_success'), 'isImage'=>public_path().'/images/user/' ]);
        echo $resp;
    }

    function bulkDelete(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.user').' '.__('adminWords.delete_success') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'users','column'=>'id', 'msg'=>__('adminWords.user').' '.__('adminWords.delete_success'), 'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    function fetch_states(Request $request){
        $country = AllCountry::findOrFail($request->country_id);
        $states = AllState::where('country_id', $request->country_id)->pluck('name','id')->all();
        $resp = array('status'=>1, 'data'=>$states);
        return response()->json($resp);
    }

    function fetch_city(Request $request){
        $states = AllState::findOrFail($request->state_id);
        $city = AllCity::where('state_id', $request->state_id)->pluck('name','id')->all();
        $resp = array('status'=>1, 'data'=>$city);
        return response()->json($resp);
    }
}
