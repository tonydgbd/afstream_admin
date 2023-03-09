<?php

namespace Modules\Users\Http\Controllers;
use App\User;
use Auth;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Location\Entities\Country;
use Modules\Location\Entities\AllCountry;
use Modules\Location\Entities\AllState;
use Modules\Location\Entities\AllCity;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        
        return view('users::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(){
        $data['country'] = Country::with('allCountries')->get()->pluck('allCountries.nicename','allCountries.id');
        $data['state'] = collect();
        $data['city'] = collect();
        $data['title'] = 'Create User';
        $data['passwordReq'] = '1';
        return view('users::edituser',$data);
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id){
        return view('users::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('users::edit');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id, $bulk=''){
        // $resp = singleDelete([ 'table'=>'users','column'=>'id','where'=>['id'=>$id], 'msg'=>'User deleted successfully.', 'isImage'=>public_path().'/images/user/']);
        // echo $resp;
        $user = User::find($id);
        if(!empty($user)){
            if ($user->image != null) {             
                delete_file_if_exist(public_path().'/images/user/'.$users->image);
            }
            $delete = $user->delete();
            if($delete){
                if($bulk)
                    return 1;
                else
                    $resp = array('status'=>1, 'msg'=>'User deleted successfully.');
            }else{
                $resp = array('status'=>0, 'msg'=>'Something went wrong.');
            }
        }else{
            $resp = array('status'=>0, 'msg'=>'Something went wrong.');
        }
        echo json_encode($resp);
    }

    public function profile(){
        $user = User::where('id',Auth::user()->id)->first();
        return view('users::profile', compact('user'));
    }
    public function editUser($id){
        $data['country'] = Country::with('allCountries')->get()->pluck('allCountries.nicename','allCountries.id');
        $data['state'] = collect();
        $data['city'] = collect();
        $data['user'] = User::where('id', $id)->first();
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
                // 'name' => 'required',
                'email' =>'required|string|email|max:255|unique:users,id,'.$user->id,
                'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
                'gender' => 'required',
                'mobile' => 'required',
                'password' => 'nullable|string|min:8',
            ];
            $checkMail = select(['table'=>'users', 'column'=>'id', 'where'=>[['email',$request->email],['id','!=', $id]], 'single'=>1]);
            if(!empty($checkMail)){
                echo json_encode(array('status'=>0, 'msg'=>'Email already exists.'));
                return;
            }
        }else{
            $email = User::where('email',$request->email)->first();
            if(!empty($email)){
                echo json_encode(array('status'=>0, 'msg'=>'Email already exists.'));
                return;
            }else{
                $rules = [
                    // 'name' => 'required',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile' => 'required',
                    'gender' => 'required',
                    'password' => 'required|string|min:8',
                    'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
                ];
                $id = get_increment_id('users');  
            }
        }    
        $checkValidate = validation($data, $rules);
        if($checkValidate['status'] == 1){

            $user = User::create([

            ]);
            $newArr = array(
                'name' => isset($data['name']) ? $data['name'] : NULL,
                'email' => $data['email'],
                'gender' => $data['gender'],
                'mobile' => $data['mobile'],
                'status' => 1,
                'address' => $data['address'],
                'country_id' => isset($data['state_id']) ?  $data['country_id'] : null,
                'state_id' => $data['state_id'],
                'city_id' => $data['city_id'],
                'pincode' =>$data['pincode'],
                'dob' => $data['dob'],
                'role' => $data['role']
            );
            // return $newArr;
            if($image = $request->file('userProfileImage')){
                $name = 'user'.$id.'-'.time().'-'.$image->getClientOriginalName();
                $newArr['image'] = $name;
                upload_image($image, public_path().'/images/user/', $name);
                if(!empty($user) && $user->image != '') {
                    delete_file_if_exist(public_path().'/images/user/'.$user->image);
                }
            }
            if(isset($data['password'])){
                $newArr['password'] = bcrypt($data['password']);
            }
            if(!isset($data['status'])){
                $newArr['status'] = 0;
            }
            
            if(!empty($user)){
                $usersDetail = $user->update($newArr);
                $msg = 'User updated successfully.';
            }else{
                $usersDetail = User::create($newArr); 
                $msg = 'User added successfully.';
            }
            if($usersDetail){
                $resp = array('status'=>1, 'msg'=>$msg);
            }else{
                $resp = array('status'=>0, 'msg'=>'Soemthing went wrong.');
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function usersData(){
        $users = User::orderBy('id','desc')->get();
        return DataTables::of($users)
        ->editColumn('checkbox',function($user){
            return '<div class="inline custom-checkbox"><input id="checkboxAll'.$user->id.'" type="checkbox" class="custom-control-input CheckBoxes" value="'.$user->id.'"><label for="checkboxAll'.$user->id.'" class="custom-control-label"></label></div>';
        })
        ->addColumn('image', function ($user) {
            return $user->image != null ? '<img width="50px" height="70px" src="' . url("images/user/" . $user->image) . '"/>': '<img width="50px" height="70px" src="' . url("assets/images/users/profile.svg") . '"/>';
        })
        ->addColumn('role',function($user){
            return $user->role == 1 ? "Admin" : "User";
        })
        ->addColumn('status',function($user){
            return '<div class="custom-switch"><input id="switch3" class="custom-control-input updateStatus" '.($user->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('updateStatus/'.$user->id).'"><label class="custom-control-label" for="switch3"></label></div>';
        })
        ->addColumn('mobile', function ($user) {
            return $user->mobile!=null ? $user->mobile: '';
        })
        ->addColumn('action', function ($user) {
            return '<div class="button-list"><a href="'.url('edit/'.$user->id).'" class="btn btn-sm btn-success-rgba"><i class="feather icon-edit-2"></i></a><button type="button" data-url="'.url('destroy/'.$user->id).'" class="btn btn-sm btn-danger-rgba" id="deleteRecordById"><i class="feather icon-trash"></i></button></div>';
        })
        ->rawColumns(['checkbox','image','status','action'])->make(true);
    }

    function updateStatus(Request $request, $id){
        $resp = change_status(['table'=>'users', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
        echo $resp;
    }

    function bulkDelete(Request $request, $type){
        $checkValidate = validation($request->all(),['checked' =>'required'],'Atleast 1 '.$type.' must be selected.');
        if($checkValidate['status'] == 1){
            $cnt=1;
            foreach($request->checked as $checked){
                if($type == 'user'){
                    $getData = User::findOrFail($checked);
                    $msg = 'User deleted successfully.';
                }
                $getId = $getData->id;
                $checkDelete = $this->destroy($getId, 1);
                if(($cnt == sizeof($request->checked)) && $checkDelete == '1'){
                    $resp = array('status'=>1, 'msg'=>$msg);
                    echo json_encode($resp);
                }
                $cnt++;
            }
        }else{
            echo json_encode($checkValidate);
        }
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
