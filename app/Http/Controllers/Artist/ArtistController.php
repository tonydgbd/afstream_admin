<?php

namespace App\Http\Controllers\Artist;

use Modules\Location\Entities\AllCountry;
use Modules\Location\Entities\AllState;
use Modules\Setting\Entities\Settings; 
use Modules\Location\Entities\Country;
use Modules\Location\Entities\AllCity;
use Stevebauman\Purify\Facades\Purify;
use App\Http\Controllers\Controller;
use Modules\Artist\Entities\Artist;
use Modules\Coupon\Entities\Coupon;
use Modules\Audio\Entities\Audio;
use Modules\Album\Entities\Album;
use App\ArtistIntegrationSetting;
use Illuminate\Http\Request;
use App\SuccessPayment;
use App\User;
use Auth;
use Hash;
use Str;
use DB;

class ArtistController extends Controller
{
    public function index(){
        
        $allAudio = Audio::where('user_id',Auth::user()->id)->get();
        $data['recent_track'] = Audio::where('user_id',Auth::user()->id)->orderBy('id','desc')->limit(10)->get();
        $data['countAudio'] = count($allAudio);
        $data['totalSalesAmount'] = select(['column' => ['*'],'table' => 'admin_audio_payment'])->where('artist_id',Auth::user()->id)->sum('amount');
        $data['totalEarnAmount'] = select(['column' => ['*'], 'table' => 'artist_audio_payment'])->where('artist_id',Auth::user()->id)->sum('amount');
        
        $streamCountArr = [];
        for($i=1; $i<=date('m'); $i++){
            $streamCount = DB::table('audio')->where([['created_at', 'LIKE', '%'.date('Y').'-'.($i<10 ? '0'.$i : $i).'-%'], ['user_id','=',Auth::user()->id]])->sum('listening_count');            
            array_push($streamCountArr, $streamCount);           
        }
        $data['artistAudioStreamCount'] = $streamCountArr;
        return view('artist.dashboard', $data);
    }


    // 3rd Party Integration Start
    public function artistIntegration(){
        $artistIntegration = ArtistIntegrationSetting::where('user_id',Auth::user()->id)->first();
        return view('artist.integration')->with('artistIntegration',$artistIntegration); 
    }

    public function saveArtistIntegrationData(Request $request, $type){

        $input = [];
        $input = $request->except('_token');

        $isChecked = 0;        
        if($type == 'artist_aws_s3' && isset($input['aws_status'])){
            $rules = [ 'aws_region' => 'required', 'aws_access_key' => 'required','aws_secret_key'=> 'required','aws_bucket'=>'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }
        if($type == 'artist_youtube' && isset($input['youtube_status'])){             
            $rules = [ 'google_api_key' => 'required', 'youtube_channel_key' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }

        $artistIntegrationData = ArtistIntegrationSetting::where('user_id',Auth::user()->id)->first();
        $artistDetail = Artist::where('user_id',Auth::user()->id)->first();
        if(!empty($artistDetail)){
            $input['artist_id'] = $artistDetail->id;
        }

        if(empty($artistIntegrationData)){      

            $input['user_id'] = Auth::user()->id;            
            $addUpdateDetail = ArtistIntegrationSetting::create($input);            
        }else{
            $addUpdateDetail = $artistIntegrationData->update($input);
        }

        if($addUpdateDetail){
            $resp = array('status'=>1, 'msg'=> __('adminWords.detail').' '.__('adminWords.success_msg'));
        }else{
            $resp = array('status'=>0, 'msg'=> __('adminWords.detail').' '.__('adminWords.could_not_err'));
        }        

        echo json_encode($resp);
    }
    
    public function changeIntegrationStatus(Request $request){

        $artistIntegrationData = ArtistIntegrationSetting::where('user_id',Auth::user()->id)->first();

        if(empty($artistIntegrationData)){      
            $data = [];
            $data['user_id'] = Auth::user()->id;            
            $data[$request->type] = $request->status;            
            $addUpdateStatus = ArtistIntegrationSetting::create($data);            
        }else{
            $addUpdateStatus = $artistIntegrationData->update([ $request->type => $request->status ]);
        }
        
        if($addUpdateStatus){
            $resp = array('status'=>1, 'msg'=> __('adminWords.detail').' '.__('adminWords.success_msg'));
        }else{
            $resp = array('status'=>0, 'msg'=> __('adminWords.detail').' '.__('adminWords.could_not_err'));
        }
        echo json_encode($resp);
    }



    public function profile(){

        $data['country'] = Country::with('allCountries')->get()->pluck('allCountries.nicename','allCountries.id');
        $data['user'] = User::where('id', Auth::user()->id)->first();        
        $data['state'] = AllState::where('country_id', $data['user']->country_id)->pluck('name','id')->all();
        $data['city'] = AllCity::where('state_id', $data['user']->state_id)->pluck('name','id')->all();
        return view('artist.profile', $data);        
    }



    public function profileUpdate(Request $request){        
        
        $id = Auth()->user()->id;
        $user = User::find($id);
        $data = $request->all();        
        if(!empty($user)){
            $rules = [
                'name' => 'required',
                'email' =>'required|string|email|max:255|unique:users,id,'.$user->id,
                'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
                'gender' => 'required',
                'mobile' => 'required',
                'password' => 'nullable|string|min:8',
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
                    'password' => 'required|string|min:8',
                    'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
                ];
                $id = get_increment_id('users');  
            }
        }    
        $checkValidate = validation($data, $rules);
        if($checkValidate['status'] == 1){
            $newArr  = array(
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
            
            if(!empty($user)){                
                $usersDetail = $user->update($newArr);
                $msg = __('adminWords.user').' '.__('adminWords.updated_msg');
            }

            if($usersDetail){

                $artist = [];
                
                $checkArtist = Artist::where('user_id',$id)->first();
                
                $artist['artist_name'] = $data['name']; 
                $artist['artist_slug'] = Str::slug($data['name'],'-');      
                $artist['dob'] = $data['dob'];     

                if($image = $request->file('userProfileImage')){
                    $name = 'artist-'.time().'.webp';
                    $artist['image'] = str_replace(' ','',$name);
                    upload_image($image, public_path().'/images/artist/', $name, '500x500');
                    if(!empty($checkArtist) && $checkArtist->image != ''){
                        delete_file_if_exist(public_path().'/images/artist/'.$checkArtist->image);
                    }
                }

                $addArtist = $checkArtist->update($artist);
                $resp = array('status'=>1, 'msg'=>$msg);
            }else{
                $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg'));
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }
    
}
