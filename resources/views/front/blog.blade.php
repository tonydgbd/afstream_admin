@extends('layouts.front.main')
@section('title', __('adminWords.blogs'))
@inject('blogCounts', 'Modules\General\Entities\Blogs')
@section('content')
    <div class="ms_artist_wrapper common_pages_space">
        <div class="ms_blog_inner">
        
            <!-- Top Genres section -->
            <div class="blog-thumb-wrapper">
                    <div class="slider_heading_wrap">
                        <div class="slider_cheading">
                            <h4 class="cheading_title">{{ __('adminWords.blogs') }} &nbsp;</h4>
                        </div>
                    </div>
                    <div class="containerr">
                        <div class="row">
                            <div class="col-xl-9 col-lg-12 col-md-12">
                                 <div class="row">
                                @php
                                    if(sizeof($blogs) > 0){
                                @endphp
                                        
                                            @php
                                            if(sizeof($blogs) > 0){
                                                $html = '';
                                                foreach($blogs as $blog) {
                                                    if($blog->image != '' && file_exists(public_path('images/blogs/'.$blog->image))){
                                                        $img = ' <img src="'.asset('public/images/blogs/'.$blog->image).'" class="img-fluid">';
                                                    }else{
                                                        $img = '<img src="'.dummyImage('blog').'" class="img-fluid">';
                                                    }
        
                                                        echo'<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 col-12">
                                                            <div class="slider_cbox slider_artist_box blog_thumb_section">
                                                                <div class="slider_cimgbox slider_artist_imgbox">'.$img.'</div>
                                                                <div class="slider_ctext slider_artist_text">
                                                                     <header class="entry-header">
                                                                        <div class="entry-meta">
                                                                            <span class="posted-on">
                                                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                                                                <a href="javascript:;" rel="bookmark">
                                                                                    <time class="entry-date published" datetime="2022-09-21 11:04:35">Sep 21, 2022</time>
                                                                                </a>
                                                                            </span>
                                                                            <span class="byline">
                                                                                <span class="ms-separator"></span>
                                                                                <i class="fa fa-user" aria-hidden="true"></i> 
                                                                                <span class="author vcard">
                                                                                    <a class="url fn n" href="https://pixelnx.in/musioo_artist">Admin</a>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                    </header>
                                                                    <a class="slider_ctitle blog_title" href="'.url('blog/single/'.$blog->id.'/'.$blog->slug).'">'.$blog->title.'</a>
                                                                    
                                                                    <p class="slider_cdescription slider_artist_des">'.str_limit(strip_tags(htmlspecialchars_decode($blog->detail)),65).'</p> 
                                                                    
                                                                    <a class="read_more_linkk " href="'.url('blog/single/'.$blog->id.'/'.$blog->slug).'">
                                                                        Read More
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>';   
                                                }
        
                                            }else{
                                                echo '<div class="ms_empty_data">
                                                        <p>'.__("frontWords.no_blog").'</p>
                                                    </div>';
                                            }
                                            @endphp                     
                                        
                            @php
                                }else{
                                    echo '<div class="ms_empty_data">
                                            <p>'.__("frontWords.no_blog").'</p>
                                        </div>';
                                }
                            @endphp
                        </div>
                         </div>
                        <!--SideBar-->
                        <div class="col-xl-3 col-lg-12 col-md-12 fixBlogSidebar">
                            
                            <div class="sidebar_wrapper blogsCategorySidebar">
                               <section class="widget widget_categories">
                                	<h2 class="widget-title">Categories</h2>
                                	<ul>
                                	    @forelse($blogCategories as $category)
                                	        
                                	        @php 
                                	            $blogs = $blogCounts->where(['blog_cat_id' => $category['id'], 'is_active' => '1'])->get();
                                	            $keywords = [];
                                	            $keywords = explode(",",$blog['keywords']);
                                	        @endphp
                                	        @if(count($blogs) > 0)
                                        		<li>
                                        		    @if(count($blogs) == 1)
                                        			    <a href="{{ url('blog/single/'.$blogs[0]->id.'/'.$blogs[0]->slug) }}">{{ $category['title'] }}</a>
                                        			    <!--<span class="ms_cat_count">{{ count($blogs) }}</span>-->
                                        			@else
                                        			    <a href="{{ url('blog/multiple/'.$category['id']) }}">{{ $category['title'] }}</a>
                                        			@endif
                                        			    <span class="ms_cat_count">{{ count($blogs) }}</span>
                                        		</li>
                                    		@endif
                                	    @empty
                                    	    <div class="ms_empty_data">
                                                <p>No category available.</p>
                                            </div>
                                	    @endforelse
                                	</ul>
                                </section>
                                
                                <!--<section class="widget widget_image">-->
                                <!--    <img src="https://pixelnx.in/musioo_artist/public/assets/images/offer-banner-img.jpg" alt="Musioo" />-->
                                <!--</section>-->
                                
                            </div>
                        </div>
                        
                        
                        </div>
                    </div>
                   
                </div>
                
        </div>
        @include('layouts.front.footer')
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
@endsection