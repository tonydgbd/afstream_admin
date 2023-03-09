            
    <!---index page--->
    <div class="ms_index_wrapper common_pages_space">
        <div class="ms_index_inner">
            <div class="ms_index_secwrap">
                <div class="ms_songslist_main">
                    <div class="ms_songslist_wrap">
                        <ul class="ms_songslist_nav nav nav-pills" role="tablist">
                            <li>
                                <a class="active" data-toggle="pill" href="#top-picks" role="tab" aria-controls="top-picks" aria-selected="true">
                                    {{ __("frontWords.todays_top") }}
                                </a>
                            </li>
                            <li>
                                <a class="" data-toggle="pill" href="#trending-songs" role="tab" aria-controls="trending-songs" aria-selected="false">
                                    {{ __("frontWords.trending_songs") }}
                                </a>
                            </li>
                            <li>
                                <a class="" data-toggle="pill" href="#new-release" role="tab" aria-controls="new-release" aria-selected="false">
                                    {{ __("frontWords.new_release") }}
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="top-picks" role="tabpanel" aria-labelledby="top-picks">
                                <div class="ms_songslist_box">
                                    <ul class="ms_songlist ms_index_songlist">

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
                            <div class="tab-pane fade" id="trending-songs" role="tabpanel" aria-labelledby="trending-songs">
                                <div class="ms_songslist_box">
                                    <ul class="ms_songlist ms_index_songlist">

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
                            <div class="tab-pane fade" id="new-release" role="tabpanel" aria-labelledby="new-release">
                                <div class="ms_songslist_box">
                                    <ul class="ms_songlist ms_index_songlist">
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
                </div>
                <!--Put Admin Added Playlist Here-->
            </div>
            
        </div>
    </div>
