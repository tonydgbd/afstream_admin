<?php
namespace Modules\General\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;
use Modules\Audio\Entities\Audio;
use App\User;
use App\Comment;
use App\UserAction;
use App\Reply;
use Auth;
use Modules\General\Entities\InvoiceSetting;
use Modules\General\Entities\BlogCategories;
use Modules\General\Entities\Blogs;
use Modules\General\Entities\Pages;
use Modules\General\Entities\Testimonial;
use Modules\General\Entities\Slider;
use Modules\General\Entities\Faq;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Stevebauman\Purify\Facades\Purify;
use Crypt;
use DB;

class GeneralController extends Controller{

    public function faqs(){
        return view('general::faq.index');
    }

    public function faqsData(){
        $faqs = select(['table'=>'faq','column'=>'*','order'=>['id','desc'] ]);
        return DataTables::of($faqs)
        ->editColumn('checkbox', function($faq){
            return '<div class="checkbox danger-check"><input id="checkboxAll'.$faq->id.'" type="checkbox" class="CheckBoxes faqCheckBox" value="'.$faq->id.'"><label for="checkboxAll'.$faq->id.'"></label></div>';
        })
        ->editColumn('answer', function($faq){
            return '<p class="limited-text-pera">'.htmlspecialchars_decode($faq->answer).'<a class="ms_read_moreFAQ" href="'.url('updateFaq/'.$faq->id).'">Read More</a></p>';
        })
        ->editColumn('status', function($faq){ 
            return '<div class="checkbox success-check"><input id="checkboxc'.$faq->id.'" name="status" class="updateStatus" '.($faq->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('updateFaqStatus/'.$faq->id).'"><label for="checkboxc'.$faq->id.'"></label></div>';
        })
        ->editColumn('action', function($faq){
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
                            <a href="'.url('updateFaq/'.$faq->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('destroyFaq/'.$faq->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','answer','status','action'])->make(true);
    }

    public function updateFaq($id){
        $data['faqs'] = Faq::find($id);
        return view('general::faq.create', $data);
    }


    public function addFaq(){
        return view('general::faq.create');
    }

    public function updateFaqStatus(Request $request, $id){
        $checkValidate = validation($request->all(), ['status'=>'required'] );
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'faq', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function addUpdateFaq(Request $request, $id){
        $faqs = [];
        if(is_numeric($id)){
            $faqs = Faq::find($id);
        }
        $data = $request->except('_token');
        $data['status'] = (isset($data['status']) ? 1 : 0);
        
        $checkValidation = validation($data, [ 'question'=>'required', 'answer'=>'required' ]);
        if($checkValidation['status'] == 1){
            $data['answer'] = Purify::clean($data['answer']);
            if(!empty($faqs)){
                $update = $faqs->update($data);
                $msg = __('adminWords.faq').' '.__('adminWords.updated_msg');
            }else{
                $update = Faq::create($data);
                $msg = __('adminWords.faq').' '.__('adminWords.success_msg');
            }
            $resp = ['status'=>1, 'msg'=>$msg];
        }else{
            $resp = $checkValidation;
        }
        echo json_encode($resp);        
    }

    public function destroyFaq($id){
        $resp = singleDelete([ 'table'=>'faq','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.faq').' '.__('adminWords.delete_success')]);
        echo $resp;
    }

    public function bulkDeleteFaq(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').' '.__('adminWords.faq').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'faq', 'column'=>'id', 'msg'=>__('adminWords.faq').' '.__('adminWords.delete_success') ,'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function blogs(){
        return view('general::blog.index');
    }

    public function blog_category(){
        return view('general::blog_category.index');
    }

    public function blogsCategoryData(){
        $category = select(['table'=>'blog_categories','column'=>['title','id','is_active','created_at'], 'order'=>['id','desc'] ]);
        return DataTables::of($category)
        ->editColumn('checkbox', function($category){
            return '<div class="checkbox danger-check"><input id="checkboxAll'.$category->id.'" type="checkbox" class="CheckBoxes" value="'.$category->id.'"><label for="checkboxAll'.$category->id.'"></label></div>';
        })
        ->editColumn('created_at', function($category){
            return date('d-m-Y', strtotime($category->created_at));
        })
        ->editColumn('status', function($category){
            return '<div class="checkbox success-check"><input id="checkboxc'.$category->id.'" class="updateStatus" '.($category->is_active == 1 ? 'checked':'').' type="checkbox" data-url="'.url('blogCatStts/'.$category->id).'"><label for="checkboxc'.$category->id.'"></label></div>';
        })
        ->editColumn('action', function($category){
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
                            <a class="blogCategoryPopupToggle mr-2" data-url="'.url('getBlogCategoryName/'.$category->id).'" data-save="'.url('createBlogCat/'.$category->id).'"><i class="far fa-edit mr-2"></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('destroyBlogCat/'.$category->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','status','action'])->make(true);
    }

    public function getBlogCategoryName($id){
        $cat = BlogCategories::find($id);
        if(!empty($cat)){
            $resp = ['status'=>1, 'data'=>$cat];
        }else{
            $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        }
        echo json_encode($resp);
    }

    public function blogCatStts(Request $request, $id){
        $checkValidate = validation($request->all(), ['status'=>'required'] );
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'blog_categories', 'where'=>['id'=>$id],'data'=> ['is_active'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function createBlogCat(Request $request, $id){
        if(!is_numeric($id))
            $rules = ['title'=>'required|unique:blog_categories'];
        else
            $rules = [];

        $checkValidate = validation($request->all(), $rules);

        if($checkValidate['status'] == 1){
            $arr = [
                'title'=>$request->title,
                'slug'=>Str::slug($request->title,'-'),
                'is_active'=>isset($request->is_active) ? '1' : '0'
            ];
            if(is_numeric($id)){
                $checkSlug = BlogCategories::where([['id','!=',$id],['slug','=', $arr['slug']]])->get();
            }else{
                $checkSlug = BlogCategories::where('slug', $arr['slug'])->get();
            }    
            if(sizeof($checkSlug) > 0){
                $resp = ['status'=>0, 'msg'=>__('adminWords.category').' '.__('adminWords.already_exist')];
            }else{
                $cat = is_numeric($id) ? BlogCategories::find($id) : [];
                if(!empty($cat)){
                    $cat->update($arr);
                    $msg = __('adminWords.category').' '.__('adminWords.updated_msg');
                }else{
                    BlogCategories::create($arr);
                    $msg = __('adminWords.category').' '.__('adminWords.added_msg');
                }
                $resp = ['status'=>1, 'msg'=>$msg];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function destroyBlogCat($id){
        $resp = singleDelete([ 'table'=>'blog_categories','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.category').' '.__('adminWords.delete_success')]);
        if($resp){
            Blogs::where('blog_cat_id',$id)->delete();
        }
        echo $resp;
    }

    public function destroyBlog($id){
        $resp = singleDelete([ 'table'=>'blogs','column'=>'id','where'=>['id'=>$id], 'msg'=> __('adminWords.blog').' '.__('adminWords.delete_success')]);
        echo $resp;
    }

    public function bulkDeleteBlogCat(Request $request){
       
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.category').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'blog_categories', 'column'=>'id', 'msg'=>__('adminWords.category').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]);
            if($resp && !empty($request->checked)){
                Blogs::whereIn('blog_cat_id',$request->checked)->delete();
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function blogsData(){
        $blog = select(['table'=>'blogs','column'=>['blogs.image','blogs.title','blogs.blog_cat_id','blogs.detail','blogs.id','blogs.is_active','blogs.created_at'], 'order'=>['id','desc'] ]);
        
        return DataTables::of($blog)
        ->editColumn('checkbox', function($blog){
            return '<div class="checkbox danger-check"><input id="checkboxAll'.$blog->id.'" type="checkbox" class="CheckBoxes" value="'.$blog->id.'"><label for="checkboxAll'.$blog->id.'"></label></div>';
        })
        ->editColumn('image', function($blog){
            if($blog->image != '' && file_exists(public_path('/images/blogs/'.$blog->image)))
                $src = asset('public/images/blogs/'.$blog->image);
            else
                $src = asset('public/images/sites/1050x700.png');
            return '<span class="img-thumb"><img src="'.$src.'" alt="" class="img-fluid" width="102px" height="68px"></span>';
        })
        ->editColumn('detail', function($blog){
            return '<div class="limited-text-pera blogDetails">'.htmlspecialchars_decode($blog->detail).'</div>';
        })
        ->editColumn('category', function($blog){
            $categoryName = BlogCategories::select('title')->find($blog->blog_cat_id);
            return $categoryName->title;
        })
        ->editColumn('created_at', function($blog){
            return date('d-m-Y', strtotime($blog->created_at));
        })
        ->editColumn('is_active', function($blog){
            return '<div class="checkbox success-check"><input id="checkboxc'.$blog->id.'" class="updateStatus" '.($blog->is_active == 1 ? 'checked':'').' type="checkbox" data-url="'.url('blogStts/'.$blog->id).'"><label for="checkboxc'.$blog->id.'"></label></div>';
        })
        ->editColumn('action', function($blog){
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
                            <a href="'.url('editBlog/'.$blog->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="'.url('comments/blog/'.$blog->title.'/'.Crypt::encrypt($blog->id)).'"><i class="fa fa-comment mr-2" aria-hidden="true"></i>'.__('adminWords.comment').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('destroyBlog/'.$blog->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','detail','image', 'is_active','action'])->make(true);
    }
     
    public function editBlog($id){
        $data['users'] = User::where([['status','=','1'],['name','!=','']])->pluck('name','id')->all();
        $data['blog_category'] = BlogCategories::where('is_active','1')->pluck('title','id')->all();
        $data['blogData'] = Blogs::where('id',$id)->get();
        
        if(isset($data['blogData']) && !empty($data['blogData'][0])){
            $data['blogData'] = $data['blogData'][0];
        }
        return view('general::blog.addEdit', $data);
    }
    
    public function blogStts(Request $request, $id){
        $checkValidate = validation($request->all(), ['status'=>'required'] );
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'blogs', 'where'=>['id'=>$id],'data'=> ['is_active'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function create_blog(){
        $data['users'] = User::where([['status','=','1'],['name','!=','']])->pluck('name','id')->all();
        $data['blog_category'] = BlogCategories::where('is_active','1')->pluck('title','id')->all();
        return view('general::blog.addEdit', $data);
    }

    public function addEditBlog(Request $request, $id){
        
        $input = $request->except('_token');
        $data = [
            'title' => 'required',
            'blog_cat_id'=>'required',
            'detail' => 'required|min:3'
        ];
        if(!is_numeric($id)){
            $data['image'] = 'required|mimes:jpg,png,jpeg';
        }
        $checkValidate = validation($input, $data);
        if($checkValidate['status'] == 1){ 
                $input['slug'] = Str::slug($input['title'],'-');
            if(is_numeric($id)){
                $checkSlug = Blogs::where([['id','!=',$id],['slug','=',$input['slug']]])->get();
            }else{
                $id = get_increment_id('blogs');  
                $data['title'] = 'required|unique:blogs';
                $checkSlug = Blogs::where('slug',$input['slug'])->get();
            }
            
            if(sizeof($checkSlug) > 0){
                $resp = ['status'=>0, 'msg'=>__('adminWords.blog').' '.__('adminWords.already_exist')];
            }else{
                $blogs = is_numeric($id) ? Blogs::find($id) : [];
                $input['is_active'] = (isset($input['is_active'])) ? 1 : 0;
                if($image = $request->file('image')){
                    $name = 'blog'.$id.'-'.time().'.webp';
                    upload_image($image, public_path().'/images/blogs/', $name, '1050x700');
                    $input['image'] = str_replace(' ','',$name);
                }
                $input['user_id'] = Auth::user()->id;
                $input['detail'] = Purify::clean($input['detail']);
                
                if(isset($request->keywords) && !empty($request->keywords)){
                    $input['keywords'] = $request->keywords;
                }
                
                if(!empty($blogs)){
                    $blogs->update($input);
                    $msg = __('adminWords.blog').' '.__('adminWords.updated_msg');
                }else{
                    $blog = Blogs::create($input);
                    $msg = __('adminWords.blog').' '.__('adminWords.added_msg');
                }
                $resp = ['status'=>1, 'msg'=>$msg];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function bulkDeleteBlog(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').' '.__('adminWords.blog').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'blogs', 'column'=>'id', 'msg'=>__('adminWords.blog').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }


    public function pages(){
        return view('general::pages.index');
    }

    public function pagesData(){
        $pages = Pages::orderBy('id','desc')->get();
        return DataTables::of($pages)
        ->editColumn('checkbox',function($pages){
            if($pages->slug != 'terms-of-use' && $pages->slug != 'privacy-policy'){
                return '<div class="checkbox danger-check"><input id="checkboxAll'.$pages->id.'" type="checkbox" class="CheckBoxes" value="'.$pages->id.'"><label for="checkboxAll'.$pages->id.'"></label></div>';
            }else{
                return '';
            }
        })
        ->editColumn('is_active', function($pages){
            if($pages->slug != 'terms-of-use' && $pages->slug != 'privacy-policy'){
                return '<div class="checkbox success-check"><input id="checkboxc'.$pages->id.'" class="updateStatus" '.($pages->is_active == 1 ? 'checked':'').' type="checkbox" data-url="'.url('pagesStts/'.$pages->id).'"><label for="checkboxc'.$pages->id.'"></label></div>';
            }else{
                return '';
            }
        })
        ->editColumn('detail', function($pages){
            return  substr_replace(strip_tags(htmlspecialchars_decode($pages->detail)), "...", 80);  //strip_tags(htmlspecialchars_decode($pages->detail));
        })
        ->editColumn('created_at', function($pages){
            return date('d-m-Y', strtotime($pages->created_at));
        })
        ->editcolumn('action', function($pages){
            if($pages->slug != 'terms-of-use' && $pages->slug != 'privacy-policy'){
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
                            <a href="'.url('editPage/'.$pages->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>                        
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('destroyPage/'.$pages->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
            }else{
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
                                <a href="'.url('editPage/'.$pages->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                            </li>                        
                        </ul>
                    </div>';
                
            }
        })
        ->rawColumns(['checkbox','is_active','action'])->make(true);
        
    }

    public function create_page(){
        return view('general::pages.addEdit');
    }

    public function editPage($id){
        $data['pageData'] = Pages::find($id);
        return view('general::pages.addEdit', $data);
    }

    public function addEditPage(Request $request, $id){
        $input = $request->except('_token');
        $checkValidate = validation($input, [
            'title' => 'required',
            'detail' => 'required'
        ]);
        
        if($checkValidate['status'] == 1){
            $input['is_active'] = !isset($input['is_active']) ? 0 : 1;
            $input['slug'] = Str::slug($input['title'],'-');
            $input['detail'] = Purify::clean($input['detail']);
            if(is_numeric($id)){
                $checkSlug = Pages::where([['id','!=',$id],['slug','=',$input['slug']]])->get();
            }else{
                $checkSlug = Pages::where([['slug','=',$input['slug']]])->get();
            }
            if(count($checkSlug) > 0){
                $resp = ['status'=>0, 'msg'=> __('adminWords.page').' '.__('adminWords.already_exist')];
            }else{
                $pages = is_numeric($id) ?  Pages::find($id) : [];
                if(!empty($pages)){
                    $addUpdate = $pages->update($input);
                    $msg = __('adminWords.pages').' '.__('adminWords.updated_msg');
                }else{
                    $addUpdate = Pages::create($input);
                    $msg = __('adminWords.pages').' '.__('adminWords.added_msg');;
                }
                $resp = ['status'=>1, 'msg'=>$msg];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function pagesStts(Request $request, $id){
        $checkValidate = validation($request->all(), ['status'=>'required'] );
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'pages', 'where'=>['id'=>$id],'data'=> ['is_active'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function destroyPage($id){
        $resp = singleDelete([ 'table'=>'pages','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.pages').' '.__('adminWords.delete_success') ]);
        echo $resp;
    }

    public function bulkDeletePages(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').' '.__('adminWords.page').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'pages', 'column'=>'id', 'msg'=>__('adminWords.pages').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

  
    public function testimonial(){
        return view('general::testimonial.index');
    }

    public function testimonialData(){
        $testimonial = Testimonial::orderBy('id','desc')->get();
        return DataTables::of($testimonial)
        ->editColumn('checkbox',function($testimonial){
            return '<div class="checkbox danger-check"><input id="checkboxAll'.$testimonial->id.'" type="checkbox" class="CheckBoxes" value="'.$testimonial->id.'"><label for="checkboxAll'.$testimonial->id.'"></label></div>';
        })
        ->editColumn('detail', function($testimonial){
            return strip_tags(htmlspecialchars_decode($testimonial->detail));
        })
        ->editColumn('image', function($testimonial){
            if($testimonial->image != '' && file_exists(public_path('/images/testimonial/'.$testimonial->image)))
                $src = asset('public/images/testimonial/'.$testimonial->image);
            else
                $src = asset('public/images/sites/50x50.png');
            return '<img src="'.$src.'" alt="" class="img-fluid" width="50px" height="50px">';
        })
        ->editColumn('status', function($testimonial){
            return '<div class="checkbox success-check"><input id="checkboxc'.$testimonial->id.'" class="updateStatus" '.($testimonial->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('tesimonialStts/'.$testimonial->id).'"><label for="checkboxc'.$testimonial->id.'"></label></div>';
        })
        ->editcolumn('action', function($testimonial){
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
                            <a href="'.url('testimonial/edit/'.$testimonial->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>                        
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('destroyTestimonial/'.$testimonial->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','image','status','action'])->make(true);
    }

    public function createTestimonial(){
        return view('general::testimonial.addEdit');
    }

    public function editTestimonial($id){
        $data['testimonials'] = Testimonial::find($id);
        return view('general::testimonial.addEdit', $data);
    }

    public function addEditTestimonial(Request $request, $id){
        $input = $request->except('_token');
        $checkValidate = validation($input, [
            'client_name' => 'required',
            'detail'      => 'required',
            'image'    => 'nullable|image|mimes:jpg,png,gif,jpeg'
        ]);
        if($checkValidate['status'] == 1){
            $testimonial = is_numeric($id) ? Testimonial::find($id) : [];
            $input['rating'] = isset($input['rating']) ? $input['rating'] : 0;
            if($image = $request->file('image')){
                $name = 'user-'.time().'.webp';
                upload_image($image, public_path().'/images/testimonial/', $name, '50x50');
                $input['image'] = str_replace(' ','',$name);
            }
            if(!empty($testimonial)){
                $addUpdate = $testimonial->update($input);
                $msg = __('adminWords.testimonial').''.__('adminWords.updated_msg');
            }else{
                $addUpdate = Testimonial::create($input);
                $msg = __('adminWords.testimonial').''.__('adminWords.added_msg');
            }
            $resp = ['status'=>1, 'msg'=>$msg];
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function tesimonialStts(Request $request, $id){
        $checkValidate = validation($request->all(), ['status'=>'required'] );
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'testimonials', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function destroyTestimonial($id){
        $resp = singleDelete([ 'table'=>'testimonials','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.testimonial').' '.__('adminWords.delete_success')]);
        echo $resp;
    }

    public function bulkDeleteTestimonial(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.testimonial').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'testimonials', 'column'=>'id', 'msg'=>__('adminWords.testimonial').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }
    
    public function slider(){
        return view('general::slider.index');
    }

    public function sliderData(){
        $slider = Slider::all();
        return DataTables::of($slider)
        ->editColumn('checkbox', function($slider){
            return '<div class="inline custom-checkbox getSliderId checkbox" data-id="'.$slider->id.'"><input id="checkboxAll'.$slider->id.'" type="checkbox" class="CheckBoxes" value="'.$slider->id.'"><label for="checkboxAll'.$slider->id.'"></label></div>';
        })
        ->editColumn('image', function($slider){
            if($slider->image != '' && file_exists(public_path('/images/slider/'.$slider->image)))
                $src = asset('public/images/slider/'.$slider->image);
            else
                $src = asset('public/images/sites/1660x800.png');
            return '<img src="'.$src.'" alt="" class="img-fluid" width="170px" height="82px">';
        })
        ->editColumn('created_at', function($slider){
            return date('d-m-Y', strtotime($slider->created_at));
        })
        ->editColumn('status', function($slider){
            return '<div class="checkbox success-check"><input id="checkboxc'.$slider->id.'" name="status" class="updateStatus" '.($slider->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('updateSliderStatus/'.$slider->id).'"><label for="checkboxc'.$slider->id.'"></label></div>';
        })
        ->editColumn('action', function($slider){
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
                            <a href="'.url('slider/edit/'.$slider->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>                        
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('destroySlider/'.$slider->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','image','status','action'])
        ->make(true);
    }

    public function updateSliderStatus(Request $request, $id){
        $checkValidate = validation($request->all(), ['status'=>'required'] );
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'sliders', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function create_slider(){
        return view('general::slider.addEdit');
    }

    public function edit_slider($id){
        $data['sliderData'] = Slider::firstWhere('id', $id);
        return view('general::slider.addEdit', $data);
    }

    public function addEditSlider(Request $request, $id){
        $input = $request->except('_token');
        $rules = [ 'title' => 'required' ];
        $rules['image'] = is_numeric($id) ?  'nullable|image|mimes:jpg,png,jpeg' : 'required|image|mimes:jpg,png,jpeg';
        $checkValidate = validation($input, $rules);
        if($checkValidate['status'] == 1){
            $where = is_numeric($id) ? [['id','!=', $id],['title','=',$request->title]] : [['title','=',$request->title]];
            $slider = Slider::firstWhere($where);
            if($image = $request->file('image')){
                $name = 'slider-'.time().'.webp';
                upload_image($image, public_path().'/images/slider/', $name, '1660x800');
                $input['image'] = str_replace(' ','',$name);
            }
            if(!empty($slider)){
                $resp = ['status'=>0, 'msg'=>__('adminWords.slider').' '.__('adminWords.already_exist')];
            }
            else{
                $sliders = is_numeric($id) ? Slider::find($id) : [];
                if(!empty($sliders)){
                    $addUpdate = $sliders->update($input);
                }else{
                    $input['position'] = get_increment_id('sliders');
                    $addUpdate = Slider::create($input);
                }
                if($addUpdate)
                    $resp = ['status'=>1, 'msg'=>__('adminWords.slider').' '.__('adminWords.success_msg')];
                else
                    $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function destroySlider($id){
        $resp = singleDelete([ 'table'=>'sliders','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.slider').' '.__('adminWords.delete_success')]);
        echo $resp;
    }

    public function bulkDeleteSlider(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').' '.__('adminWords.slider').' '.__('adminWords.must_selected') );
        $resp = ($checkValidate['status'] == 1 ? bulkDeleteData(['table'=>'sliders', 'column'=>'id', 'msg'=>__('adminWords.slider').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]) : $checkValidate);
        echo json_encode($resp);
    }

    public function saveSliderPosition(Request $request){
        $sliders = Slider::all();
        foreach($sliders as $slider){
            $id = $slider->id;
            $success = 0;
            foreach($request->order as $order){
                if($order['id'] == $id){
                    $update = $slider->update(['position' => $order['position'] ]);
                    $success = 1;
                }
            }
        }
        if($success == 1){
            $resp = ['status'=>1, 'msg'=>__('adminWords.slider').' '.__('adminWords.sort_msg')];
        }else{
            $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        }
        
        echo json_encode($resp);
    }

    public function comments($type, $name, $id){
        
        $data[$type.'_id'] = $id;
        $data[$type.'_name'] = $name;
        $data['type'] = $type;
        
        if($type == 'audio'){
            $audio_id = Crypt::decrypt($id);
            $comments = Comment::where('audio_id',$audio_id)->get()->toArray();
            if(isset($audio_id) && !empty($audio_id) && !empty($comments)){
                DB::table('comments')->where(['audio_id' => $audio_id])->update(['admin_view' => '1']);
            }
        }
        if($type == 'blog'){
            $blog_id = Crypt::decrypt($id);
            $comments = Comment::where('blog_id',$blog_id)->get()->toArray();
            if(isset($blog_id) && !empty($blog_id) && !empty($comments)){
                DB::table('comments')->where(['blog_id' => $blog_id])->update(['admin_view' => '1']);
            }
        }
        return view('general::comment.comment', $data);
    }

    public function commentData($type, $id){
        $id = Crypt::decrypt($id);
        $comment = Comment::where($type.'_id', $id)->orderBy('id','desc')->get();
        
        $commentArr = [];
        $user_name = $audio_name = '';
        if(!empty($comment)){
            foreach($comment as $comments){
                $getUserData = User::where('id',$comments->user_id)->limit(1)->get();
                if(sizeof($getUserData) > 0){
                    $user_name = $getUserData[0]->name;
                }
                array_push($commentArr, ['user_name' => $user_name, 'message' => $comments->message, 'created_at' => $comments->created_at, 'id' => $comments->id, 'status' => $comments->status, 'blog_id' => $id, 'type' => $type]);
            }
        }
       
        return DataTables::of($commentArr)
        ->editColumn('checkbox', function($comment){
            return '<div class="inline custom-checkbox getSliderId checkbox" data-id="'.$comment['id'].'"><input id="checkboxAll'.$comment['id'].'" type="checkbox" class="CheckBoxes" value="'.$comment['id'].'"><label for="checkboxAll'.$comment['id'].'"></label></div>';
        })
        ->editColumn('created_at', function($comment){
            return date('d-m-Y', strtotime($comment['created_at']));
        })
        ->editColumn('status', function($comment){
            return '<div class="checkbox success-check"><input id="checkboxc'.$comment['id'].'" name="status" class="updateStatus" '.($comment['status'] == 1 ? 'checked':'').' type="checkbox" data-url="'.url('comment/status/'.$comment['id']).'"><label for="checkboxc'.$comment['id'].'"></label></div>';
        })
        ->editColumn('action', function($comment){
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
                            <a href="javascript:void(0)" data-url="'.url('comment/reply/'.$comment['type'].'/'.$comment['id'].'/'.$comment['blog_id']).'" data-get-url="'.url('getReply/'.$comment['id']).'" id="replyOnUserComment"><i class="fa fa-reply mr-2 "></i>'.__('adminWords.reply').'</a>
                        </li>                        
                        <li>
                            <a href="javascript:void(0);" data-url="'.url('comment/destroy/'.$comment['id']).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','status','action'])
        ->make(true);
    }

    public function updateCommentStts(Request $request, $id){
        $checkValidate = validation($request->all(), ['status'=>'required'] );
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'comments', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function destroyComment($id){
        $resp = singleDelete([ 'table'=>'comments','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.comment').' '.__('adminWords.delete_success')]);
        echo $resp;
    }

    public function bulkDeleteComment(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.comment').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'comments', 'column'=>'id', 'msg'=>__('adminWords.comment').' '.__('adminWords.delete_success') ,'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public  function audio_rating(Request $request){
        if(isset(Auth::user()->id)){
            $checkValidate = validation($request->except('_token'), ['rating' => 'required']);
            if($checkValidate['status'] == 1){
                $checkUser = UserAction::where(['user_id' => Auth::user()->id, 'audio_id' => $request->audio_id])->get();
                if(sizeof($checkUser) > 0){
                    $addUpdate = UserAction::where(['user_id' => Auth::user()->id, 'audio_id' => $request->audio_id])->update(['rating' => $request->rating]);
                }else{
                    $addUpdate = UserAction::create([
                        'user_id' => Auth::user()->id,
                        'audio_id' => $request->audio_id,
                        'rating' => $request->rating
                    ]);
                }
                $resp = ['status' => 1, 'msg' => __('adminWords.rating').' '.__('adminWords.added_msg') ];
            }else{
                $resp = $checkValidate;
            }
        }else{
            $resp = ['status' => 0, 'msg' => __('adminWords.login_msg') ];
        }
        echo json_encode($resp);
    }

    public function get_reply_data(Request $request, $id){
        $checkReply = Reply::find($id);
        if(!empty($checkReply)){
            $resp = ['status' => 1, 'reply' => $checkReply->reply, 'msg' => ''];
        }else{
            $resp = ['status' => 2];
        }
        
        echo json_encode($resp);
    }

    public function replyComment(Request $request, $type, $cmnt_id, $id){
        $checkReply = Reply::where([$type.'_id' => $id, 'comment_id' => $cmnt_id])->get();
        if(sizeof($checkReply) > 0){ // update
            $update = Reply::where([$type.'_id' => $id, 'comment_id' => $cmnt_id])->update(['reply' => $request->reply]);
        }else{ // create
            $update = Reply::create([
                'comment_id' => $cmnt_id,
                'user_id' => Auth::user()->id,
                $type.'_id' => $id,
                'reply' => $request->reply
                ]);
        }
        echo json_encode(['status' => 1, 'msg' => __('adminWords.reply').' '.__('adminWords.success_msg') ]);
    }

    public function manual_transaction(){
        return view('general::manual_pay.manual');
    }

    public function manualPayData(){
        $transactionData = select(['table'=>'payment_gateways','column'=>['id', 'plan_id', 'payment_data', 'created_at', 'status'], 'where' => [ ['type', 'manual_pay']], 'order'=>['id','desc'] ]);
    
        $transaction = [];
        if(sizeof($transactionData) > 0){
            foreach($transactionData as $data){
                $plan_name = select(['column' => 'plan_name', 'table' => 'plans', 'where' => ['id' => $data->plan_id]]);
                $paymentDetail = json_decode($data->payment_data)[0];
                $arr = ['id' => $data->id, 'user_name' => $paymentDetail->user_name, 'order_id' => $paymentDetail->order_id, 'amount' => 
                $paymentDetail->currency.$paymentDetail->amount, 'ordered_at' => $data->created_at, 'status' => $data->status, 'proof' => $paymentDetail->payment_proof_doc];
                if(sizeof($plan_name) > 0){
                    $arr['plan_name'] = $plan_name[0]->plan_name; 
                }
                array_push($transaction, $arr);
            }
        }
        
        return DataTables::of($transaction)
        ->addIndexColumn() 
        ->editColumn('payment_proof', function($transaction){
            return '<img src="'.url('public/images/payment/'.$transaction['proof']).'" width="50" height="50" id="paymentProofImgPopup">';
        })
        ->editColumn('status', function($transaction){ 
            if($transaction['status'] == 1){
                return 'Approved';
            }else if($transaction['status'] == 0){
                return 'Rejected';
            }else{
                return '<select name="paymentStatus" class="form-control" data-payment-id="'.$transaction['id'].'">
                            <option value="2" '.($transaction['status'] == 2 ? 'selected' : '').'>pending</option>              
                            <option value="1" '.($transaction['status'] == 1 ? 'selected' : '').'>Approved</option>              
                            <option value="0" '.($transaction['status'] == 0 ? 'selected' : '').'>Rejected</option>              
                        </select>';
            }
        })
        ->rawColumns(['status','payment_proof'])->make(true);
    }

        public function invoice_setting(){
            $data['setting'] = select(['table'=>'invoice_settings','column'=>['*']]);
            return view('general::setting.invoice_setting', $data);
        }

        public function invoiceDetail(Request $request){
            $checkSetting = InvoiceSetting::all();
            $data = $request->except('_token');
            unset($data['termCond']);
            if($image = $request->file('author_sign')){
                $name = 'invoice-'.time().'.webp';
                $data['author_sign'] = str_replace(' ','',$name);
                upload_image($image, public_path().'/images/sites/', $name);
                if(sizeof($checkSetting) && $checkSetting[0]->invoice_data != '') {
                    $decodeData = json_decode($checkSetting[0]->invoice_data);
                    delete_file_if_exist(public_path().'/images/sites/'.$decodeData->author_sign);
                }
            }
            if(sizeof($checkSetting) > 0){ 
                if($request->file('author_sign') == ''){
                    $decodeData = json_decode($checkSetting[0]->invoice_data);
                    $data['author_sign'] = $decodeData->author_sign;
                }
                $updateSetting = InvoiceSetting::where('id', $checkSetting[0]->id)->update(['invoice_data' => json_encode($data) ]);
            }else{
                $createSetting = InvoiceSetting::create(['invoice_data' => json_encode($data) ]);
            }
            $resp = ['status' => 1, 'msg' => __('adminWords.detail').' '.__('adminWords.success_msg')];
            echo json_encode($resp);
        }

}
