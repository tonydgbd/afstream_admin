@extends('layouts.front.main')
@section('title', __('frontWords.home'))
@section('style')
    <link href="{{ asset('public/assets/css/star-rating.css') }}" rel="stylesheet" type="text/css"> 
@endsection

@section('content')
    
            
    <!---index page--->
    <div class="ms_index_wrapper common_pages_space">
        <div class="ms_index_inner ms_home_version_two">
            <div class="row">
                <div class="col-xl-10 col-lg-8"> 
                    
                    <div class="ms_home2_wrap">


                    @if(!empty($is_youtube) && $is_youtube == 1 && !empty($ytPlaylists['results']))   

                        <!-- YT Top Tracks Start -->
                        @if(isset($popularYtVideos) && sizeof($popularYtVideos) > 0)
                            <!-- Youtube Playlists -->
                            <div class="ms_artist_slider also_like_slider2">
                                <div class="slider_heading_wrap">
                                    <div class="slider_cheading">
                                        <h4 class="cheading_title">{{ __("adminWords.youtube").' '.__("frontWords.top_track") }} &nbsp;</h4> 
                                    </div>
                                    <!-- Add Arrows --> 
                                    <div class="slider_cmn_controls">
                                        <div class="slider_cmn_nav"><span class="swiper-button-next2 slider_nav_next"></span></div>
                                        <div class="slider_cmn_nav"><span class="swiper-button-prev2 slider_nav_prev"></span></div> 
                                    </div>
                                </div>
                                <div class="ms_artist_innerslider">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            @php 
                                                $artists = ''; 
                                                $ytImage = '';   
                                                $videoId = '';

                                                foreach($popularYtVideos as $ytVideo){                                                

                                                    if(isset($ytVideo->snippet->thumbnails->medium->url) && !empty($ytVideo->snippet->thumbnails->medium->url)){
                                                        $ytImage = $ytVideo->snippet->thumbnails->medium->url;
                                                    }elseif(isset($ytVideo->snippet->thumbnails->high->url) && $ytVideo->snippet->thumbnails->high->url){
                                                        $ytImage = $ytVideo->snippet->thumbnails->high->url;
                                                    }elseif(isset($ytVideo->snippet->thumbnails->default->url) && !empty($ytVideo->snippet->thumbnails->default->url)){
                                                        $ytImage = $ytVideo->snippet->thumbnails->default->url;
                                                    }else{ $ytImage = 'public/images/yt_music.webp'; }

                                                    if(isset($ytVideo->id) && !empty($ytVideo->id)){
                                                        $videoId = $ytVideo->id;
                                                    }
                                                    
                                                    if($ytVideo->snippet->thumbnails != '' ){
                                                        $img = '<img src="'.asset($ytImage).'" alt="">';
                                                    }else{
                                                        $img = '<img src="'.dummyImage('audio').'" alt="" class="img-fluid">';
                                                    }
                                            @endphp
                                                    <div class="swiper-slide play_btn">
                                                        <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                            <div class="slider_cimgbox slider_artist_imgbox play_box_img">
                                                                @if($ytVideo->snippet->thumbnails != '' )
                                                                    <img src="{{ asset($ytImage) }}" alt="" class="img-fluid">
                                                                @else
                                                                    <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid"> 
                                                                @endif  
                                                            </div>                                                            

                                                            <div class="ms_play_icon play_btn yt_music" data-musicid="{{ $videoId }}" data-title="{{ $ytVideo->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">
                                                                <img src="{{ asset('public/assets/images/svg/play.svg') }}" alt="">
                                                            </div>

                                                            <div class="slider_ctext slider_artist_text">
                                                                <a class="slider_ctitle slider_artist_ttl limited_text_line yt_music" data-musicid="{{ $videoId }}" data-title="{{ $ytVideo->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}" href="javascript:void(0)">{{ $ytVideo->snippet->title }}</a>          
                                                            </div>
                                                        </div>
                                                        </div>';
                                            @php  } @endphp                                      
                                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>                            
                        @endif
                        <!-- YT Top Tracks End -->


                        <!-- YT Playlists Start -->
                        @php  
                            $y = 2;                         
                            $t = count($ytPlaylists['results']);
                        @endphp 

                        @foreach($ytPlaylists['results'] as $ytplaylists)

                            @php  $y++; @endphp                       

                            <div class="ms_artist_slider also_like_slider{{$y}}">
                                <div class="slider_heading_wrap">
                                    <div class="slider_cheading">
                                        <h4 class="cheading_title limited_text_line">{{ $ytplaylists->snippet->title }} &nbsp;</h4>
                                    </div>
                                    <!-- Add Arrows -->
                                    <div class="slider_cmn_controls">
                                        <div class="slider_cmn_nav"><span class="swiper-button-next{{$y}} slider_nav_next"></span></div>
                                        <div class="slider_cmn_nav"><span class="swiper-button-prev{{$y}} slider_nav_prev"></span></div>
                                    </div>
                                </div>
                                                
                                @php
                                    $playlists = getYtPlaylistDetailById($ytplaylists->id);                                
                                @endphp


                                    <div class="ms_artist_innerslider">
                                        <div class="swiper-container">
                                            <div class="swiper-wrapper">                                                
                                            @foreach($playlists['results'] as $ytplaylist)        
                                                @php                            
                                                    $ytImage = '';    
                                                    $videoId = '';                                                  
                                                        
                                                    if(isset($ytplaylist->snippet->thumbnails->medium->url) && !empty($ytplaylist->snippet->thumbnails->medium->url)){
                                                        $ytImage = $ytplaylist->snippet->thumbnails->medium->url;
                                                    }elseif(isset($ytplaylist->snippet->thumbnails->high->url) && $ytplaylist->snippet->thumbnails->high->url){
                                                        $ytImage = $ytplaylist->snippet->thumbnails->high->url;
                                                    }elseif(isset($ytplaylist->snippet->thumbnails->default->url) && !empty($ytplaylist->snippet->thumbnails->default->url)){
                                                        $ytImage = $ytplaylist->snippet->thumbnails->default->url;
                                                    }else{ $ytImage = 'public/images/yt_music.webp'; }

                                                    if(isset($ytplaylist->snippet->resourceId->videoId) && !empty($ytplaylist->snippet->resourceId->videoId)){
                                                        $videoId = $ytplaylist->snippet->resourceId->videoId;
                                                    }   
                                                @endphp
                                                @if(!empty($videoId)) 
                                                    <div class="swiper-slide play_btn">
                                                        <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                            <div class="slider_cimgbox slider_artist_imgbox play_box_img">
                                                                @if($ytplaylist->snippet->thumbnails != '' )
                                                                    <img src="{{ asset($ytImage) }}" alt="" class="img-fluid">
                                                                @else
                                                                    <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                                                                @endif      
                                                            </div>

                                                            <div class="ms_play_icon play_btn yt_music" data-musicid="{{ $videoId }}" data-title="{{ $ytplaylist->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">
                                                                <img src="{{ asset('public/images/svg/play.svg') }}" alt="play icone">
                                                            </div>
                                                            <div class="slider_ctext slider_artist_text">
                                                                <a href="javascript:void(0)" class="yt_music limited_text_line" data-musicid="{{ $videoId }}" data-title="{{ $ytplaylist->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">{{ $ytplaylist->snippet->title }}</a>           
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif                                   
                                            @endforeach
                                                               
                                            </div>
                                        </div>
                                    </div>
                                        @section('script')
                                        <script>
                                            var t = parseInt(`{{$t}}`)+3;
                                            for (let y = 3; y < t; y++) {
                                                var swiper = new Swiper('.also_like_slider'+y+' .swiper-container', {
                                                    slidesPerView: 4,
                                                    spaceBetween: 30,
                                                    loop: true,
                                                    speed: 1500,
                                                    navigation: {
                                                        nextEl: '.swiper-button-next'+y,
                                                        prevEl: '.swiper-button-prev'+y,
                                                    },
                                                    breakpoints: {
                                                        1800: {
                                                            slidesPerView: 3,
                                                        },
                                                        1600: {
                                                            slidesPerView: 3,
                                                            spaceBetween: 20,
                                                        },
                                                        1500: {
                                                            slidesPerView: 2,
                                                        },
                                                        1399: {
                                                            slidesPerView: 4,
                                                            spaceBetween: 10,
                                                        },
                                                        1024: {
                                                            slidesPerView: 3,
                                                            spaceBetween: 10,
                                                        },
                                                        992: {
                                                            slidesPerView: 4,
                                                            spaceBetween: 10,
                                                        },
                                                        800: {
                                                            slidesPerView: 3,
                                                            spaceBetween: 10,
                                                        },
                                                        700: {
                                                            slidesPerView: 2,
                                                            spaceBetween: 15,
                                                        },
                                                        480: {
                                                            slidesPerView: 1,
                                                        }
                                                    },
                                                });
                                            }
                                        </script>
                                        @endsection
                            </div>
                        @endforeach    
                        <!-- YT Playlists End -->
                        
                    @endif 

                        <!-- Top Artist Start -->
                        <div class="ms_popular_artist_wrapper ms_artist_slider recommended_artist_slider mb-0">
                            <div class="slider_heading_wrap">
                                <div class="slider_cheading">
                                    <h4 class="cheading_title">{{ __("frontWords.top_artist") }} &nbsp;</h4>
                                </div> 
                                <div class="slider_cmn_controls">
                                    <a href="javascript:void(0);" class="ms_btn_link getAjaxRecord" data-type="artist" data-url="{{ route('user.artist') }}" title="{{ __('adminWords.top_artist') }}">{{ __("frontWords.view_more") }}</a> 
                                </div> 
                            </div>
                            <div class="row">
                                
                                        @php 
                                        if(sizeof($top_artist) > 0){
                                        
                                            $artists_id = json_decode($top_artist[0]->top_artist);
                                            if(!empty($artists_id)){
                                                foreach($artists_id as $artist_id){
                                                    $getArtist = select(['column' => '*', 'table'=>'artists', 'where'=>['id'=>$artist_id] ]); 
                                                    if(!empty($getArtist)){
                                                        foreach($getArtist as $artist){
                                        @endphp 
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6 play_btn play_icon_btn">
                                                            <div class="slider_cbox">
                                                                <div class="slider_cimgbox">
                                                                    @if($artist->image != '' && file_exists(public_path('images/artist/'.$artist->image)))
                                                                        <img src="{{ asset('public/images/artist/'.$artist->image) }}" alt="" class="img-fluid">
                                                                    @else
                                                                        <img src="{{ dummyImage('artist') }}" alt="" class="img-fluid">
                                                                    @endif   
                                                                    <div class="ms_play_icon play_btn play_music" data-musicid="{{ $artist->id }}" 
                                                                        data-musictype="artist" data-url="{{ url('/songs') }} ">
                                                                        <img src="{{ asset('public/images/svg/play.svg') }}" alt="play icone">
                                                                    </div>
                                                                </div>
                                                                <div class="slider_ctext">
                                                                    <a class="slider_ctitle slider_artist_ttl limited_text_line getAjaxRecord" href="javascript:void(0)" data-type="artist" data-url="{{ url('artist/single/'.$artist->id.'/'.$artist->artist_slug) }}">{{ $artist->artist_name }}</a>
                                                                </div>
                                                            </div>
                                                        </div>     
                                            
                                        @php
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        @php
                                            }else{
                                                echo '<div class="col-lg-12">
                                                        <div class="ms_empty_data">
                                                            <p>'.__("frontWords.no_artist").'</p>
                                                        </div>
                                                    </div>';
                                            }
                                        @endphp                    
                                </div>
                               
                        </div>
                        <!-- Artist End  -->
                        

                        <!-- Today Top Tracks Start -->
                        <div class="ms_popular_tracks_wrapper comman-sec-spacer pt-0">
                            <div class="slider_heading_wrap">
                                <div class="slider_cheading">
                                    <h4 class="cheading_title">{{ __("frontWords.todays_top") }} &nbsp;</h4> 
                                </div>
                                <div class="slider_cmn_controls ">
                                    <a href="javascript:void(0)" class="ms_btn_link getAjaxRecord" data-type="audio" data-url="{{ route('user.audio') }}" title="{{ __('frontWords.todays_top') }}">{{ __("frontWords.view_more") }}</a> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="ms_songslist_box">
                                            <ul class="ms_songlist ms_index_songlist mb-5">

                                            @if(sizeof($today_top) > 0)                                                                             
                                                @php $i= 1; @endphp

                                                @if(!empty($today_top))                                                
                                                    @foreach($today_top as $audio)
                                                            @php
                                                                $getArtist = json_decode($audio->artist_id);
                                                                $artist_name = '';
                                                            @endphp

                                                            @foreach($getArtist as $artistid)
                                                                @php
                                                                    $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistid] ]);
                                                                @endphp
                                                                @if(count($artists) > 0)
                                                                    @php $artist_name .= $artists[0]->artist_name.','; @endphp
                                                                @endif
                                                            @endforeach
                                                            @php
                                                                $getLikeDislikeAudio = getFavDataId(['column' => 'audio_id', 'audio_id' => $audio->id]);
                                                                $download = '';
                                                            @endphp     

                                                            <li>
                                                                <div class="ms_songslist_inner">
                                                                    <div class="ms_songslist_left play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                                                        <div class="songslist_number">
                                                                            <h4 class="songslist_sn">{{ $i++ }}</h4>
                                                                            <span class="songslist_play"><img src="{{ asset('public/images/svg/play_songlist.svg') }}" alt="Play" class="img-fluid"/></span>
                                                                        </div> 
                                                                        <div class="songslist_details">
                                                                            <div class="songslist_thumb play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                                                                @if($audio->image != '' && file_exists(public_path('images/audio/thumb/'.$audio->image)))
                                                                                    <img src="{{ asset('public/images/audio/thumb/'.$audio->image) }}" alt="">
                                                                                @else
                                                                                    <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                                                                                @endif
                                                                            </div>
                                                                            <div class="songslist_name play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">            
                                                                                <h3 class="song_name play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}"><a href="javascript:void(0);">{{ $audio->audio_title }}</a></h3>
                                                                                <p class="song_artist">{{ $artist_name }} </p>
                                                                            </div> 
                                                                        </div> 
                                                                    </div>
                                                                    <div class="ms_songslist_right">
                                                                        <span class="ms_songslist_like addToFavourite" data-favourite="{{ $audio->id }}" data-type="audio">
                                                                            @if($getLikeDislikeAudio == 1)
                                                                                <svg width="19px" height="19px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 391.8 391.8"><defs><style>.cls-1{fill:#d80027;}</style></defs>
                                                                                <title>{{ 'Remove From'.__('frontWords.favourites') }}</title>
                                                                                <path class="cls-1" d="M280.6,43.8A101.66,101.66,0,0,1,381.7,144.9c0,102-185.8,203.1-185.8,203.1S10.2,245.5,10.2,144.9A101.08,101.08,0,0,1,111.3,43.8h0A99.84,99.84,0,0,1,196,89.4,101.12,101.12,0,0,1,280.6,43.8Z"></path></svg>
                                                                            @else
                                                                                <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>
                                                                            @endif
                                                                        </span>
                                                                        <span class="ms_songslist_time">{{ $audio->audio_duration }}</span>
                                                                        <div class="ms_songslist_more">
                                                                            <span class="songslist_moreicon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.105 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.105 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg></span>
                                                                            <ul class="ms_common_dropdown ms_songslist_dropdown">
                                                                                <li><a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $audio->id }}" data-musictype="audio"><span class="opt_icon"><span class="icon icon_queue"></span></span>{{ __("frontWords.add_to_queue") }}</a></li>
                                                                                
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="ms_add_playlist" data-musicid="{{ $audio->id }}">
                                                                                        <span class="common_drop_icon drop_playlist"></span>
                                                                                        {{ __("frontWords.add_to_playlist") }}
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('audio/single/'.$audio->id.'/'.$audio->audio_slug) }}" data-sharename="{{ $audio->audio_title }}">
                                                                                        <span class="common_drop_icon drop_share"></span>
                                                                                        {{ __("frontWords.share") }}
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    @if(isset($audio->download_price) && !empty($audio->download_price))
                                                                                        <input type="hidden" class="getAudioAmountToDownload" value="{{ $audio->download_price }}">

                                                                                        @if(Auth::check())                
                                                                                            @php 
                                                                                                $buyedAudios = json_decode(auth()->user()->audio_download_list); 
                                                                                            @endphp
                                                                                            @if(!empty($buyedAudios))
                                                                                                @if(in_array($audio->id, $buyedAudios))
                                                                                                    <a href="javascript:void(0);" class="artistDownloadTrack download_artist_track" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                        <span class="opt_icon common_drop_icon drop_downld"> 
                                                                                                            <span class="icon icon_download"></span>
                                                                                                        </span>
                                                                                                        {{ __("frontWords.download") }}
                                                                                                    </a>
                                                                                                @else
                                                                                                    <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                        <span class="opt_icon common_drop_icon drop_downld">
                                                                                                            <span class="icon icon_download"></span>
                                                                                                        </span>
                                                                                                        {{ __("frontWords.buy_to_download") }}
                                                                                                    </a>
                                                                                                @endif
                                                                                            @else
                                                                                                <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                    <span class="opt_icon common_drop_icon drop_downld">
                                                                                                        <span class="icon icon_download"></span>
                                                                                                    </span>
                                                                                                    {{ __("frontWords.buy_to_download") }}
                                                                                                </a>
                                                                                            @endif
                                                                                                
                                                                                        @else
                                                                                            <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                <span class="opt_icon common_drop_icon drop_downld">
                                                                                                    <span class="icon icon_download"></span>
                                                                                                </span>
                                                                                                {{ __("frontWords.buy_to_download") }}
                                                                                            </a>                                                           
                                                                                        @endif                                             

                                                                                    @elseif(!empty($userPlan) && $userPlan->is_download == 1)      
                                                                                        @if($audio->aws_upload == 1)
                                                                                            <a href=" {{ getSongAWSUrlHtml($audio) }} ">
                                                                                                <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                                                                            </a> 
                                                                                        @else    
                                                                                            <a href="javascript:void(0);" class="download_track" data-musicid="{{ $audio->id }}">
                                                                                                <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                                                                            </a>
                                                                                        @endif              
                                                                                    @elseif(empty($audio->download_price) && empty($userPlan))   
                                                                                        <a href="{{ route('pricing-plan') }}">
                                                                                            <span class="opt_icon common_drop_icon drop_downld">
                                                                                                <span class="icon icon_download"></span>
                                                                                            </span>{{ __("frontWords.download") }}  
                                                                                        </a>             
                                                                                    @endif
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        
                                                    @endforeach
                                                    
                                                @endif
                                            @else
                                                <div class="col-lg-12">
                                                    <div class="ms_empty_data">
                                                        <p>{{ __("frontWords.no_track") }}</p>
                                                    </div>
                                                </div>                            
                                            @endif 

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Today Top Tracks End  -->


                        <!-- Trending Tracks Start -->
                        <div class="ms_popular_tracks_wrapper comman-sec-spacer pt-0">
                            <div class="slider_heading_wrap">
                                <div class="slider_cheading">
                                    <h4 class="cheading_title">{{ __("frontWords.trending_songs") }} &nbsp;</h4> 
                                </div>
                                <div class="slider_cmn_controls ">
                                    <a href="javascript:void(0)" class="ms_btn_link getAjaxRecord" data-type="audio" data-url="{{ route('user.audio') }}" title="{{ __('frontWords.trending_songs') }}">{{ __('frontWords.view_more') }}</a> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="ms_songslist_box">
                                        <ul class="ms_songlist ms_index_songlist mb-5">
                               
                                            @if(sizeof($trending_song) > 0)                                                                             
                                                @php $i= 1; @endphp

                                                @if(!empty($trending_song))                                                
                                                    @foreach($trending_song as $audio)
                                                            @php
                                                                $getArtist = json_decode($audio->artist_id);
                                                                $artist_name = '';
                                                            @endphp

                                                            @foreach($getArtist as $artistid)
                                                                @php
                                                                    $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistid] ]);
                                                                @endphp
                                                                @if(count($artists) > 0)
                                                                    @php $artist_name .= $artists[0]->artist_name.','; @endphp
                                                                @endif
                                                            @endforeach
                                                            @php
                                                                $getLikeDislikeAudio = getFavDataId(['column' => 'audio_id', 'audio_id' => $audio->id]);
                                                                $download = '';
                                                            @endphp     

                                                            <li>
                                                                <div class="ms_songslist_inner">
                                                                    <div class="ms_songslist_left play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                                                        <div class="songslist_number">
                                                                            <h4 class="songslist_sn">{{ $i++ }}</h4>
                                                                            <span class="songslist_play"><img src="{{ asset('public/images/svg/play_songlist.svg') }}" alt="Play" class="img-fluid"/></span>
                                                                        </div> 
                                                                        <div class="songslist_details">
                                                                            <div class="songslist_thumb play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                                                                @if($audio->image != '' && file_exists(public_path('images/audio/thumb/'.$audio->image)))
                                                                                    <img src="{{ asset('public/images/audio/thumb/'.$audio->image) }}" alt="">
                                                                                @else
                                                                                    <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                                                                                @endif
                                                                            </div>
                                                                            <div class="songslist_name play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">            
                                                                                <h3 class="song_name play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}"><a href="javascript:void(0);">{{ $audio->audio_title }}</a></h3>
                                                                                <p class="song_artist">{{ $artist_name }} </p>
                                                                            </div> 
                                                                        </div> 
                                                                    </div>
                                                                    <div class="ms_songslist_right">
                                                                        <span class="ms_songslist_like addToFavourite" data-favourite="{{ $audio->id }}" data-type="audio">
                                                                            @if($getLikeDislikeAudio == 1)
                                                                                <svg width="19px" height="19px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 391.8 391.8"><defs><style>.cls-1{fill:#d80027;}</style></defs>
                                                                                <title>{{ 'Remove From'.__('frontWords.favourites') }}</title>
                                                                                <path class="cls-1" d="M280.6,43.8A101.66,101.66,0,0,1,381.7,144.9c0,102-185.8,203.1-185.8,203.1S10.2,245.5,10.2,144.9A101.08,101.08,0,0,1,111.3,43.8h0A99.84,99.84,0,0,1,196,89.4,101.12,101.12,0,0,1,280.6,43.8Z"></path></svg>
                                                                            @else
                                                                                <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>
                                                                            @endif
                                                                        </span>
                                                                        <span class="ms_songslist_time">{{ $audio->audio_duration }}</span>
                                                                        <div class="ms_songslist_more">
                                                                            <span class="songslist_moreicon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.105 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.105 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg></span>
                                                                            <ul class="ms_common_dropdown ms_songslist_dropdown">
                                                                                <li><a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $audio->id }}" data-musictype="audio"><span class="opt_icon"><span class="icon icon_queue"></span></span>{{ __("frontWords.add_to_queue") }}</a></li>
                                                                                
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="ms_add_playlist" data-musicid="{{ $audio->id }}">
                                                                                        <span class="common_drop_icon drop_playlist"></span>
                                                                                        {{ __("frontWords.add_to_playlist") }}
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('audio/single/'.$audio->id.'/'.$audio->audio_slug) }}" data-sharename="{{ $audio->audio_title }}">
                                                                                        <span class="common_drop_icon drop_share"></span>
                                                                                        {{ __("frontWords.share") }}
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    @if(isset($audio->download_price) && !empty($audio->download_price))
                                                                                        <input type="hidden" class="getAudioAmountToDownload" value="{{ $audio->download_price }}">

                                                                                        @if(Auth::check())                
                                                                                            @php 
                                                                                                $buyedAudios = json_decode(auth()->user()->audio_download_list); 
                                                                                            @endphp
                                                                                            @if(!empty($buyedAudios))
                                                                                                @if(in_array($audio->id, $buyedAudios))
                                                                                                    <a href="javascript:void(0);" class="artistDownloadTrack download_artist_track" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                        <span class="opt_icon common_drop_icon drop_downld"> 
                                                                                                            <span class="icon icon_download"></span>
                                                                                                        </span>
                                                                                                        {{ __("frontWords.download") }}
                                                                                                    </a>
                                                                                                @else
                                                                                                    <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                        <span class="opt_icon common_drop_icon drop_downld">
                                                                                                            <span class="icon icon_download"></span>
                                                                                                        </span>
                                                                                                        {{ __("frontWords.buy_to_download") }}
                                                                                                    </a>
                                                                                                @endif
                                                                                            @else
                                                                                                <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                    <span class="opt_icon common_drop_icon drop_downld">
                                                                                                        <span class="icon icon_download"></span>
                                                                                                    </span>
                                                                                                    {{ __("frontWords.buy_to_download") }}
                                                                                                </a>
                                                                                            @endif
                                                                                                
                                                                                        @else
                                                                                            <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                <span class="opt_icon common_drop_icon drop_downld">
                                                                                                    <span class="icon icon_download"></span>
                                                                                                </span>
                                                                                                {{ __("frontWords.buy_to_download") }}
                                                                                            </a>                                                           
                                                                                        @endif                                             

                                                                                    @elseif(!empty($userPlan) && $userPlan->is_download == 1)      
                                                                                        @if($audio->aws_upload == 1)
                                                                                            <a href=" {{ getSongAWSUrlHtml($audio) }} ">
                                                                                                <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                                                                            </a> 
                                                                                        @else    
                                                                                            <a href="javascript:void(0);" class="download_track" data-musicid="{{ $audio->id }}">
                                                                                                <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                                                                            </a>
                                                                                        @endif              
                                                                                    @elseif(empty($audio->download_price) && empty($userPlan))   
                                                                                        <a href="{{ route('pricing-plan') }}">
                                                                                            <span class="opt_icon common_drop_icon drop_downld">
                                                                                                <span class="icon icon_download"></span>
                                                                                            </span>{{ __("frontWords.download") }}  
                                                                                        </a>             
                                                                                    @endif
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        
                                                    @endforeach
                                                    
                                                @endif
                                            @else
                                                <div class="col-lg-12">
                                                    <div class="ms_empty_data">
                                                        <p>{{ __("frontWords.no_track") }}</p>
                                                    </div>
                                                </div>                            
                                            @endif   

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Trending Tracks End  -->

                        <!-- New Release Tracks Start -->
                        <div class="ms_popular_tracks_wrapper comman-sec-spacer pt-0">
                            <div class="slider_heading_wrap">
                                <div class="slider_cheading">
                                    <h4 class="cheading_title">{{ __("frontWords.new_release") }} &nbsp;</h4>  
                                </div>
                                <div class="slider_cmn_controls ">
                                    <a href="javascript:void(0)" class="ms_btn_link getAjaxRecord" data-type="audio" data-url="{{ route('user.audio') }}" title="{{ __('frontWords.new_release') }}">{{ __("frontWords.view_more") }}</a> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="ms_songslist_box">
                                        <ul class="ms_songlist ms_index_songlist mb-5">
                                            @if(sizeof($new_release) > 0)                                                                             
                                                @php $i= 1; @endphp

                                                @if(!empty($new_release))                                                
                                                    @foreach($new_release as $audio)
                                                            @php
                                                                $getArtist = json_decode($audio->artist_id);
                                                                $artist_name = '';
                                                            @endphp

                                                            @foreach($getArtist as $artistid)
                                                                @php
                                                                    $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistid] ]);
                                                                @endphp
                                                                @if(count($artists) > 0)
                                                                    @php $artist_name .= $artists[0]->artist_name.','; @endphp
                                                                @endif
                                                            @endforeach
                                                            @php
                                                                $getLikeDislikeAudio = getFavDataId(['column' => 'audio_id', 'audio_id' => $audio->id]);
                                                                $download = '';
                                                            @endphp     

                                                            <li>
                                                                <div class="ms_songslist_inner">
                                                                    <div class="ms_songslist_left play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                                                        <div class="songslist_number">
                                                                            <h4 class="songslist_sn">{{ $i++ }}</h4>
                                                                            <span class="songslist_play"><img src="{{ asset('public/images/svg/play_songlist.svg') }}" alt="Play" class="img-fluid"/></span>
                                                                        </div> 
                                                                        <div class="songslist_details">
                                                                            <div class="songslist_thumb play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                                                                @if($audio->image != '' && file_exists(public_path('images/audio/thumb/'.$audio->image)))
                                                                                    <img src="{{ asset('public/images/audio/thumb/'.$audio->image) }}" alt="">
                                                                                @else
                                                                                    <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                                                                                @endif
                                                                            </div>
                                                                            <div class="songslist_name play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">            
                                                                                <h3 class="song_name play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}"><a href="javascript:void(0);">{{ $audio->audio_title }}</a></h3>
                                                                                <p class="song_artist">{{ $artist_name }} </p>
                                                                            </div> 
                                                                        </div> 
                                                                    </div>
                                                                    <div class="ms_songslist_right">
                                                                        <span class="ms_songslist_like addToFavourite" data-favourite="{{ $audio->id }}" data-type="audio">
                                                                            @if($getLikeDislikeAudio == 1)
                                                                                <svg width="19px" height="19px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 391.8 391.8"><defs><style>.cls-1{fill:#d80027;}</style></defs>
                                                                                <title>{{ 'Remove From'.__('frontWords.favourites') }}</title>
                                                                                <path class="cls-1" d="M280.6,43.8A101.66,101.66,0,0,1,381.7,144.9c0,102-185.8,203.1-185.8,203.1S10.2,245.5,10.2,144.9A101.08,101.08,0,0,1,111.3,43.8h0A99.84,99.84,0,0,1,196,89.4,101.12,101.12,0,0,1,280.6,43.8Z"></path></svg>
                                                                            @else
                                                                                <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>
                                                                            @endif
                                                                        </span>
                                                                        <span class="ms_songslist_time">{{ $audio->audio_duration }}</span>
                                                                        <div class="ms_songslist_more">
                                                                            <span class="songslist_moreicon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.105 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.105 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg></span>
                                                                            <ul class="ms_common_dropdown ms_songslist_dropdown">
                                                                                <li><a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $audio->id }}" data-musictype="audio"><span class="opt_icon"><span class="icon icon_queue"></span></span>{{ __("frontWords.add_to_queue") }}</a></li>
                                                                                
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="ms_add_playlist" data-musicid="{{ $audio->id }}">
                                                                                        <span class="common_drop_icon drop_playlist"></span>
                                                                                        {{ __("frontWords.add_to_playlist") }}
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('audio/single/'.$audio->id.'/'.$audio->audio_slug) }}" data-sharename="{{ $audio->audio_title }}">
                                                                                        <span class="common_drop_icon drop_share"></span>
                                                                                        {{ __("frontWords.share") }}
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    @if(isset($audio->download_price) && !empty($audio->download_price))
                                                                                        <input type="hidden" class="getAudioAmountToDownload" value="{{ $audio->download_price }}">

                                                                                        @if(Auth::check())                
                                                                                            @php 
                                                                                                $buyedAudios = json_decode(auth()->user()->audio_download_list); 
                                                                                            @endphp
                                                                                            @if(!empty($buyedAudios))
                                                                                                @if(in_array($audio->id, $buyedAudios))
                                                                                                    <a href="javascript:void(0);" class="artistDownloadTrack download_artist_track" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                        <span class="opt_icon common_drop_icon drop_downld"> 
                                                                                                            <span class="icon icon_download"></span>
                                                                                                        </span>
                                                                                                        {{ __("frontWords.download") }}
                                                                                                    </a>
                                                                                                @else
                                                                                                    <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                        <span class="opt_icon common_drop_icon drop_downld">
                                                                                                            <span class="icon icon_download"></span>
                                                                                                        </span>
                                                                                                        {{ __("frontWords.buy_to_download") }}
                                                                                                    </a>
                                                                                                @endif
                                                                                            @else
                                                                                                <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                    <span class="opt_icon common_drop_icon drop_downld">
                                                                                                        <span class="icon icon_download"></span>
                                                                                                    </span>
                                                                                                    {{ __("frontWords.buy_to_download") }}
                                                                                                </a>
                                                                                            @endif
                                                                                                
                                                                                        @else
                                                                                            <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                <span class="opt_icon common_drop_icon drop_downld">
                                                                                                    <span class="icon icon_download"></span>
                                                                                                </span>
                                                                                                {{ __("frontWords.buy_to_download") }}
                                                                                            </a>                                                           
                                                                                        @endif                                             

                                                                                    @elseif(!empty($userPlan) && $userPlan->is_download == 1)      
                                                                                        @if($audio->aws_upload == 1)
                                                                                            <a href=" {{ getSongAWSUrlHtml($audio) }} ">
                                                                                                <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                                                                            </a> 
                                                                                        @else    
                                                                                            <a href="javascript:void(0);" class="download_track" data-musicid="{{ $audio->id }}">
                                                                                                <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                                                                            </a>
                                                                                        @endif              
                                                                                    @elseif(empty($audio->download_price) && empty($userPlan))   
                                                                                        <a href="{{ route('pricing-plan') }}">
                                                                                            <span class="opt_icon common_drop_icon drop_downld">
                                                                                                <span class="icon icon_download"></span>
                                                                                            </span>{{ __("frontWords.download") }}  
                                                                                        </a>             
                                                                                    @endif
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        
                                                    @endforeach
                                                    
                                                @endif
                                            @else
                                                <div class="col-lg-12">
                                                    <div class="ms_empty_data">
                                                        <p>{{ __("frontWords.no_track") }}</p>
                                                    </div>
                                                </div>                            
                                            @endif    

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- New Release Tracks End  -->     

                        <!-- Offer Banner Start -->
                        <div class="ms_offerbanner mb-5 pb-5"> 
                            <div class="ms_offerbanner_img text-center">
                                @section('script')
                                    @if(isset($google_ad) && !empty($google_ad))
                                        @php

                                            $getAd = $google_ad->limit(1)->first();

                                            if(!empty($userPlan) && $userPlan->show_advertisement == 1){
                                                echo '<div class="google_ad text-center p-5 m-5">'.
                                                        (sizeof($getAd) > 0 && $getAd->status == 1 ? html_entity_decode($getAd->google_ad_script) : '').'
                                                    </div>';
                                            }                                
                                            
                                        @endphp
                                    @endif
                                @endsection
                                <!-- <img src="https://themes91.in/wp/miraculous-update/wp-content/uploads/2018/09/adv.jpg" alt=""> -->
                            </div>
                        </div>
                        <!-- Offer Banner End -->
                        <!-- Popular Album Start -->
                        <div class="ms_popular_album_wrapper comman-sec-spacer"> 
                            <div class="slider_heading_wrap">
                                <div class="slider_cheading">
                                    <h4 class="cheading_title"> {{ __("frontWords.popular_albums") }} &nbsp;</h4> 
                                </div>
                                <div class="slider_cmn_controls">
                                <a href="javascript:void(0)" class="ms_btn_link getAjaxRecord" data-type="album" data-url="{{ route('user.album') }}" title="{{ __('frontWords.popular_albums') }}">{{ __("frontWords.view_more") }}</a>
                                </div>
                            </div>
                            <div class="row">
                                @php 
                                    if(sizeof($top_album) > 0){
                                                $albums_id = json_decode($top_album[0]->top_album);
                                                    if(!empty($albums_id)){
                                                        foreach($albums_id as $album_id){
                                                            $albumsData = select(['column' => '*', 'table' => 'albums', 'where' => ['id'=>$album_id] ]);
                                                            if(!empty($albumsData)){
                                                                foreach($albumsData as $album){
                                                                $artist_name = get_artist_name(['album_id'=>$album->id]);
                                @endphp
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
                                            <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                <div class="slider_cimgbox slider_artist_imgbox play_box_img">
                                                    @if($album->image != '' && file_exists(public_path('images/album/'.$album->image)))
                                                        <img src="{{ asset('public/images/album/'.$album->image) }}" alt="" class="img-fluid">
                                                    @else
                                                        <img src="{{ dummyImage('album') }}" alt="" class="img-fluid">
                                                    @endif
                                                    <div class="ms_play_icon play_music" data-musicid="{{ $album->id }}" data-musictype="album" data-url="{{ url('/songs') }}">
                                                        <img src="{{ asset('public/images/svg/play.svg') }}" alt="play icone">
                                                    </div>
                                                </div>
                                                <div class="slider_ctext slider_artist_text">
                                                    <a class="slider_ctitle slider_artist_ttl limited_text_line getAjaxRecord" data-type="album" data-url="{{ url('album/single/'.$album->id.'/'.$album->album_slug) }}" href="javascript:void(0)">{{ $album->album_name }}</a>
                                                </div>
                                            </div>
                                        </div>

                                @php } } } } } else { @endphp  
                                    <div class="col-lg-12">
                                        <div class="ms_empty_data" style="padding-left:17px;">
                                            <p> {{ __("frontWords.no_album") }} </p>
                                        </div>
                                    </div>
                                @php } @endphp

                            </div>
                        </div>
                        <!-- Popular Album END -->

                        <!-- Genres Start -->
                        <div class="ms_genres_wrapper comman-sec-spacer"> 
                            <div class="slider_heading_wrap">
                                <div class="slider_cheading">
                                    <h4 class="cheading_title">{{ __('adminWords.genre') }} &nbsp;</h4>
                                </div>
                                <div class="slider_cmn_controls">
                                 <a href="javascript:void(0)" class="ms_btn_link getAjaxRecord" data-type="genre" data-url="{{ route('user.genres') }}" title="{{ __('adminWords.genre') }}">{{ __("frontWords.view_more") }}</a>
                                </div> 
                            </div> 
                                <!-- Top Genres section -->
                                <div class="ms_genres_style2">
                                    @php
                                        if(sizeof($genres) > 0){
                                    @endphp
                                        <div class="row">
                                                @php
                                                if(sizeof($genres) > 0){
                                                    $html = '';
                                                    foreach($genres as $genre) {
                                                        if($genre->image != '' && file_exists(public_path('images/audio/audio_genre/'.$genre->image))){
                                                            $img = '<img src="'.asset('public/images/audio/audio_genre/'.$genre->image).'" alt="" class="img-fluid">';
                                                        }else{
                                                            $img = '<img src="'.dummyImage('genre').'" alt="" class="img-fluid">';
                                                        }
                                                        echo'<div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-6">
                                                                <a href="'.url('genre/single/'.$genre->id.'/'.$genre->genre_slug).'" class="d-block w-100">
                                                                
                                                                <div class="slider_cbox">
                                                                    <div class="slider_cimgbox">'.$img.'</div>
                                                                    <div class="slider_ctext">
                                                                        <span>'.$genre->genre_name.'</span>
                                                                    </div>
                                                                </div>
                                                                </a>
                                                            </div>';   
                                                    }

                                                }else{
                                                    echo '<div class="ms_empty_data">
                                                            <p>'.__("frontWords.no_genre").'</p>
                                                        </div>';
                                                }
                                                @endphp                     
                                        </div>
                                    @php
                                        }else{
                                            echo '<div class="ms_empty_data">
                                                    <p>'.__("frontWords.no_genre").'</p>
                                                </div>';
                                        }
                                    @endphp
                                </div>

                            </div>
                       
                        <!-- Genres End  -->

                    </div>
                </div>

                <div class="col-xl-2 col-lg-4"> 
                    <div class="ms_sidebar_wrap">
                       <div>
                            @section('script')
                                @if(isset($google_ad) && !empty($google_ad))
                                    @php

                                        $getAd = $google_ad->limit(1)->first();

                                        if(!empty($userPlan) && $userPlan->show_advertisement == 1){
                                            echo '<div class="google_ad text-center p-5 m-5">'.
                                                    (sizeof($getAd) > 0 && $getAd->status == 1 ? html_entity_decode($getAd->google_ad_script) : '').'
                                                </div>';
                                        }                                
                                        
                                    @endphp
                                @endif
                            @endsection

                            <!-- Ad Img Here -->
                            <!-- <img src="{{ asset('public/assets/images/ad.jpg') }}" alt="">   -->
                       </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

@endsection 

@section('script')
    <script src="{{ asset('public/assets/js/star-rating.js') }}"></script>
@endsection