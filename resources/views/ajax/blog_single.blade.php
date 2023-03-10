@if(!empty($blog[0]->keywords))
    @section('meta_keywords', $blog[0]->keywords)
@endif  
@inject('users', 'App\User')
@inject('reply', 'App\Reply')
@inject('blogCounts', 'Modules\General\Entities\Blogs')


    <div class="musiooFrontSinglePage">
        <div class="ms_artist_wrapper common_pages_space">
            @php                
                $getUser = $users->find($blog[0]->user_id);
            @endphp
                <main id="main" class="site-main">
                <div class="ms_main_wrapper ms_main_wrapper_single">
        		       <div class="container">
                           	<div class="row">
        				        <div class="col-xl-8 col-lg-12 col-md-12">
                                    <div class="ms_main_data"> 
                                        <article class="hentry">
                                            <div class="ms_single_page_image">
                                                <span class="ms_blog_date">{{ date('F d, Y', strtotime($blog[0]->created_at)) }}</span>
                                                    <img src="{{ asset('images/blogs/'.$blog[0]->image) }}" alt="{{ $blog[0]->title }}">
                                            </div>
                                            <header class="entry-header">
                                                <div class="entry-meta">
                                                    <span class="posted-on">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                        <a href="javascript:;" rel="bookmark">
                                                            <time class="entry-date published" datetime="{{ $blog[0]->created_at }}">{{ date('F d, Y', strtotime($blog[0]->created_at)) }}</time>
                                                        </a>
                                                    </span>
                                                    <span class="byline">
                                                        <span class="ms-separator"></span>
                                                        <i class="fa fa-user" aria-hidden="true"></i> 
                                                        <span class="author vcard">
                                                            <a class="url fn n" href="{{url('/')}}">{{ (!empty($getUser) ? $getUser->name : __('adminWords.musioo')) }}</a>
                                                        </span>
                                                    </span>
                                                </div>
                                            </header>
                                            <h4 class="blog-title">{{ $blog[0]->title }}</h4>
                                            <div class="entry-content">
                                                <p>{{ strip_tags(htmlspecialchars_decode($blog[0]->detail)) }}</p>
                                            </div>
                                            <footer class="entry-footer ms-entry-footer"></footer>
                                        </article>
                                        <div id="comments" class="comments-area">
                                            <div class="ms_heading">
                                                <h1 class="comments-title">{{ __('frontWords.comment') }} ({{ sizeof($comments) }})</h1>
                                            </div>
                                            <ol class="comment-list">
                                        @php
                                        if(sizeof($comments) > 0){
                                            foreach($comments as $comment){
                                                $userInfo = $users->find($comment->user_id);   
                                                $getReply = $reply->where(['comment_id' => $comment->id, 'blog_id' => $blog[0]->id])->get();
                                        @endphp
                                                <li class="comment">
                                                    <div class="comment-body ms_comment_section">
                                                        <div class="comment-author comment_img">
                                                            <img alt="" src="{{ !empty($userInfo) && $userInfo->image != '' ? asset('images/user/'.$usersInfo->image) : asset('assets/images/users/profile.jpg') }}" class="avatar avatar-80 photo" height="80" width="80">
                                                        </div>
                                                        <div class="comment_info">
                                                            <div class="comment_head">
                                                                <h3><cite class="fn">{{ !empty($userInfo) ? $userInfo->name : '' }}</cite> <span class="says">says</span></h3>
                                                                <p><a href="javascript:void(0);">{{ !empty($userInfo) ? date('F d, Y', strtotime($userInfo->created_at)).' At '.date('h:i a', strtotime($userInfo->created_at)) : '' }}</a></p>
                                                            </div>
                                                            <div class="ms_test_para">
                                                                <p>{{ $comment->message }}</p>
                                                            </div>
                                                            
    
                                                        </div>
                                                    </div>
                                                    @php
                                                        if(sizeof($getReply) > 0){
                                                            $usersInfo = $users->find($getReply[0]->user_id);   
                                                    @endphp
                                                    <ol class="children adminComments">
                                                        <li class="comment">
                                                            <div class="comment-body ms_comment_section">
                                                                <div class="comment-author comment_img">
                                                                    <img alt="" src="{{ !empty($usersInfo) && $usersInfo->image != '' ? asset('images/user/'.$usersInfo->image) : asset('assets/images/users/profile.jpg') }}" class="avatar avatar-80 photo" height="80" width="80">
                                                                </div>
                                                                <div class="comment-meta commentmetadata comment_info">
                                                                    <div class="comment_head">
                                                                        <h3><cite class="fn">{{ !empty($usersInfo) ? $usersInfo->name : '' }}</cite> <span class="says">says:</span></h3>
                                                                        <p><a href="javascript:void(0);">{{ !empty($usersInfo) ? date('F d, Y', strtotime($usersInfo->created_at)).' At '.date('h:i a', strtotime($usersInfo->created_at)) : '' }}
                                                                        </a></p>
                                                                    </div>
    
                                                                    <p>{{ $getReply[0]->reply }}</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        
                                                    </ol>
                                                    
                                                    @php } @endphp
                                                </li>
                                                
                                        @php
                                            }
                                        }
                                        @endphp
                                    </ol>
                                            <div class="ms_cmnt_wrapper">
                                                <div class="ms_heading">
                                                    <h1>{{ __('frontWords.leave_comment') }}</h1>
                                                </div>
                                                <div class="ms_cmnt_form">
                                                    <form action="{{ url('user/comment/blog/'.$blog[0]->id) }}" data-reset="1" method="post" class="comment-form">
                                                        <div class="ms_input_group1">
                                                            <div class="ms_input">
                                                                <textarea id="comment" name="message" class="form-control require" placeholder="{{ __('frontWords.enter_comment') }}" cols="45" rows="8" aria-required="true" spellcheck="false"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="ms_input_group2">
                                                            <div class="ms_input">
                                                                <button type="button" class="ms_btn" data-action="submitThisForm">{{ __('frontWords.post_comment') }}</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                        	            $keywords = [];
                                        	            $keywords = explode(",",$blog[0]->keywords);
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
                                        
                                        <section class="widget widget_tag_cloud">
                                        	<h2 class="widget-title">Tag Cloud</h2>
                                        	<div class="tagcloud">
                                        	    @if(!empty($keywords[0]))
                                        	        @foreach($keywords as $key)
                                        		        <a href="javascript:void(0)" class="tag-cloud-link">{{ $key }}</a>
                                        	        @endforeach
                                        		@else
                                        		    <div class="ms_empty_data">
                                                        <p>No keywords available.</p>
                                                    </div>
                                        	    @endif
                                        	</div>
                                        </section>
    		                        </div>
    		                    </div>
    				        </div>
    			        </div>
    		        </div>
            </main>             
        </div>
    </div>
    
@include('layouts.front.footer')