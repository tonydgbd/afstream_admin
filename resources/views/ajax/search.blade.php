
@section('search')
{{ $search }}
@endsection

    <div class="musiooFrontSinglePage">
        <div class="ms_artist_wrapper common_pages_space">

            @if(!empty($is_youtube) && $is_youtube == 1 && sizeof($ytBrowseSearch) != 0)
                <div class="ms_top_artist">
                    <div class="col-lg-12">
                        <div class="ms_heading">
                            <h1>{{ __("adminWords.youtube").' '.__("frontWords.search_browse_music") }}</h1>
                        </div>
                    </div>
                    <div class="container-fluid ms_track_slides">
                        <div class="row">
                            @php

                                $channelTitle = '';                               
                                $ytImage = '';   
                                $videoId = '';

                                foreach($ytBrowseSearch as $music){                                      

                                    $channelTitle = $music->snippet->channelTitle; 
                                    if(isset($music->snippet->thumbnails->medium->url) && !empty($music->snippet->thumbnails->medium->url)){
                                        $ytImage = $music->snippet->thumbnails->medium->url;
                                    }elseif(isset($music->snippet->thumbnails->high->url) && $music->snippet->thumbnails->high->url){
                                        $ytImage = $music->snippet->thumbnails->high->url;
                                    }elseif(isset($music->snippet->thumbnails->default->url) && !empty($music->snippet->thumbnails->default->url)){
                                        $ytImage = $music->snippet->thumbnails->default->url;
                                    }else{ $ytImage = 'public/images/yt_music.webp'; }

                                    if(isset($music->id->videoId) && !empty($music->id->videoId)){
                                        $videoId = $music->id->videoId;
                                    }
                                @endphp
                                @if(!empty($videoId))
                                    <div class="col-lg-2 col-md-6">
                                        <div class="ms_rcnt_box marger_bottom30">
                                            <div class="ms_rcnt_box_img">                                        
                                                
                                                @if($music->snippet->thumbnails != '' )
                                                    <img src="{{ asset($ytImage) }}" alt="" class="img-fluid">
                                                @else
                                                    <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                                                @endif     

                                                <div class="album_more_optn list_more"> 
                                                    <ul>
                                                        <li class="list_more">
                                                            <a href="javascript:void(0);" class="songslist_moreicon">
                                                                <span >
                                                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.104 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.104 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg>
                                                                </span>
                                                            </a>
                                                            <ul class="ms_common_dropdown ms_downlod_list list_more">    
                                                                
                                                                <li>
                                                                    <a href="javascript:void(0);" class="ms_add_playlist" data-musicid="{{ $videoId }}" data-musictype="ms_video">
                                                                        <span class="common_drop_icon drop_playlist"></span>
                                                                        {{ __("frontWords.add_to_playlist") }}
                                                                    </a>
                                                                </li>

                                                                <!-- 
                                                                <li>
                                                                    <a href="javascript:void(0);" class="add_to_queue" data-musicid="" data-musictype="playlist">
                                                                    <span class="opt_icon" title="Add To Queue"><span class="icon icon_queue"></span></span>
                                                                    {{ __("frontWords.add_to_queue") }}
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:void(0);" class="download_list" data-musicid="" data-type="playlist">
                                                                        <span class="opt_icon common_drop_icon drop_downld">
                                                                            <span class="icon icon_download"></span>
                                                                        </span>
                                                                        {{ __("frontWords.download") }} 
                                                                    </a>
                                                                </li>      
                                                                <li>
                                                                    <a href="javascript:void(0);" class="ms_share_music" data-shareuri="" data-sharename="">
                                                                        <span class="common_drop_icon drop_share"></span>{{ __('frontWords.share') }}
                                                                    </a>
                                                                </li> -->
                                                                
                                                            </ul>                                   
                                                        </li>
                                                    
                                                    </ul>
                                                </div>

                                                <div class="ms_main_overlay">
                                                    <div class="ms_box_overlay"></div>                                                     

                                                    <div class="ms_play_icon play_btn yt_music" data-musicid="{{ $videoId }}" data-title="{{ $music->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">
                                                        <img src="{{ asset('public/assets/images/svg/play.svg') }}" alt="">
                                                    </div>
                                                    
                                                </div>                                            
                                            </div>
                                            
                                            <div class="ms_rcnt_box_text">
                                                <h3><a href="javascript:void(0)" class="yt_music" data-musicid="{{ $videoId }}" data-title="{{ $music->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">{{ $music->snippet->title }}</a></h3>
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @php
                                    }                    
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>               
            @endif


            @php
                if(sizeof($albums) > 0){
            @endphp    
            
                <div class="ms_top_artist">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="ms_heading">
                                    <h1>{{ __('frontWords.album') }}</h1>
                                </div>
                            </div>
                            @php
                                if(sizeof($albums) > 0){
                                    foreach($albums as $album){
                                       $artist_name = get_artist_name(['album_id'=>$album->id]);
                                        $getLikeDislikeAlbum = getFavDataId(['column' => 'album_id', 'album_id' => $album->id]);
                            @endphp
                                            <div class="col-lg-2 col-md-6">
                                                <div class="ms_rcnt_box marger_bottom30">
                                                    <div class="ms_rcnt_box_img">
                                                        @if($album->image != '' && file_exists(public_path('images/album/'.$album->image)))
                                                            <img src="{{ asset('public/images/album/'.$album->image) }}" alt="" class="img-fluid">
                                                        @else
                                                            <img src="{{ dummyImage('album') }}" alt="" class="img-fluid">
                                                        @endif                                            
                                                        <div class="ms_main_overlay">
                                                            <div class="ms_box_overlay"></div>
                                                            <div class="ms_more_icon">
                                                                <img src="{{ asset('public/assets/images/svg/more.svg') }}" alt="">
                                                            </div>
                                                            
                                                            <ul class="more_option">
                                                                <li><a href="javascript:;" class="addToFavourite" data-favourite="{{ $album->id }}" data-type="album"><span class="opt_icon"><span class="icon icon_fav"></span></span>{{ __('frontWords.favourites') }}</a></li>
                                                                <li><a href="javascript:;" class="add_to_queue" data-musicid="{{ $album->id }}" data-musictype="album"><span class="opt_icon"><span class="icon icon_queue"></span></span>{{ __('frontWords.add_to_queue') }}</a></li>
                                                                <li><a href="javascript:;" class="ms_share_music" data-shareuri="{{ url('images/album/'.$album->image) }}" data-sharename="{{ $album->album_name }} "><span class="opt_icon"><span class="icon icon_share"></span></span>{{ __('frontWords.share') }}</a></li>
                                                            </ul>
                                                            <div class="ms_play_icon play_btn play_music" data-musicid="{{ $album->id }}" data-musictype="album" data-url="{{ url('/songs') }}">
                                                                <img src="{{ asset('public/assets/images/svg/play.svg') }}" alt="">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="ms_rcnt_box_text">
                                                        <h3><a href="{{ url('album/single/'.$album->id.'/'.$album->album_slug) }}">{{ $album->album_name }}</a></h3>
                                                        <p>{{ ($artist_name != '' ? rtrim($artist_name,', ') : 'Unknown') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                            @php
                                }
                                    }else{
                                        echo '<div class="col-lg-12"><div class="ms_empty_data">
                                        <p>'.__('frontWords.no_album_with_title').' "'.$search.'"</p>
                                    </div></div>';
                                    }
                            @endphp
                        </div>
                    </div>
                </div>
            
            @php } @endphp
            
            
            @php
                if(sizeof($audios) > 0){
            @endphp    
                <div class="ms_top_artist">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ms_heading">
                                <h1>{{ __('frontWords.track') }}</h1>
                            </div>
                        </div>
                        @php
                            if(sizeof($audios) > 0){
                                foreach($audios as $audio){
                                    $getArtist = json_decode($audio->artist_id);
                                    $artist_name = '';
                                    foreach($getArtist as $artistName){
                                        $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistName] ]);
                                        if(count($artists) > 0){
                                            $artist_name .= $artists[0]->artist_name.', ';
                                        }
                                    }
                        @endphp
                                        <div class="col-lg-2 col-md-6">
                                            <div class="ms_rcnt_box marger_bottom30">
                                                <div class="ms_rcnt_box_img">
                                                    @if($audio->image != '' && file_exists(public_path('images/audio/thumb/'.$audio->image)))
                                                        <img src="{{ asset('public/images/audio/thumb/'.$audio->image) }}" alt="" class="img-fluid">
                                                    @else
                                                        <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                                                    @endif
                                                    
                                                    <div class="ms_main_overlay">
                                                        <div class="ms_box_overlay"></div>
                                                        <div class="ms_more_icon">
                                                            <img src="{{ asset('public/assets/images/svg/more.svg') }}" alt="">
                                                        </div>
                                                        <ul class="more_option">
                                                            <li><a href="javascript:;" class="addToFavourite" data-favourite="{{ $audio->id }}" data-type="audio"><span class="opt_icon"><span class="icon icon_fav"></span></span>{{ __('frontWords.favourites') }}</a></li>
                                                            <li><a href="javascript:;" class="add_to_queue" data-musicid="{{ $audio->id }}" data-musictype="audio"><span class="opt_icon"><span class="icon icon_queue"></span></span>{{ __('frontWords.add_to_queue') }}</a></li>

                                                            @php
                                                                if(!empty($userPlan) && $userPlan->is_download == 1){
                                                            @endphp
                                                                <li><a href="javascript:;" class="download_track" data-musicid="{{ $audio->id }}"><span class="opt_icon"><span class="icon icon_dwn"></span></span>{{ __('frontWords.download_now') }}</a></li>
                                                            @php } @endphp

                                                            <li><a href="javascript:;" class="ms_add_playlist" data-musicid="{{ $audio->id }}"><span class="opt_icon"><span class="icon icon_playlst"></span></span>{{ __('frontWords.add_to_playlist') }}</a></li>  

                                                            <li><a href="javascript:;" class="ms_share_music" data-shareuri="{{ url('images/audio/thumb/'.$audio->image) }}" data-sharename="{{ $audio->audio_title }} "><span class="opt_icon"><span class="icon icon_share"></span></span>{{ __('frontWords.share') }}</a></li>
                                                        </ul>
                                                        <div class="ms_play_icon play_btn play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                                            <img src="{{ asset('public/assets/images/svg/play.svg') }}" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ms_rcnt_box_text">
                                                    <h3><a href="{{ url('audio/single/'.$audio->id.'/'.$audio->audio_slug) }}">{{ $audio->audio_title }}</a></h3>
                                                    <p>{{ ($artist_name != '' ? rtrim($artist_name,', ') : 'Unknown') }}</p>
                                                </div>
                                            </div>
                                        </div>
                        @php
                            }
                                }else{
                                    echo '<div class="col-lg-12"><div class="ms_empty_data">
                                    <p>'.__('frontWords.no_album_with_title').' "'.$search.'"</p>
                                </div></div>';
                                }
                        @endphp
                    </div>
                </div>
            </div>
            @php } @endphp
            
            @php
                if(sizeof($artistData) > 0){
            @endphp
                <div class="ms_top_artist">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ms_heading">
                                <h1>{{ __('frontWords.artist') }}</h1>
                            </div>
                        </div>
                        @php
                            if(sizeof($artistData) > 0){
                                foreach($artistData as $artist){
                        @endphp
                            <div class="col-lg-2 col-md-6">
                                <div class="ms_rcnt_box marger_bottom30">
                                    <div class="ms_rcnt_box_img">
                                        @if($artist->image != '' && file_exists(public_path('images/artist/'.$artist->image)))
                                            <img src="{{ asset('public/images/artist/'.$artist->image) }}" alt="" class="img-fluid">
                                        @else
                                            <img src="{{ dummyImage('artist') }}" alt="" class="img-fluid">
                                        @endif                                
                                        <div class="ms_main_overlay">
                                            <div class="ms_box_overlay"></div>
                                            <div class="ms_more_icon">
                                                <img src="{{ asset('public/assets/images/svg/more.svg') }}" alt="">
                                            </div>
                                            <ul class="more_option">
                                                <li><a href="javascript:;" class="addToFavourite" data-favourite="{{ $artist->id }}" data-type="artist"><span class="opt_icon"><span class="icon icon_fav"></span></span>{{ __('frontWords.favourites') }}</a></li>
                                                <li><a href="javascript:;" class="add_to_queue" data-musicid="{{ $artist->id }}" data-musictype="artist"><span class="opt_icon"><span class="icon icon_queue"></span></span>{{ __('frontWords.add_to_queue') }}</a></li>
                                                <li><a href="javascript:;" class="ms_share_music" data-shareuri="{{ url('images/artist'.$artist->image) }}" data-sharename="{{ $artist->artist_name }} "><span class="opt_icon"><span class="icon icon_share"></span></span>{{ __('frontWords.share') }}</a></li>
                                            </ul>
                                            <div class="ms_play_icon play_btn play_music" data-musicid="{{ $artist->id }}" data-musictype="artist" data-url="{{ url('/songs') }}">
                                                <img src="{{ asset('public/assets/images/svg/play.svg') }}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ms_rcnt_box_text">
                                        <h3><a href="{{ url('artist/single/'.$artist->id.'/'.$artist->artist_slug) }}">{{ $artist->artist_name }}</a></h3>
                                    </div>
                                </div>
                            </div>
                        @php
                            }
                                }else{
                                    echo '<div class="col-lg-12"><div class="ms_empty_data">
                                    <p>'.__('frontWords.no_artist_with_title').' "'.$search.'".</p>
                                </div></div>';
                                }
                        @endphp
                    </div>
                </div>
            </div>
            @php } @endphp
            
            @php if(sizeof($albums) <= 0 && sizeof($audios) <= 0 && sizeof($artistData) <= 0 && sizeof($ytBrowseSearch) <= 0) { @endphp
                <div class="col-lg-12"><div class="ms_empty_data"><p>{{ __('frontWords.no_video') }} </p></div></div> 
            @php } @endphp
        </div>    
    </div>
    
@include('layouts.front.footer')