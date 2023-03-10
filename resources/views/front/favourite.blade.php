@extends('layouts.front.main')
@section('title', __('frontWords.favourites'))
@section('content')

    @php
        if(isset(Auth::user()->id)){
    @endphp
        <!---index page--->
        <div class="ms_history_wrapper common_pages_space">
        <div class="ms_history_inner">   
        
            <div class="album_inner_list">
                
                <div class="slider_heading_wrap marger_bottom30">
                    <div class="slider_cheading">
                        <h4 class="cheading_title"> {{ __('frontWords.favourites') }} &nbsp;</h4>
                    </div>
                </div>
                <div class="album_list_wrapper">
                    <ul class="album_list_name">
                        <li>#</li>
                        <li>{{ __('frontWords.track_title') }}</li>
                        <li>{{ __('frontWords.artist') }}</li>
                        <li class="text-center">{{ __('frontWords.duration') }}</li> 
                        <li class="text-center">{{ __('frontWords.favourites') }}</li>
                        <li class="text-center">{{ __('frontWords.more') }}</li>                                
                    </ul>
                    @php 
                        if(sizeof($favourites) > 0 && $favourites[0]->audio_id != '' && $favourites[0]->audio_id != '[]'){
                            $favourite = json_decode($favourites[0]->audio_id);
                            if(sizeof($favourite) > 0){
                            
                                $cnt = 0;
                                foreach($favourite as $favourite_audio){
                                    $audioDetail = audioDetail(['songid' => $favourite_audio]);
                                    if(sizeof($audioDetail) > 0){
                                        foreach($audioDetail as $audios){
                                            $cnt++;
                                            $getArtist = json_decode($audios->artist_id); 
                                            if(sizeof($getArtist) > 0){
                                                $artist_name = '';
                                                foreach($getArtist as $artistName){
                                                    $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistName] ]);
                                                    if(count($artists) > 0){
                                                        $artist_name .= $artists[0]->artist_name.', ';
                                                    }
                                                }
                                                $getLikeDislikeAudio = getFavDataId(['column' => 'audio_id', 'audio_id' => $audios->id]);
                                                @endphp
                                                <ul class="removeFavRow">  <!-- play_active_song -->
                                                    
                                                    <li class="play_music" data-musicid="{{ $audios->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                                        <span class="play_no">{{ $cnt }}</span>
                                                        <span class="play_hover">
                                                            <img src="{{ asset('images/svg/play_songlist.svg') }}" alt="Play" class="img-fluid list_play">
                                                            <img src="{{ asset('images/svg/sound_bars.svg') }}" alt="bar" class="img-fluid list_play_bar">  
                                                        </span>
                                                    </li>
                                                    <li><a href="javascript:void(0);" class="play_music" data-musicid="{{ $audios->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">{{ $audios->audio_title }}</a></li>
                                                    <li><a href="javascript:void(0);" class="play_music" data-musicid="{{ $audios->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">{{ rtrim($artist_name,', ') }}</a></li>
                                                    <li class="text-center"><a href="javascript:void(0);" class="play_music" data-musicid="{{ $audios->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">{{ $audios->audio_duration }}</a></li>
                                                    <li class="text-center">
                                                        <a href="javascript:void(0);" class="addToFavourite" data-favourite="{{ $audios->id }}" data-type="audio">
                                                            <span class="list_heart">
                                                                <svg width="19px" height="19px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 391.8 391.8"><defs><style>.cls-1{fill:#d80027;}</style></defs><title>Remove From {{ __('frontWords.favourites') }}</title><path class="cls-1" d="M280.6,43.8A101.66,101.66,0,0,1,381.7,144.9c0,102-185.8,203.1-185.8,203.1S10.2,245.5,10.2,144.9A101.08,101.08,0,0,1,111.3,43.8h0A99.84,99.84,0,0,1,196,89.4,101.12,101.12,0,0,1,280.6,43.8Z"></path></svg>
                                                            </span>
                                                        </a>
                                                    </li>                                
                                                    <li class="list_more">
                                                        <a href="javascript:void(0);" class="songslist_moreicon">
                                                            <span >
                                                                <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.104 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.104 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg>
                                                            </span>
                                                        </a>
                                                        <ul class="ms_common_dropdown ms_downlod_list">                                                                       
                                                            <li>
                                                                
                                                                <a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $audios->id }}" data-musictype="audio">
                                                                <span class="opt_icon" title="Add To Queue"><span class="icon icon_queue"></span></span>
                                                                {{ __("frontWords.add_to_queue") }}
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);" class="ms_add_playlist" data-musicid="{{ $audios->id }}">
                                                                    <span class="common_drop_icon drop_playlist"></span>{{ __('frontWords.add_to_playlist') }}
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('audio/single/'.$audios->id.'/'.$audios->audio_slug) }}" data-sharename="{{ $audios->title }}">
                                                                    <span class="common_drop_icon drop_share"></span>{{ __('frontWords.share') }}
                                                                </a>
                                                            </li>
                                                            <li>
                                                                @if(isset($audios->download_price) && !empty($audios->download_price))

                                                                    @if(Auth::check())
                                                                            
                                                                        @php 
                                                                            $buyedAudios = json_decode(auth()->user()->audio_download_list); 
                                                                        @endphp
                                                                        @if(!empty($buyedAudios))
                                                                            @if(in_array($audios->id, $buyedAudios))
                                                                                <a href="javascript:void(0);" class="artistDownloadTrack download_artist_track" data-musicid="{{ $audios->id }}" data-type="audio">     
                                                                                    <span class="opt_icon common_drop_icon drop_downld"> 
                                                                                        <span class="icon icon_download"></span>
                                                                                    </span>
                                                                                    {{ __("frontWords.download") }}
                                                                                </a>
                                                                            @else
                                                                                <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audios->id }}" data-type="audio">     
                                                                                    <span class="opt_icon common_drop_icon drop_downld">
                                                                                        <span class="icon icon_download"></span>
                                                                                    </span>
                                                                                    {{ __("frontWords.buy_to_download") }}
                                                                                </a>
                                                                            @endif
                                                                        @else
                                                                            <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audios->id }}" data-type="audio">     
                                                                                <span class="opt_icon common_drop_icon drop_downld">
                                                                                    <span class="icon icon_download"></span>
                                                                                </span>
                                                                                {{ __("frontWords.buy_to_download") }}
                                                                            </a>
                                                                        @endif
                                                                            
                                                                    @else
                                                                        <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audios->id }}" data-type="audio">     
                                                                            <span class="opt_icon common_drop_icon drop_downld">
                                                                                <span class="icon icon_download"></span>
                                                                            </span>
                                                                            {{ __("frontWords.buy_to_download") }}
                                                                        </a>                                                           
                                                                    @endif                                             

                                                                @elseif(!empty($userPlan) && $userPlan->is_download == 1)
                                                                    @if($audios->aws_upload == 1)
                                                                        <a href=" {{ getSongAWSUrlHtml($audios) }} ">
                                                                            <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                                                        </a> 
                                                                    @else    
                                                                        <a href="javascript:void(0);" class="download_track" data-musicid="{{ $audios->id }}">
                                                                            <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                                                        </a>
                                                                    @endif                                    
                                                                @elseif(empty($audios->download_price) && empty($userPlan))                                    
                                                                    <a href="{{ route('pricing-plan') }}">
                                                                        <span class="opt_icon common_drop_icon drop_downld">
                                                                            <span class="icon icon_download"></span>
                                                                        </span>{{ __("frontWords.download") }}  
                                                                    </a>            
                                                                @endif
                                                            </li>
                                                        </ul>                                   
                                                    </li>                               
                                                </ul>
                                                @php
                                        }
                                    }
                                }
                            }
                        }else{ 
                            echo '<ul class="removeFavRow"><li class="ms_empty_data">'.__("frontWords.no_track").'</li></ul>';
                        }
                    }else{ 
                            echo '<ul class="removeFavRow"><li class="ms_empty_data">'.__("frontWords.no_track").'</li></ul>';
                        }
                    @endphp
                </div>
                
            </div>
            
            
            @php 
                if(sizeof($favourites) > 0 && $favourites[0]->album_id != '' && $favourites[0]->album_id != '[]'){
            @endphp
            <!-- Favourite Albums section -->
            <div class="ms_artist_slider top_album_slider">
                <div class="slider_heading_wrap">
                    <div class="slider_cheading">
                        <h4 class="cheading_title">{{ __('frontWords.your_fav').' '.__('frontWords.album') }} &nbsp;</h4>
                    </div>
                        <!-- Add Arrows -->
                        <div class="slider_cmn_controls">
                            <div class="slider_cmn_nav "><span class="swiper-button-next1 slider_nav_next"></span></div>
                            <div class="slider_cmn_nav"><span class="swiper-button-prev1 slider_nav_prev"></span></div>
                        </div>
                    </div>
                
                    <div class="ms_artist_innerslider">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @php 
                                    $favourite = json_decode($favourites[0]->album_id);
                                    foreach($favourite as $fav_id){
                                        $albumData = select(['column' => '*', 'table' => 'albums', 'where' => ['id'=>$fav_id] ]);
                                        if(sizeof($albumData) > 0){
                                            foreach($albumData as $album){
                                @endphp
                                       <div class="swiper-slide play_btn play_music play_icon_btn" data-musicid="{{ $album->id }}" data-musictype="album" data-url="{{ url('/songs') }}">
                                            <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                <div class="slider_cimgbox slider_artist_imgbox play_box_img">
                                                    @if($album->image != '' && file_exists(public_path('images/album/'.$album->image)))
                                                        <img src="{{ asset('images/album/'.$album->image) }}" alt="" class="img-fluid">
                                                    @else
                                                        <img src="{{ dummyImage('album') }}" alt="" class="img-fluid">
                                                    @endif 
                                                    <div class="ms_play_icon">
                                                        <img src="{{ asset('images/svg/play.svg') }}" alt="play icone">
                                                    </div>
                                                </div>
                                                <div class="slider_ctext slider_artist_text">
                                                    <a class="slider_ctitle slider_artist_ttl limited_text_line" href="{{ url('album/single/'.$album->id.'/'.$album->album_slug) }}">{{ $album->album_name }}</a>
                                                    <!-- <p class="slider_cdescription slider_artist_des">2018</p> -->
                                                </div>
                                            </div>
                                    </div>  
                                    @php   }
                                        }
                                    @endphp                     
                            
                                     @php } @endphp 
                            </div>
                        </div>
                    </div> 
            </div>
            @php } @endphp
                        
                        
            @php 
                if(sizeof($favourites) > 0 && $favourites[0]->artist_id != '' && $favourites[0]->artist_id != '[]'){
            @endphp
            <!-- Recommended Artists section -->
            <div class="ms_artist_slider2 top_album_slider">
                <div class="slider_heading_wrap">
                    <div class="slider_cheading">
                        <h4 class="cheading_title">{{ __('frontWords.your_fav').' '.__('frontWords.artist') }} &nbsp;</h4>
                    </div>     
                    <!-- Add Arrows -->
                    <div class="slider_cmn_controls">
                        <div class="slider_cmn_nav "><span class="swiper-button-next2 slider_nav_next"></span></div>
                        <div class="slider_cmn_nav"><span class="swiper-button-prev2 slider_nav_prev"></span></div>
                    </div>
                </div>
                <div class="ms_artist_innerslider">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            @php 
                                $favourite = json_decode($favourites[0]->artist_id);
                                foreach($favourite as $fav_id){
                                    $artistData = select(['column' => '*', 'table' => 'artists', 'where' => ['id'=>$fav_id] ]);
                                    if(sizeof($artistData) > 0){
                                        foreach($artistData as $artist){
                            @endphp
                                            <div class="swiper-slide play_btn play_music play_icon_btn" data-musicid="{{ $artist->id }}" data-musictype="artist" data-url="{{ url('/songs') }}">
                                                <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                    <div class="slider_cimgbox slider_artist_imgbox play_box_img">
                                                        @if($artist->image != '' && file_exists(public_path('images/artist/'.$artist->image)))
                                                            <img src="{{ asset('images/artist/'.$artist->image) }}" alt="" class="img-fluid">
                                                        @else
                                                            <img src="{{ dummyImage('artist') }}" alt="" class="img-fluid">
                                                        @endif   
                                                        <div class="ms_play_icon">
                                                            <img src="{{ asset('images/svg/play.svg') }}" alt="play icone">
                                                        </div>
                                                    </div>
                                                    <div class="slider_ctext slider_artist_text">
                                                        <a class="slider_ctitle slider_artist_ttl limited_text_line" href="{{ url('artist/single/'.$artist->id.'/'.$artist->artist_slug) }}">{{ $artist->artist_name }}</a>                                          
                                                        <!-- <p class="slider_cdescription slider_artist_des">Anna Ellison, Claire Hudson</p> -->
                                                    </div>
                                                </div>
                                            </div>     
                                
                            @php
                                            }
                                        }
                                    } 
                            @endphp
                                    
                         </div>
                    </div>
                </div>
                       
            </div>
            @php } @endphp                    
            
            @php 
                if(sizeof($favourites) > 0 && $favourites[0]->genre_id != '' && $favourites[0]->genre_id != '[]'){
            @endphp
            <div class="ms_artist_slider top_album_slider">
                <div class="slider_heading_wrap">
                    <div class="slider_cheading">
                        <h4 class="cheading_title">{{  __('frontWords.your_fav').' '.__('adminWords.genre') }} &nbsp;</h4>
                    </div>
                    <!-- Add Arrows -->
                    <div class="slider_cmn_controls">
                        <div class="slider_cmn_nav "><span class="swiper-button-next1 slider_nav_next"></span></div>
                        <div class="slider_cmn_nav"><span class="swiper-button-prev1 slider_nav_prev"></span></div>
                    </div>
                </div>
                <div class="ms_artist_innerslider">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    @php 
                                        $favourite = json_decode($favourites[0]->genre_id);
                                        foreach($favourite as $fav_id){
                                            $genres = select(['column' => '*', 'table' => 'audio_genres', 'where' => ['id'=>$fav_id] ]);
                                            if(sizeof($genres) > 0){
                                                foreach($genres as $genre){
                                     
                                                    if($genre->image != '' && file_exists(public_path('images/audio/audio_genre/'.$genre->image))){
                                                        $img = '<img src="'.asset('images/audio/audio_genre/'.$genre->image).'" alt="" class="img-fluid">';
                                                    }else{
                                                        $img = '<img src="'.dummyImage('genre').'" alt="" class="img-fluid">';
                                                    }
        
                                                        echo'<a href="'.url('genre/single/'.$genre->id.'/'.$genre->genre_slug).'"><div class="swiper-slide">
                                                            <div class="slider_cbox slider_artist_box text-center">
                                                                <div class="slider_cimgbox slider_artist_imgbox">'.$img.'</div>
                                                                <div class="slider_ctext slider_artist_text">
                                                                    <a class="slider_ctitle slider_artist_ttl limited_text_line" href="'.url('genre/single/'.$genre->id.'/'.$genre->genre_slug).'">'.$genre->genre_name.'</a>
                                                                    <!-- <p class="slider_cdescription slider_artist_des">2018</p> -->
                                                                </div>
                                                            </div>
                                                        </div></a>';   
                                    
                                                }

                                            }else{
                                                echo '<div class="ms_empty_data">
                                                        <p>'.__("frontWords.no_genre").'</p>
                                                    </div>';
                                            } }
                                            @endphp                     
                                </div>
                            </div>
                        </div>
            </div>
            @php } @endphp
                
            
            @php 
                if(sizeof($favourites) > 0 && $favourites[0]->playlist_id != '' && $favourites[0]->playlist_id != '[]'){
            @endphp
            <div class="ms_artist_slider top_album_slider">
                <div class="slider_heading_wrap">
                    <div class="slider_cheading">
                        <h4 class="cheading_title">{{ __('frontWords.your_fav').' '.__('frontWords.playlist') }} &nbsp;</h4>
                    </div>
            
               <!-- Add Arrows -->
                    <div class="slider_cmn_controls">
                        <div class="slider_cmn_nav "><span class="swiper-button-next1 slider_nav_next"></span></div>
                        <div class="slider_cmn_nav"><span class="swiper-button-prev1 slider_nav_prev"></span></div>
                    </div>
                </div>
                <div class="ms_artist_innerslider">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                @php
                        $favourite = json_decode($favourites[0]->playlist_id);
                        foreach($favourite as $fav_id){ 
                            $playlist = select(['column' => '*', 'table' => 'playlists', 'where' => ['id'=>$fav_id] ]);
                            //print_r($playlist); die;
                            foreach($playlist as $list){
                                $img = asset('assets/images/playlist.jpg');
                                if(!empty($list->song_list) && $list->song_list != ''){
                                    $songs = json_decode($list->song_list);
                                    if(!empty($songs)){
                                        $img = audioDetail(['songid'=>$songs[0], 'image' => 1]);
                                    }
                                }
                @endphp
                <div class="col-lg-2 col-md-6">
                        <div class="ms_rcnt_box marger_bottom25">
                            <div class="ms_rcnt_box_img">
                                <img src="{{ $img == '[]' ? asset('assets/images/playlist.jpg') : $img  }}" alt="" class="img-fluid">
                                <div class="ms_main_overlay">
                                    <div class="ms_box_overlay"></div>
                                        <div class="ms_more_icon">
                                            <img src="{{ asset('assets/images/svg/more.svg') }}" alt="More">
                                        </div>
                                        
                                        <ul class="more_option open_option remove_playlist">
                                            
                                            <li class="favourite_icon">
                                                <a href="javascript:void(0);" class="addToFavourite" data-favourite="{{ $list->id }}" data-type="playlist">
                                                    <svg width="19px" height="19px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 391.8 391.8"><defs><style>.cls-1{fill:#d80027;}</style></defs><title>Remove From {{ __('frontWords.favourites') }}</title><path class="cls-1" d="M280.6,43.8A101.66,101.66,0,0,1,381.7,144.9c0,102-185.8,203.1-185.8,203.1S10.2,245.5,10.2,144.9A101.08,101.08,0,0,1,111.3,43.8h0A99.84,99.84,0,0,1,196,89.4,101.12,101.12,0,0,1,280.6,43.8Z"></path></svg>
                                                </a>
                                                {{ __('frontWords.favourites') }}
                                                
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="download_list" data-musicid="{{ $list->id }}" data-type="playlist">
                                                    <span class="opt_icon common_drop_icon drop_downld">
                                                        <span class="icon icon_download"></span>
                                                    </span>
                                                    {{ __("frontWords.download") }} 
                                                </a>
                                            </li>
                                            
                                            <li>
                                                <a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $list->id }}" data-musictype="playlist">
                                                <span class="opt_icon" title="Add To Queue"><span class="icon icon_queue"></span></span>
                                                    {{ __("frontWords.add_to_queue") }}
                                                </a>
                                            </li>
                                            
                                            <li>
                                                <a href="javascript:void(0);" class="ms_remove_user_playlist" data-list-id="{{ $list->id }}"><span class="opt_icon">
                                                    <span class="icon icon_playlst"></span>
                                                    </span>{{ __('frontWords.remove_playlist') }}
                                                </a>
                                            </li>
                                        </ul>
                                    <div class="ms_play_icon play_btn play_list_music play_icon_btn" data-musicid="{{ $list->id }}">
                                        <img src="{{ asset('assets/images/svg/play.svg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="ms_rcnt_box_text">
                                <h3><a href="{{ url('playlist/single/'.$list->id)}}" class="limited_text_line">{{ $list->playlist_name }}</a></h3>
                                <p>{{ !empty($list->song_list) ? count(json_decode($list->song_list)) : 0 }} {{ __('frontWords.track') }}</p>
                            </div>
                        </div>
                    </div>
                    @php    
                            } 
                        }
                    @endphp
                            </div>
                        </div>
                    </div>
            </div>
            @php } @endphp
                
                
        </div>
        @include('layouts.front.footer')
    </div>
    
    @php
        }else{
            echo ' <div class="ms_history_wrapper common_pages_space">
                        <article id="post-31">
                            <div class="ms_entry_content">   
                                <div class="fw-page-builder-content">
                                    <section class="fw-main-row ">
                                        <div class="fw-container-fluid">
                                            <div class="fw-row">
                                                <div class="fw-col-xs-12">
                                                    <div class="ms_needlogin">
                                                        <div class="needlogin_img">
                                                            <img src="'.asset('assets/images/svg/headphones.svg').'" alt="">
                                                        </div>
                                                        <h2>'.__("frontWords.need_to_login").'</h2>
                                                        <a href="javascript:void(0);" class="ms_btn reg_btn" data-toggle="modal" data-target="#loginModal">
                                                            <span>'.__("frontWords.register_login").'</span>
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
                    </div>';
            }
    @endphp
@endsection