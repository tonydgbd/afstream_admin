@inject('users', 'App\User')
@inject('blogCounts', 'Modules\General\Entities\Blogs')
    <div class="musiooFrontSinglePage">
        <div class="ms_artist_wrapper common_pages_space">
            @php
                if(sizeof($blogs) > 0){
            @endphp
            <main id="main" class="site-main">
                    <div class="ms_main_wrapper ms_main_wrapper_single">
        		        <div class="container">
                           	<div class="row">
                           	    
    				            <div class="col-xl-8 col-lg-12 col-md-12">
                           	        @foreach($blogs as $blog)
                                        <div class="ms_main_data"> 
                                            <article class="hentry">
                                                <div class="ms_single_page_image">
                                                    <span class="ms_blog_date">{{ date('F d, Y', strtotime($blog->created_at)) }}</span>
                                                        <img src="{{ asset('images/blogs/'.$blog->image) }}" alt="{{ $blog->title }}">
                                                </div>
                                                <header class="entry-header">
                                                    <div class="entry-meta">
                                                        <span class="posted-on">
                                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                                            <a href="javascript:;" rel="bookmark">
                                                                <time class="entry-date published" datetime="{{ $blog->created_at }}">{{ date('F d, Y', strtotime($blog->created_at)) }}</time>
                                                            </a>
                                                        </span>
                                                        <span class="byline">
                                                            <span class="ms-separator"></span>
                                                            <i class="fa fa-user" aria-hidden="true"></i> 
                                                            <span class="author vcard">
                                                                <a class="url fn n getAjaxRecord" data-type="home" data-url="{{ url('/home') }}" href="javascript:void(0)">Admin</a>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </header>
                                                <h4 class="blog-title">{{ $blog->title }}</h4>
                                                <div class="entry-content">
                                                    <p>{{ strip_tags(htmlspecialchars_decode($blog->detail)) }}</p>
                                                </div>
                                                <footer class="entry-footer ms-entry-footer"></footer>
                                            </article>
                                        </div>
    		                        @endforeach
    		                    </div> 
    		                    
    		                    <!--SideBar-->
    		                    <div class="col-xl-4 col-lg-12 col-md-12">
    		                        <div class="sidebar_wrapper">
		                               <section class="widget widget_categories">
                                        	<h2 class="widget-title">Categories</h2>
                                        	<ul>
                                        	    @forelse($blogCategories as $category)
                                        	        
                                        	        @php 
                                        	            $blogs = $blogCounts->where(['blog_cat_id' => $category['id'], 'is_active' => '1'])->get();
                                        	        @endphp
                                        	        @if(count($blogs) > 0)
                                                		<li>
                                                		    @if(count($blogs) == 1)
                                                			    <a class="getAjaxRecord" data-url="{{ url('blog/single/'.$blogs[0]->id.'/'.$blogs[0]->slug) }}" href="javascript:void(0)">{{ $category['title'] }}</a>
                                                			    <!--<span class="ms_cat_count">{{ count($blogs) }}</span>-->
                                                			@else
                                                			    <a class="getAjaxRecord" data-url="{{ url('blog/multiple/'.$category['id']) }}" href="javascript:void(0)">{{ $category['title'] }}</a>
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
    		                        </div>
    		                    </div>
    		                    
    				        </div>
    			        </div>
    		        </div>
            </main> 
            @php
                }
            @endphp
        </div>
    </div>

@include('layouts.front.footer')