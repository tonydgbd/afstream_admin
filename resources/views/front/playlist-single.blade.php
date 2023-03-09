@extends('layouts.front.main')
@section('title', __('frontWords.playlist_single'))
@inject('ytVideo', 'Alaouy\Youtube\Facades\Youtube')

@section('content')
    
<div class="musiooFrontSinglePage">
    <div class="fw-col-xs-12">
    	<div class="ms_artist_wrapper common_pages_space">
            <div class="ms_heading">
                <h1>{{ (!empty($getPlaylist) && $getPlaylist[0]->playlist_name != '' ? $getPlaylist[0]->playlist_name : '') }}</h1>
            </div>            

            <div class="album_list_wrapper">
                <ul class="album_list_name">  
                    <li>#</li>
                    <li>{{ __('frontWords.track_title') }}</li>
                    <li>{{ __('frontWords.artist') }}</li>
                    <li class="text-center">{{ __('frontWords.duration') }}</li>
                    <li class="text-center">{{ __('frontWords.more') }}</li>
                    <li class="text-center">{{ __('frontWords.remove') }}</li>
                </ul>
                @php 
                    if(!empty($getPlaylist)){
                        $getSongId = json_decode($getPlaylist[0]->song_list);
                        if(!empty($getSongId)){
                            $audioArr = [];
                            $cnt=0;
                            foreach($getSongId as $songid){
                                $cnt++;
                                $getAudio = audioDetail(['songid' => $songid]);
                                if(!empty($getAudio)){
                                    foreach($getAudio as $audio){
                                        $getArtist = json_decode($audio->artist_id);
                                        $artist_name = '';
                                        foreach($getArtist as $artistName){
                                            $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistName] ]);
                                            if(count($artists) > 0){
                                                $artist_name .= $artists[0]->artist_name.',';
                                            }
                                        }
                                        $getLikeDislikeAudio = getFavDataId(['column' => 'audio_id', 'audio_id' => $audio->id]);
                                        
                        @endphp
                        <ul class="ms_list_songs">
                            <li class="play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                <span class="play_no">{{ $cnt }}</span>
                                <span class="play_hover">
                                    <img src="{{ asset('public/images/svg/play_songlist.svg') }}" alt="Play" class="img-fluid list_play">
                                    <img src="{{ asset('public/images/svg/sound_bars.svg') }}" alt="bar" class="img-fluid list_play_bar">  
                                </span>
                            </li>
                            <!--<li><span class="play_no">{{ $cnt }}</span><span class="play_hover play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}"></span></li>-->
                            <li class="play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">{{ $audio->audio_title }}</li>
                            <li class="play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">{{ rtrim($artist_name,',') }}</li>
                            <li class="text-center">{{ $audio->audio_duration }}</li>
                            <li class="list_more">
                                    <a href="javascript:void(0);" class="songslist_moreicon">
                                        <span >
                                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.104 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.104 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg>
                                        </span>
                                    </a>
                                    <ul class="ms_common_dropdown ms_downlod_list">
                                        <li>
                                            <a href="javascript:void(0);" class="addToFavourite" data-favourite="{{ $audio->id }}" data-type="audio">
                                                <span class="common_drop_icon drop_fav"></span>{{ __('frontWords.favourites') }}
                                            </a>
                                        </li>
                                        @php
                                            if(!empty($userPlan) && $userPlan->is_download == 1){
                                                if($audio->aws_upload == 1){
                                                    echo  '<li><a href="'.getSongAWSUrlHtml($audio).'"><span class="common_drop_icon drop_downld"></span>'.__("frontWords.download_now").'</a></li>';
                                                }else{
                                                    echo '<li><a href="javascript:void(0);" class="download_track" data-musicid="'.$audio->id.'"><span class="common_drop_icon drop_downld"></span>'.__('frontWords.download_now').'</a></li>';
                                                }
                                            }
                                        @endphp                                       
                                        <li><a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $audio->id }}" data-musictype="audio"><span class="common_drop_icon drop_downld"></span>{{ __('frontWords.add_to_queue') }}</a></li>
                                        <li>
                                            <a href="javascript:void(0);" class="ms_add_playlist" data-musicid="{{ $audio->id }}">
                                                <span class="common_drop_icon drop_playlist"></span>{{ __('frontWords.add_to_playlist') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('images/audio/'.$audio->audio) }}" data-sharename="{{ $audio->title }}">
                                                <span class="common_drop_icon drop_share"></span>{{ __('frontWords.share') }}
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
                            
                            <li class="text-center"> 
                                <a href="javascript:void(0);" class="remove_user_playlist_music" musicid="{{ $audio->id }}" data-list-id="{{ ((!empty($getPlaylist) ? $getPlaylist[0]->id : '')) }}">
                                    <span class="ms_close">
                                        <img src="{{ asset('public/assets/images/svg/close.svg') }}" alt="Close">
                                    </span>
                                </a>
                            </li>
                        </ul>
                    @php
                    }
                        }
                            }
                                }
                                    }
                    @endphp
               
            </div>
        </div>
        
        @if(isset($is_youtube) && !empty($is_youtube) && $is_youtube == 1)   

            @php 
                if(!empty($getPlaylist)){
                    $getVideoId = json_decode($getPlaylist[0]->video_list);                    
                }
            @endphp
            <!-- Playlist Video Start -->
            @if(isset($getVideoId) && !empty($getVideoId))

                <div class="ms_top_artist">
                    <div class="col-lg-12"> 
                        <div class="ms_heading">
                            <h1>{{ __("adminWords.youtube").' '.__("frontWords.playlist").' '.__("frontWords.videos") }}</h1>    
                        </div>
                    </div>

                    <div class="container-fluid ms_track_slides">
                        <div class="row">
                            @foreach($getVideoId as $ytId)

                                @php
                                    $ytImage = '';   
                                    $videoId = '';

                                    $ytVideo = getSingleYtVideoById($ytId);
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
                                @endphp

                                @if(!empty($videoId))
                                    <div class="col-lg-2 col-md-6 playlistYtVideo">
                                        <div class="ms_rcnt_box marger_bottom30">
                                            <div class="ms_rcnt_box_img">                                        
                                                
                                                @if($ytVideo->snippet->thumbnails != '' )
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
                                                                    <a href="javascript:void(0);" class="remove_user_playlist_music" musicid="{{ $videoId }}" data-list-id="{{ ((!empty($getPlaylist) ? $getPlaylist[0]->id : '')) }}" musictype="ms_video">

                                                                        <span class="common_drop_icon drop_playlist"></span>
                                                                        {{ __("frontWords.remove") }} 
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a href="javascript:void(0);" class="add_to_yt_queue" data-musicid="{{ $videoId }}">
                                                                    <span class="opt_icon" title="Add To Queue"><span class="icon icon_queue"></span></span>
                                                                    {{ __("frontWords.add_to_queue") }}
                                                                    </a>
                                                                </li>                                                                
                                                                
                                                            </ul>                                   
                                                        </li>
                                                    
                                                    </ul>
                                                </div>


                                                <div class="ms_main_overlay">
                                                    <div class="ms_box_overlay"></div>   
                                                    <div class="ms_play_icon play_btn yt_music" data-musicid="{{ $videoId }}" data-title="{{ $ytVideo->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">
                                                        <img src="{{ asset('public/assets/images/svg/play.svg') }}" alt="">
                                                    </div>
                                                </div>                                            
                                            </div>
                                            
                                            <div class="ms_rcnt_box_text">
                                                <h3>
                                                    <a href="javascript:void(0)" class="yt_music" data-musicid="{{ $videoId }}" data-title="{{ $ytVideo->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">
                                                        {{ $ytVideo->snippet->title }}
                                                    </a>
                                                </h3>
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                                      
            @endif
            <!-- Playlist Video End -->

        @endif
        
    </div>
    @include('layouts.front.footer')
</div>
@endsection