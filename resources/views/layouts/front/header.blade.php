<body id="mainBodyContent">
    @php
        if(!empty($settings)){
            if(isset($settings['preloader']) && isset($settings['is_preloader']) && $settings['is_preloader'] == 1){
                echo '<div class="ms_loader">
                        <div class="wrap">
                            <img src="'.url('public/images/sites/'.$settings['preloader']).'" alt="">
                        </div>
                    </div>
                    <div class="ms_ajax_loader d-none">
                        <div class="wrap">
                            <img src="'.url('public/images/sites/'.$settings['preloader']).'" alt="">
                        </div>
                    </div>';
            }else if(!isset($settings['is_preloader'])){
                echo '<div class="ms_loader">
                    <div class="wrap">
                        <img src="'.url('public/assets/images/loader.gif').'" alt="">
                    </div>
                </div>
                <div class="ms_ajax_loader d-none">
                    <div class="wrap">
                        <img src="'.url('public/assets/images/loader.gif').'" alt="">
                    </div>
                </div>';
            }
        }else{
            echo '<div class="ms_loader">
                    <div class="wrap">
                    <img src="'.url('public/assets/images/loader.gif').'" alt="">
                    </div>
                </div>
                <div class="ms_ajax_loader d-none">
                    <div class="wrap">
                        <img src="'.url('public/assets/images/loader.gif').'" alt="">
                    </div>
                </div>';
        }
    @endphp
	
    
    <!----Main Wrapper Start---->
    <div class="ms_main_wrapper {{ Request::path() == 'home' || Request::path() == '/' ? 'ms_mainindex_wrapper' : '' }}">
        <!---Side Menu Start--->
        <div class="ms_sidemenu_wrapper">
            <div class="ms_nav_close ms_cmenu_toggle">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </div>
            <div class="ms_sidemenu_inner">
                <div class="ms_logo_inner">
                    <div class="ms_logo">
                        <a class="getAjaxRecord" data-type="{{ $homepage }}" data-url="{{ url('/home') }}" href="javascript:void(0)"><img src="{{ (isset($settings['large_logo']) && $settings['large_logo'] != '' ? asset('public/images/sites/'.$settings['large_logo']) : '' ) }}" alt="" class="img-fluid"/></a>
                       
                    </div>
                    <div class="ms_logo_mini">
                        <a class="getAjaxRecord" data-type="{{ $homepage }}" data-url="{{ url('/home') }}" href="javascript:void(0)"><img src="{{ (isset($settings['mini_logo']) && $settings['mini_logo'] != '' ? asset('public/images/sites/'.$settings['mini_logo']) : '' ) }}" alt="" class="img-fluid"/></a>
                    </div>
                </div>
                
                <div class="ms_nav_wrapper">
                    <h4 class="nav_heading">{{ __('frontWords.browse_music') }}</h4>    
                    <ul> 
                        <li>  
                            <a href="javascript:void(0)" class="{{ Request::path() == 'home' || Request::path() == 'home_2' ? 'active' : '' }} checkActive getAjaxRecord" data-type="{{ $homepage }}" data-url="{{ url('/home') }}" title="{{ __('frontWords.home') }}">
        						<span class="nav_icon">
        							<span class="icon icon_home"></span>
        						</span>
        						<span class="nav_text">
                                    {{ __('frontWords.home') }}
        						</span>
						    </a>
                        </li>
                        <li> 
                            <a href="javascript:void(0)" class="{{ Nav::isRoute('user.artist') || Nav::isRoute('artist.single') ? 'active' : '' }} checkActive getAjaxRecord" data-type="artist" data-url="{{ route('user.artist') }}" title="{{ __('adminWords.artist') }}">
                            <span class="nav_icon">
                                <span class="icon icon_artists"></span>
                            </span>
                            <span class="nav_text">
                                {{ __('adminWords.artist') }}
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="{{ Nav::isRoute('user.album') ? 'active' : '' }} checkActive getAjaxRecord" data-type="album" data-url="{{ route('user.album') }}" title="{{ __('adminWords.album') }}"  >
        						<span class="nav_icon">
        							<span class="icon icon_albums"></span>
        						</span>
        						<span class="nav_text">
                                    {{ __('adminWords.album') }}
        						</span>
    						</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="{{ Nav::isRoute('user.genres') || Nav::isRoute('genre.single') ? 'active' : '' }} checkActive getAjaxRecord" data-type="genre" data-url="{{ route('user.genres') }}" title="{{ __('adminWords.genre') }}">
        						<span class="nav_icon">
                                    <span class="icon genres_icon"></span>
                                </span>
        						<span class="nav_text">
                                    {{ __('adminWords.genre') }}
        						</span>
    						</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="{{ Nav::isRoute('user.audio') || Nav::isRoute('audio.single') ? 'active' : '' }} checkActive getAjaxRecord" data-type="audio" data-url="{{ route('user.audio') }}" title="{{ __('frontWords.top_track') }}">
        						<span class="nav_icon">
                                    <span class="icon icon_music"></span>
                                </span>
        						<span class="nav_text"> 
                                    {{ __('frontWords.track') }}
        						</span>
    						</a>
                        </li>
                    </ul>

                    <h4 class="nav_heading">{{ __('frontWords.your_music') }}</h4> 

                    <ul class="nav_downloads">
                        
                        <li>
                            <a href="javascript:void(0)" class="{{ Nav::isRoute('user.favourite') ? 'active' : '' }} checkActive getAjaxRecord" data-type="favourite" data-url="{{ route('user.favourite') }}" title="{{ __('frontWords.favourites') }}" >
        						<span class="nav_icon">
        							<span class="icon icon_favourite"></span>
        						</span>
        						<span class="nav_text">
        							{{ __('frontWords.favourites') }}
        						</span>
    						</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="{{ Nav::isRoute('user.history') ? 'active' : '' }} checkActive getAjaxRecord" data-type="history" data-url="{{ route('user.history') }}" title="{{ __('frontWords.history') }}">
            					<span class="nav_icon">
            						<span class="icon icon_history"></span>
            					</span>
            					<span class="nav_text">
                                    {{ __('frontWords.history') }}
            					</span>
        					</a>
                        </li>
                    </ul>
                    <ul class="nav_playlist">
                        <li>
                            <a title="{{__('frontWords.playlist') }}" class="{{ Nav::isRoute('user.playlist') ? 'active' : '' }} checkActive getAjaxRecord" data-type="playlist" data-url="{{ route('user.playlist') }}" href="javascript:void(0)">
            					<span class="nav_icon">
                                    <span class="icon playlist_icon"></span>
                                </span>
            					<span class="nav_text">
                                    {{__('frontWords.playlist') }}
            					</span>
        					</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    <div class="ms_content_wrapper">        

            <!---Header Start--->
            <div class="ms_header">
                <div class="ms_header_inner">
                    <div class="ms_top_left">
                        <div class="ms_top_search">
                            <input type="text" class="form-control" id="search_value" placeholder="{{ __('frontWords.search').' '.__('frontWords.music').' '.__('frontWords.here') }}" value="@yield('search')">

                            <span class="search_icon searchData">
                                <img src="{{ asset('public/assets/images/svg/search.svg') }}" alt="">
                            </span>
                        </div>
                        @if(isset(Auth::user()->id))
                            <div class="ms_noti_wrap">
                                @php $users = \App\User::find(Auth::user()->id); 
                                    $notification_count = \Modules\Setting\Entities\Notification::where(['notifiable_id'=> Auth::user()->id,'remove_it'=>'0'])->where('read_at',null)->orWhere('read_at', '')->count();
                                @endphp
                                <span class="noti_icon bg_cmn_iconwrap">
                                    <i class="bg_cmn_icon"></i>
                                    <span class="notification-count setNotificationCount">{{ $notification_count }}</span>
                                </span>

                                <div class="recent-notification">
                                    @if($notification_count > 0)
                                        <div class="drop-down-header"> 
                                            <h4>{{ __('frontWords.all_notifications') }}</h4> 
                                        </div>
                                        <ul class="close_options">
                                            @foreach($users->notifications as $notification)
                                                @if($notification->remove_it == 0)
                                                <li>
                                                    <a href="javascript:void(0);">
                                                        <span class="notification_icon">
                                                            @if($notification->read_at != '')
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="read_at" x="0px" y="0px" viewBox="0 0 308.728 308.728" xml:space="preserve"><path d="M153.188,27.208c-37.562,1.134-130,55.057-144.495,63.65l-7.981,32.664l40.236,28.809l-7.733-27.01l189.62-54.288    l26.895,93.949l58.098-41.331l-10.004-32.698C283.848,82.656,190.877,28.342,153.188,27.208z"></path><polygon points="308.728,281.52 308.728,195.199 308.728,160.289 308.728,136.255 306.809,137.621 252.882,175.988     222.101,197.888 226.557,202.27 231.942,207.581 237.326,212.886 243.833,219.288 307.02,281.52   "></polygon><polygon points="0,137.415 0,150.224 0,281.52 1.479,281.52 60.832,221.766 66.667,215.892 72.127,210.391 77.588,204.891     85.158,197.271 45.731,169.042 8.147,142.135 0,136.299   "></polygon><path d="M231.905,222.705l-9.692-9.545l-5.39-5.311l-5.39-5.31l-1.382-1.366l-5.489-5.4l-0.954-0.938    c-1.599-1.576-3.27-3.053-4.989-4.461c-12.777-10.457-28.655-16.158-45.399-16.158c-16.767,0-32.616,5.69-45.394,16.137    c-1.938,1.582-3.813,3.265-5.598,5.058l-0.334,0.338l-5.363,5.399l-3.452,3.48l-5.458,5.495l-5.46,5.495l-17.921,18.046    l-47.276,47.593h274.396L231.905,222.705z"></path></svg> 
                                                            @else
                                                                <svg id="unread_msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 -92 512 512"><path d="m512 307.996094v-285.585938l-170.816406 141.289063zm0 0"/><path d="m265.714844 226.125c-2.820313 2.328125-6.265625 3.496094-9.714844 3.496094s-6.894531-1.167969-9.714844-3.496094l-51.605468-42.683594-170.128907 143.714844h462.898438l-170.132813-143.714844zm0 0"/><path d="m491.273438 0h-470.546876l235.273438 194.601562zm0 0"/><path d="m0 22.410156v285.585938l170.816406-144.296875zm0 0"/></svg>
                                                            @endif                                                           
                                                        </span>
                                                        <div class="notification_info">
                                                            <!-- <h5>Storage Full</h5> -->
                                                            <p>@php echo htmlspecialchars_decode($notification->data['data']) @endphp</p>
                                                            <small><i class="fa fa-clock-o" aria-hidden="true"></i>{{ Illuminate\Support\Carbon::parse($notification->created_at)->diffForHumans(Illuminate\Support\Carbon::now()) }}</small>
                                                        </div>
                                                        <div class="notification-status">
                                                            <span class="notification-option-btn">
                                                                <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.104 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.104 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"></path></svg>
                                                            </span>
                                                            <div class="notification-options">
                                                                <ul>
                                                                    @if($notification->read_at == '')
                                                                        <li class="markAsReadNotification" data-id="{{ $notification->id }}">Mark as Read</li>
                                                                    @endif
                                                                    <li class="removeNotiFromView" data-id="{{ $notification->id }}">Remove it</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        @if($notification->remove_it == 0)
                                            <div class="drop-down-footer">
                                                <a href="javascript:void(0);" class="ms_btn ms_sm_btn clearAllNotification">
                                                    Clear All
                                                </a>
                                            </div> 
                                        @endif
                                    @else
                                        <!-- You have no new notifications -->
                                        <div class="drop-down-header no_new_notification">
                                            <p>{{ __('frontWords.no_notification') }}</p>
                                        </div>
                                    @endif                                    
                                </div>                            
                            </div>
                        @endif  
                        
                        @if(isset($settings['is_header_msg']) && $settings['is_header_msg'] == 1)
                        <div class="ms_top_trend">
                            <span><a href="#"  class="ms_color">{{ isset($settings['header_title']) ? $settings['header_title'].' : ' : '' }} </a></span> <span class="top_marquee"><a href="#">{{ isset($settings['header_description']) ? $settings['header_description'] : '' }}</a></span>
                        </div>
                        @endif
                    </div>
                    <div class="ms_top_right">

                        @if(isset(Auth::user()->id))
                            <div class="ms_top_lang">
                                <span data-toggle="modal" data-target="#lang_modal">{{ __('frontWords.music').' '.__('frontWords.languages') }}<img src="{{ asset('public/assets/images/svg/lang.svg') }}" alt=""></span> 
                            </div>                
                        @endif
                        
                        <div class="ms_pro_inner">     
                            @if(isset(Auth::user()->id))
                                @php $users = \App\User::find(Auth::user()->id); @endphp                            
                                <div class="ms_top_btn userloggedIn">

                                    <div class="ms_pro_img"> 
                                        @if(!empty(Auth::user()->image))
                                            <img src="{{ asset('public/images/user/'.Auth::user()->image) }}" alt="Profile">
                                        @else
                                            <img src="{{ Avatar::create($users->name)->toBase64() }}" />
                                        @endif
                                        @if(Auth::user()->role == 2 && Auth::user()->artist_verify_status == 'A')
                                            <img class="front_img_artist_verified" src="{{ asset('public/assets/images/veryfied_user.svg') }}" alt="verified">
                                        @endif
                                    </div>
                                    <div class="ms_pro_namewrap">
                                        <span class="pro_name"></span> <i class="fa fa-caret-down"></i>
                                    </div>

                                    <ul class="ms_common_dropdown ms_profile_dropdown pro_dropdown_menu">
                                        @php
                                            if(Auth::user()->role == 1){
                                                echo '<li><a href="'.url('admin').'" target="_blank"><span class="common_drop_icon admin_icon"></span>'.__('frontWords.admin').'</a></li>';
                                            }elseif(Auth::user()->role == 2){
                                                echo '<li><a href="'.route('artist.home').'" target="_blank"><span class="common_drop_icon admin_icon"></span>'.__('frontWords.artist').'</a></li>';
                                            }
                                        @endphp
                                        <li><a class="getAjaxRecord" data-url="{{ url('user/profile') }}" href="javascript:void(0)">
                                            <span class="common_drop_icon drop_pro"></span>
                                            {{ __('frontWords.my').' '.__('frontWords.profile') }}</a>
                                        </li>
                                        <li><a class="getAjaxRecord" data-url="{{ url('pricing-plan') }}" href="javascript:void(0)"><span class="common_drop_icon drop_pro pricing_icon"></span>
                                            {{ __('frontWords.pricing_plan') }}</a>
                                        </li>
                                        <li><a class="getAjaxRecord" data-url="{{ url('user/purchase/history') }}" href="javascript:void(0)"><span class="common_drop_icon drop_pro history_icon"></span>
                                            {{ __('frontWords.audio_purchase_history') }}</a>
                                        </li>
                                        <li><a class="getAjaxRecord" data-url="{{ url('user/download/history') }}" href="javascript:void(0)"><span class="common_drop_icon drop_pro download_icon"></span>
                                            {{ __('frontWords.download_history') }}</a>
                                        </li>
                                        <li><a class="getAjaxRecord" data-type="playlist" data-url="{{ route('user.playlist') }}" href="javascript:void(0)"><span class="common_drop_icon drop_pro playlist_icon"></span>
                                            {{ __('frontWords.my').' '.__('frontWords.playlist') }}</a>
                                        </li>
                                        <li class="{{ Nav::isRoute('blog.single') ? 'active' : '' }}"><a class="getAjaxRecord" data-url="{{ url('blogs') }}" href="javascript:void(0)"><span class="common_drop_icon drop_pro blog_icon"></span> 
                                            {{ __('adminWords.blogs') }}</a>
                                        </li>
                                        <li><a href="{{route('user.logout') }}"><span class="common_drop_icon drop_logt"></span>
                                            {{ __('frontWords.logout') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="ms_top_btn hideGuestUser">
                                    <a href="javascript:void(0);" class="ms_btn reg_btn" data-toggle="modal" data-target="#registerModal"><span>{{ __('frontWords.register') }}</span></a>
                                    <a href="javascript:void(0);" class="ms_btn login_btn" data-toggle="modal" data-target="#loginModal"><span>{{ __('frontWords.login') }}</span></a>
                                </div>
                            @endif           
                      
                        </div>
                        <div class="ms_cmenu_toggle ms_menu_toggle">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
            <!---Header End--->
    
    @section('script')
        @php
            $getAd = $google_ad->limit(1)->get();
            if(!empty($userPlan) && $userPlan->show_advertisement == 1){
                echo '<div class="google_ad text-center p-5 m-5">'.
                        (sizeof($getAd) > 0 && $getAd[0]->status == 1 ? html_entity_decode($getAd[0]->google_ad_script) : '').'
                    </div>';
            }
        @endphp
    @endsection