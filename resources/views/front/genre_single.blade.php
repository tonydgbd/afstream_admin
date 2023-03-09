@extends('layouts.front.main')
@section('title', __('frontWords.genre_single'))
@section('content')
    <div class="musiooFrontSinglePage">
        <div class="ms_artist_wrapper common_pages_space">
            <div class="album_single_data">
                @php
                    if(!empty($genres)){
                @endphp
                    <div class="album_single_img">
                        @if($genres->image != '' && file_exists(public_path('images/audio/audio_genre/'.$genres->image)))
                            <img src="{{ asset('public/images/audio/audio_genre/'.$genres->image) }}" alt="" class="img-fluid">
                        @else
                            <img src="{{ dummyImage('genres') }}" alt="" class="img-fluid">
                        @endif
                         
                    </div>
                    <div class="album_single_text">
                        <p class="singer_name">{{ $genres->genre_name }}</p>
                        <div class="album_feature">
                            <a href="#" class="album_date">{{ __('frontWords.created_at') }} - {{ date("F d, Y", strtotime($genres->created_at)) }}</a>
                        </div>
                        <div class="album_btn">
                            <a href="#" class="ms_btn play_btn play_music" data-musicid="{{ $genres->id }}" data-musictype="genre" data-url="{{ url('/songs') }}"><span class="play_all"><img src="{{ asset('public/assets/images/svg/play_all.svg') }}" alt="">{{ __('frontWords.play_all') }}</span><span class="pause_all"><img src="{{ asset('public/assets/images/svg/pause_all.svg') }}" alt="">{{ __('frontWords.pause') }}</span></a>
                            <a href="javascript:;" class="ms_btn add_to_queue" data-musicid="{{ $genres->id }}" data-musictype="genre"><span class="play_all"><img src="{{ asset('public/assets/images/svg/add_q.svg') }}" alt="">{{ __('frontWords.add_to_queue') }}</span></a>
                        </div>
                    </div>
                    
                    <div class="album_more_optn list_more">
                    <ul>
                        <li class="list_more">
                            <a href="javascript:void(0);" class="songslist_moreicon">
                                <span >
                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.104 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.104 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg>
                                </span>
                            </a>
                            <ul class="ms_common_dropdown ms_downlod_list list_more">     
                                @if(!empty(auth()->user()->id))
                                    <li class="favourite_icon">
                                    <a href="javascript:void(0);" class="addToFavourite" data-favourite="{{ $genres->id }}" data-type="genre">
                                        @php
                                            $getData = \App\Favourite::where(['user_id'=> auth()->user()->id])->first();
                                            
                                                if(!empty($getData)){
                                                    $decodeIds = $getData->genre_id;  
                                                    if($decodeIds != '' && !empty($decodeIds)){
                                                        $dataId = json_decode($decodeIds);
                                                        if( in_array($genres->id, $dataId) ) {                                                    
                                        @endphp                    
                                                            <svg width='19px' height='19px' id='Layer_1' data-name='Layer 1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 391.8 391.8'><defs><style>.cls-1{fill:#d80027;}</style></defs><path class='cls-1' d='M280.6,43.8A101.66,101.66,0,0,1,381.7,144.9c0,102-185.8,203.1-185.8,203.1S10.2,245.5,10.2,144.9A101.08,101.08,0,0,1,111.3,43.8h0A99.84,99.84,0,0,1,196,89.4,101.12,101.12,0,0,1,280.6,43.8Z'></path></svg>
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
                                @endif   
                                
                                <li>
                                    <a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $genres->id }}" data-musictype="genre">
                                        <span class="opt_icon" title="Add To Queue"><span class="icon icon_queue"></span></span>
                                        {{ __("frontWords.add_to_queue") }}
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('genre/single/'.$genres->id.'/'.$genres->genre_slug) }}" data-sharename="{{ $genres->genre_name }}">
                                        <span class="common_drop_icon drop_share"></span>{{ __('frontWords.share') }}
                                    </a>
                                </li>
    
                            </ul>                                   
                        </li>
                    
                    </ul>
                </div>
                @php
                    }   
                @endphp    
            </div>
        </div>

        <div class="col-lg-12">
        	<div class="ms_artist_wrapper common_pages_space">
                <div class="ms_heading">
                    <h1>{{ (!empty($genres) && $genres->genre_name != '' ? $genres->genre_name : '') }}</h1>
                </div>

                <div class="album_list_wrapper">
                    <ul class="album_list_name"> 
                        <li>#</li>
                        <li>{{ __('frontWords.track_title') }}</li>
                        <li>{{ __('frontWords.artist') }}</li>
                        <li class="text-center">{{ __('frontWords.duration') }}</li>
                        <li class="text-center">{{ __('frontWords.more') }}</li>
                    </ul>
                    @php
                        if(sizeof($audioData) > 0){
                            $idArr = [];
                            $cnt = 0;
                            foreach($audioData as $audio){  
                                $cnt++;
                                $getAudioDetail = select(['column' => '*', 'table' => 'audio', 'where' => ['id'=>$audio->id] ]);
                                if(!empty($getAudioDetail)){
                                    foreach($getAudioDetail as $audios){ 
                                        $getArtist = json_decode($audios->artist_id);
                                        $artist_name = '';
                                        foreach($getArtist as $artistid){
                                            $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistid] ]);
                                            if(count($artists) > 0){
                                                $artist_name .= $artists[0]->artist_name.', ';
                                            }
                                        }
                                        $getLikeDislikeAudio = getFavDataId(['column' => 'audio_id', 'audio_id' => $audios->id]);
                                    @endphp
                                        <ul class="play_music" data-musicid="{{ $audios->id }}" data-musictype="audio" data-url="{{ url('/songs') }}"> 
                                            <li><span class="play_no">{{ $cnt }}</span>
                                                <span class="play_hover">
                                                    <img src="{{ asset('public/images/svg/play_songlist.svg') }}" alt="Play" class="img-fluid list_play">
                                                    <img src="{{ asset('public/images/svg/sound_bars.svg') }}" alt="bar" class="img-fluid list_play_bar">  
                                                </span>
                                            </li>
                                            <li><a href="{{ url('audio/single/'.$audios->id.'/'.$audios->audio_slug) }}">{{ $audios->audio_title }}</a></li>
                                            <li>{{ rtrim($artist_name,', ') }}</li>
                                            <li class="text-center">{{ $audios->audio_duration }}</li>
                                            
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
                                                        <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('audio/single/'.$audios->id.'/'.$audios->audio_slug) }}" data-sharename="{{ $audios->audio_title }}">
                                                            <span class="common_drop_icon drop_share"></span>{{ __('frontWords.share') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        @if(isset($audios->download_price) && !empty($audios->download_price))
                                                            <input type="hidden" class="getAudioAmountToDownload" value="{{ $audios->download_price }}">
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
                    @endphp
                </div>                
            </div>
        </div>
        @include('layouts.front.footer')
    </div>
@endsection 
