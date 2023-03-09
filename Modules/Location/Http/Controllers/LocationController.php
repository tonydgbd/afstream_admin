<?php
namespace Modules\Location\Http\Controllers;
use Modules\Location\Entities\AllCountry;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Location\Entities\Country;
use Modules\Location\Entities\AllState;
use Modules\Location\Entities\AllCity;
use Datatables;

class LocationController extends Controller
{
    public function index(){
        return view('location::country');
    }

    public function locationData(){
        $countries = Country::select('country','id')->orderBy('id','desc')->get();
        $newArr = [];
        if(!empty($countries)){
            foreach($countries as $country){
                $iso3 = $country->country;
                $country_name = AllCountry::firstWhere('iso3',$iso3);
                if(!empty($country_name)){
                    array_push($newArr, array('id'=> $country->id, 'country'=>$country_name['nicename'], 'iso'=>$country_name['iso'],'iso3'=>$iso3));
                }
            }
            return DataTables::of($newArr)
                ->editColumn('checkbox',function($country){
                    return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$country['id'].'" type="checkbox" class="CheckBoxes" value="'.$country['id'].'"><label for="checkboxAll'.$country['id'].'"></label></div>';
                })
                ->addColumn('action', function ($country) {
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
                            <a class="locationPopupToggle" data-url="'.url('getCountryName/'.$country['id']).'" data-save="'.url('saveCountry/'.$country['id']).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>                        
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('destroyCountry/'.$country['id']).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
                })
                ->rawColumns(['checkbox','image','action'])->make(true);
        }
    }

    public function create(){
        return view('location::create');
    }

    public function saveCountry(Request $request, $id){
       $checkValidate = validation($request->all(), ['country' => 'required|string|min:3|max:3']);
        if($checkValidate['status'] == 1){
            $country = strtoupper($request->country);
            $countryExist = AllCountry::firstWhere('iso3', $country);
            if(!empty($countryExist)){
                $where = is_numeric($id) ? [['id','!=',$id],['country','=',$country]] : [['country','=',$country]];
                $checkExistingCon = Country::where($where)->first();
                if(!empty($checkExistingCon) > 0){
                    $resp = array('status'=>0, 'msg'=>__('adminWords.country').' '.__('adminWords.already_exist'));
                }else{
                    $checkCon = is_numeric($id) ? Country::find($id) : [];
                    $addCountry = empty($checkCon) ? Country::create(['country'=>$country]) : $checkCon->update(['country'=>$country]);
                    if($addCountry){
                        $resp = array('status'=>1, 'msg'=>__('adminWords.country').' '.__('adminWords.success_msg'));
                    }else{
                        $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg') );
                    }
                }
            }else{
                $resp = array('status'=>0, 'msg'=>__('adminWords.invalid').' '.__('adminWords.iso_code'));
            }            
        }else{
           $resp = $checkValidate;
        }
       echo json_encode($resp);
    }

    function getCountryName($id){
        $countryName = select(['table'=>'countries','column'=>'country', 'where'=>['id'=>$id], 'limit'=>1]);
        if(!empty($countryName)){
            $resp = ['status'=>1, 'data'=>$countryName];
        }else
            $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        echo json_encode($resp);
    }

    public function show($id){
        return view('location::show');
    }

    public function edit($id){
        return view('location::edit');
    }

    public function destroyCountry($id){
        $resp = singleDelete([ 'table'=>'countries','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.country').' '.__('adminWords.delete_success')]);
        echo $resp;
    }

    public function bulkDeleteCountry(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.country').' '.__('adminWords.must_selected'));
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'countries', 'column'=>'id', 'msg'=>__('adminWords.country').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function state(){
        $data['country'] = AllCountry::pluck('nicename', 'id');
        return view('location::state', $data);
    }

    public function stateData(){
        $state = select(['column'=>['all_states.id','all_states.name as state', 'all_countries.nicename as country'], 'table'=>'all_states', 'join'=>[['all_countries','all_states.country_id','=','all_countries.id']], 'order'=>['all_states.id','desc'] ]);
        return DataTables::of($state)
        ->addIndexColumn() 
        ->editColumn('action', function($state){
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
                            <a class="statePopupToggle" data-url="'.url('getStateName/'.$state->id).'" data-save="'.url('saveState/'.$state->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>                        
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','action'])
        ->make(true);
    }

    public function getStateName($id){
        $stateName = select(['table'=>'all_states','column'=>['id','name','country_id'], 'where'=>['id'=>$id], 'limit'=>1]);
        if(!empty($stateName)){
            $resp = ['status'=>1, 'data'=>$stateName];
        }else
            $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        echo json_encode($resp);
    }

    public function saveState(Request $request, $id){
        $checkValidate = validation($request->except('_token'), [
            'state' => 'required',
            'country_id' => 'required'
        ]);
        if($checkValidate['status'] == 1){
            $where = is_numeric($id) ? [['id','!=',$id],['name','=',$request->state],['country_id','=',$request->country_id]] : [['name','=',$request->state], ['country_id','=',$request->country_id]];
            $checkState = AllState::where($where)->first();
            if(!empty($checkState)){
                $resp = ['status'=>0, 'msg'=>__('adminWords.state').' '.__('adminWords.already_exist')];
            }else{
                $data = ['name'=>$request->state, 'country_id'=>$request->country_id];
                $checkState = is_numeric($id) ? AllState::find($id) : [];
                
                $addUpdate = empty($checkState) ? AllState::create($data) : $checkState->update($data);
                if($addUpdate)
                    $resp = ['status'=>1, 'msg'=>__('adminWords.state').' '.__('adminWords.success_msg')];
                else
                    $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function city(){
        $data['state'] = AllState::pluck('name', 'id');
        return view('location::city', $data);
    }

    public function cityData(){

        $city = select(['column'=>['all_states.name as state','all_cities.id','all_cities.name as city'], 'table'=>'all_cities', 'join'=>[['all_states','all_cities.state_id','=','all_states.id']], 'order'=>['all_cities.id','desc'] ]);
        
        return DataTables::of($city)
        ->addIndexColumn() 
        ->editColumn('action', function($city){
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
                            <a class="cityPopupToggle" data-url="'.url('getCityName/'.$city->id).'" data-save="'.url('saveCity/'.$city->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>                        
                    </ul>
                </div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function getCityName($id){
        $cityName = select(['table'=>'all_cities','column'=>['id','name','state_id'], 'where'=>['id'=>$id], 'limit'=>1]);
        if(sizeof($cityName) > 0){
            $resp = ['status'=>1, 'data'=>$cityName];
        }else
            $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        echo json_encode($resp);
    }

    public function saveCity(Request $request, $id){
        $checkValidate = validation($request->except('_token'), [
            'city' => 'required',
            'state_id' => 'required'
        ]);
        if($checkValidate['status'] == 1){
            $where = is_numeric($id) ? [['id','!=',$id],['name','=',$request->city],['state_id','=',$request->state_id]] : [['name','=',$request->city], ['state_id','=',$request->state_id]];
            $checkState = AllCity::where($where)->first();
            if(!empty($checkState)){
                $resp = ['status'=>0, 'msg'=>__('adminWords.city').' '.__('adminWords.already_exist')];
            }else{
                $data = ['name'=>$request->city, 'state_id'=>$request->state_id, 'updated_at'=>date('Y-m-d h:i:s')];
                $addUpdate = AllCity::updateOrCreate($data);
                if($addUpdate)
                    $resp = ['status'=>1, 'msg'=>__('adminWords.city').' '.__('adminWords.success_msg')];
                else
                    $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }
}
