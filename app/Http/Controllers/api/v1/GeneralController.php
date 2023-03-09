<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\General\Entities\BlogCategories;
use Modules\General\Entities\Pages;
use Modules\General\Entities\Blogs;
use Illuminate\Http\Request;
use App\User; 
use stdClass;

class GeneralController extends Controller
{
    
    public $successStatus = true;
    public $errorStatus = false;
    public $errorMsg = 'Something went wrong.';


    /**
     * Get All Pages For App Information. 
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppInfo(Request $request) 
    {
        if($request->isMethod('get')){

            //if(!empty(Auth::user())){
                $pages = Pages::where('is_active','1')->orderBy('id','desc')->get()->toArray();
                
                if(!empty($pages)){
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "App information successfully found.";                    
                    $response['data'] =  $pages;
                    
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = 'App information does not found.';
                    $response['data'] =  [];
                }
            // }else{
            //     $response['status'] = $this->errorStatus;
            //     $response['msg'] = 'Unauthenticated.';
            //     $response['data'] =  [];
            // }
            
            return response()->json($response);
        }
    }
    
    
    /**
     * Get All Blog By Category Id. 
     *
     * @return \Illuminate\Http\Response
     */
    public function getBlogByCategoryId(Request $request) 
    {
        if($request->isMethod('post')){
            

            if(!empty(Auth::user())){
                if(isset($request->blog_cat_id) && !empty($request->blog_cat_id)){
                    
                    $blogs = Blogs::where(['is_active'=>'1','blog_cat_id'=>$request->blog_cat_id])->orderBy('id','desc')->get()->toArray();
                    
                    if(!empty($blogs)){
                        $response['status'] = $this->successStatus;
                        $response['msg'] = "Blog successfully found.";                    
                        $response['data'] =  $blogs;
                        
                    }else{
                        $response['status'] = $this->errorStatus;
                        $response['msg'] = 'Blog does not found.';
                        $response['data'] =  [];
                    }
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = "Blog Category id is required.";
                    $response['data'] =  new stdClass();
                }
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  [];
            }
            
            return response()->json($response);
        }
    }
    
    /**
     * Get All Blogs. 
     *
     * @return \Illuminate\Http\Response
     */
    public function getBlogs(Request $request) 
    {
        if($request->isMethod('get')){
            
            if(!empty(Auth::user())){
                $data = [];
                $data['blogCategories'] = BlogCategories::where('is_active',1)->get()->toArray();
                $data['blogs'] = Blogs::where('is_active', 1)->get()->toArray();
                
                if(!empty($data)){
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "Blog successfully found.";                    
                    $response['data'] =  $data;
                    
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = 'Blog does not found.';
                    $response['data'] =  [];
                }
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  [];
            }
            return response()->json($response);
        }
    }
    
    /**
     * Get Blog By Id. 
     *
     * @return \Illuminate\Http\Response
     */
    public function getBlogById(Request $request) 
    {
        if($request->isMethod('post')){

            if(!empty(Auth::user())){
                
                if(isset($request->blog_id) && !empty($request->blog_id)){
                    $data = [];
                    $data['blogCategories'] = BlogCategories::where('is_active',1)->get()->toArray();
                    $data['blog'] = Blogs::find($request->blog_id);
                    
                    if(!empty($data)){
                        $response['status'] = $this->successStatus;
                        $response['msg'] = "Blog successfully found.";                    
                        $response['data'] =  $data;
                        
                    }else{
                        $response['status'] = $this->errorStatus;
                        $response['msg'] = 'Blog does not found.';
                        $response['data'] =  [];
                    }
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = "Blog id is required.";
                    $response['data'] =  new stdClass();
                }
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  [];
            }
            return response()->json($response);
        }
    }

}
