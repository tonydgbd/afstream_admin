@extends('layouts.front.main')
@section('title', __('frontWords.playlist'))
@section('content')
@php 
if(!isset(Auth::user()->id)){ @endphp
    <div class="ms_artist_wrapper common_pages_space">
        <article id="post-31">
            <div class="ms_entry_content">   
                <div class="fw-page-builder-content">
                    <section class="fw-main-row ">
                            <div class="fw-container-fluid">
                                <div class="fw-row">
                                    <div class="fw-col-xs-12">
                                        <div class="ms_needlogin">
                                            <div class="needlogin_img">
                                                <img src="{{ asset('assets/images/svg/headphones.svg') }}" alt="">
                                            </div>
                                            <h2>{{ __('frontWords.need_to_login') }}</h2>
                                            <a href="javascript:void(0);" class="ms_btn reg_btn" data-toggle="modal" data-target="#loginModal">
                                                <span>{{ __('frontWords.register_login') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="fw-row">
                                    <div class="fw-col-xs-12">
                                    <div class="fw-divider-space padder_top80"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
        </article>
    </div>
@php } else{  @endphp
    
    <div class="ms_top_artist ms_artist_wrapper common_pages_space"> 
        <div class="container-fluid">
            
                <div class="ms_artist_slider play-list-slider">
                    <div class="slider_heading_wrap">
                        <div class="slider_cheading">
                            <h4 class="cheading_title">{{ __('frontWords.your_playlist') }} &nbsp;</h4>
                        </div>
                        <!-- Add Arrows -->
                        <div class="slider_cmn_controls">
                            <div class="slider_cmn_nav "><span class="swiper-button-next1 slider_nav_next"></span></div>
                            <div class="slider_cmn_nav"><span class="swiper-button-prev1 slider_nav_prev"></span></div>
                        </div>
                    </div>
                    <div class="swiper-container ">
        				<div class="swiper-wrapper">
        					
                    
                    @php 
                        if(sizeof($playlist) > 0){
                                
                            foreach($playlist as $list){ 
                                $img = asset('assets/images/playlist.jpg');
                                if(!empty($list->song_list) && $list->song_list != ''){
                                    $songs = json_decode($list->song_list);
                                    if(!empty($songs)){
                                        $img = audioDetail(['songid'=>$songs[0], 'image' => 1]);
                                    }
                                }
                    @endphp
                    <div class="swiper-slide">
                        <div class="ms_rcnt_box marger_bottom25 playlist_boxes">
                            <div class="ms_rcnt_box_img">
                                <a class="getAjaxRecord" data-type="playlist" data-url="{{ url('playlist/single/'.$list->id) }}" href="javascript:void(0)">
                                    <img src="{{ $img == '[]' ? asset('assets/images/playlist.jpg') : $img  }}" alt="" class="img-fluid">
                                    <div class="album_more_optn list_more">
                                        <ul>
                                            <li class="list_more">
                                                <a href="javascript:void(0);" class="songslist_moreicon">
                                                    <span >
                                                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.104 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.104 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg>
                                                    </span>
                                                </a>
                                                <ul class="ms_common_dropdown ms_downlod_list list_more">     
                                                    
                                                    <li class="favourite_icon">
                                                        <a href="javascript:void(0);" class="addToFavourite" data-favourite="{{ $list->id }}" data-type="playlist">
                                                            @php
                                                                $getData = \App\Favourite::where(['user_id'=> auth()->user()->id])->first();
                                                                
                                                                    if(!empty($getData)){
                                                                        $decodeIds = $getData->playlist_id;  
                                                                        if($decodeIds != '' && !empty($decodeIds)){
                                                                            $dataId = json_decode($decodeIds);
                                                                            if( in_array($list->id, $dataId) ) {                                                    
                                                            @endphp                    
                                                                                <svg width="19px" height="19px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 391.8 391.8"><defs><style>.cls-1{fill:#d80027;}</style></defs><title>Remove From {{ __('frontWords.favourites') }}</title><path class="cls-1" d="M280.6,43.8A101.66,101.66,0,0,1,381.7,144.9c0,102-185.8,203.1-185.8,203.1S10.2,245.5,10.2,144.9A101.08,101.08,0,0,1,111.3,43.8h0A99.84,99.84,0,0,1,196,89.4,101.12,101.12,0,0,1,280.6,43.8Z"></path></svg>
                                                            @php            }else{    
                                                            @endphp
                                                                                <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>
                                                            @php            }
                                                                        }                
                                                                    }
                                                            @endphp
                                                        </a>    
                                                        {{ __('frontWords.favourites') }}
                                                    </li>
                                                    
                        
                                                    <li>
                                                        <a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $list->id }}" data-musictype="playlist">
                                                        <span class="opt_icon" title="Add To Queue"><span class="icon icon_queue"></span></span>
                                                        {{ __("frontWords.add_to_queue") }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('playlist/single/'.$list->id.'/'.$playlist[0]->playlist_slug) }}" data-sharename="{{ $playlist[0]->playlist_name }}">
                                                            <span class="common_drop_icon drop_share"></span>{{ __('frontWords.share') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="ms_remove_user_playlist" data-list-id="{{ $list->id }}"><span class="opt_icon delete_icon">
                                                            <span class="icon icon_playlst"></span>
                                                            </span>{{ __('frontWords.remove_playlist') }}
                                                        </a>
                                                    </li>
                                                </ul>                                   
                                            </li>
                                        
                                        </ul>
                                    </div>
                                </a>    
                            </div>
                            <div class="ms_rcnt_box_text">
                                <h3><a class="getAjaxRecord" data-type="playlist" data-url="{{ url('playlist/single/'.$list->id) }}" href="javascript:void(0)">
                                    {{ $list->playlist_name }}
                                </a></h3>
                                @php 
                                    $songs = 0;
                                    $videos = 0;
                                    $totalTrack = 0;
                                    if(!empty($list->song_list)){
                                        $songs = count(json_decode($list->song_list));    
                                    }                                    
                                    if(!empty($list->video_list)){
                                        $videos = count(json_decode($list->video_list));
                                    }
                                    $totalTrack = $songs+$videos;
                                @endphp
                                @if($is_youtube == 1)
                                    <p>{{ $totalTrack }} {{ __('frontWords.track') }}</p> 
                                @else
                                    <p>{{ !empty($list->song_list) ? count(json_decode($list->song_list)) : 0 }} {{ __('frontWords.track') }}</p>
                                @endif    
                            </div>
                        </div>
                    </div>
                    
                    
                @php    }
                    }
                @endphp
                </div>
                </div>
                <div class="row">
                    <div class="col-xxl-2 col-xl-4 col-lg-6 col-md-6">
                        <div class="ms_rcnt_box marger_bottom25">
                            <div class="create_playlist">
                                <img src="{{ asset('images/add-to-playlist.png') }}" alt="add-to-playlist" class="img-fluid">
                            </div>
                            <div class="ms_rcnt_box_text">
                                <h3><a href="javascript:void();">{{ __('frontWords.create_playlist') }}</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        @include('layouts.front.footer')
    </div>
@php } @endphp
            
@endsection