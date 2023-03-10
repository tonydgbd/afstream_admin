    
    @inject('audio', 'Modules\Audio\Entities\Audio')
    @php
        if(isset($defaultCurrency->symbol) && !empty($defaultCurrency->symbol)){
            $curr = $defaultCurrency->symbol; 
        }elseif((session()->get('currency')['symbol']) && !empty(session()->get('currency')['symbol'])){
            $curr = session()->get('currency')['symbol'];
        }
    @endphp
    <div class="ms_history_wrapper common_pages_space">
        <div class="ms_history_inner"> 
        
            <div class="ms_free_download">
                <div class="ms_heading">
                    <h1>  {{ __('frontWords.download_history') }}</h1>
                </div>
                <div class="album_inner_list">
                    <div class="album_list_wrapper">
                        <ul class="album_list_name">
                            <li>#</li>
                            <li class="text-center">{{ __('frontWords.track_title') }}</li>
                            <li class="text-center">{{ __('frontWords.artist') }}</li>
                            <li class="text-center">{{ __('frontWords.duration') }}</li>
                            <li class="text-center">{{ __('adminWords.price') }}</li>
                            <li class="text-center">{{ __('adminWords.download_count') }} </li>
                            <li class="text-center">{{ __('frontWords.favourites') }}</li>
                            <li class="text-center">{{ __('frontWords.more') }}</li>
                        </ul>
                        
                            @php $i = 1; @endphp
                            @forelse($audioDownloadHistory as $downloadHistory)
                                <ul>
                                    @php
                                        $audioDetail = $audio->where('id',$downloadHistory['audio_id'])->first();
                                        $getArtist = json_decode($audioDetail->artist_id); 
                                        $artist_name = '';
                                        if(sizeof($getArtist) > 0){
                                            foreach($getArtist as $artistName){
                                                $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistName] ]);
                                                if(count($artists) > 0){
                                                    $artist_name .= $artists[0]->artist_name.', ';
                                                }
                                            }
                                        }
                                    @endphp
                                    @if(!empty($audioDetail))
                                        <li class="play_music" data-musicid="{{ $audioDetail->id }}" data-musictype="audio" data-url="{{ url('/songs') }}"><span class="play_no">{{ $i++ }}</span>
                                            <span class="play_hover">
                                                <img src="{{ asset('images/svg/play_songlist.svg') }}" alt="Play" class="img-fluid list_play">
                                                <img src="{{ asset('images/svg/sound_bars.svg') }}" alt="bar" class="img-fluid list_play_bar">  
                                            </span>
                                        </li>
                                        <li class="text-center">{{ $audioDetail->audio_title }}</li>
                                        <li class="text-center">{{ $artist_name }}</li>
                                        <li class="text-center"> {{ $audioDetail->audio_duration }} </li>
                                        <li class="text-center"> 
                                            @if(isset($audioDetail->download_price) && !empty($audioDetail->download_price))
                                                {{ $curr.$audioDetail->download_price }} 
                                            @else
                                                {{ __('adminWords.by_plan') }}
                                            @endif
                                        </li>
                                        <li class="text-center">{{ $downloadHistory['download_count'] }}</li>
                                        
                                        <li class="text-center">
                                            
                                            <span class="list_heart addToFavourite" data-favourite="{{ $audioDetail->id }}" data-type="audio">
                                                @php
                                                    $getData = \App\Favourite::where(['user_id'=> auth()->user()->id])->first();
                                                    
                                                        if(!empty($getData)){
                                                            $decodeIds = $getData->audio_id;  
                                                            if($decodeIds != '' && !empty($decodeIds)){
                                                                $dataId = json_decode($decodeIds);
                                                                if( in_array($audioDetail->id, $dataId) ) {                                                    
                                                @endphp                    
                                                                    <svg width='19px' height='19px' id='Layer_1' data-name='Layer 1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 391.8 391.8'><defs><style>.cls-1{fill:#d80027;}</style></defs><path class='cls-1' d='M280.6,43.8A101.66,101.66,0,0,1,381.7,144.9c0,102-185.8,203.1-185.8,203.1S10.2,245.5,10.2,144.9A101.08,101.08,0,0,1,111.3,43.8h0A99.84,99.84,0,0,1,196,89.4,101.12,101.12,0,0,1,280.6,43.8Z'></path></svg>
                                                @php            }else{    
                                                @endphp
                                                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>
                                                @php            }
                                                            }                
                                                        }
                                                @endphp
                                               
                                            </span>
                                        </li>                                
                                        <li class="list_more">
                                            <a href="javascript:void(0);" class="songslist_moreicon">
                                                <span >
                                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.104 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.104 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg>
                                                </span>
                                            </a>
                                            <ul class="ms_common_dropdown ms_downlod_list">                                                                       
                                                <li>
                                                    
                                                    <a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $audioDetail->id }}" data-musictype="audio">
                                                    <span class="opt_icon" title="Add To Queue"><span class="icon icon_queue"></span></span>
                                                    {{ __("frontWords.add_to_queue") }}
                                                    </a>
                                                </li>
                                                
                                                <li>
                                                    <a href="javascript:void(0);" class="ms_add_playlist" data-musicid="{{ $audioDetail->id }}">
                                                        <span class="common_drop_icon drop_playlist"></span>{{ __('frontWords.add_to_playlist') }}
                                                    </a>
                                                </li>
                                                
                                                <li>
                                                    <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('audio/single/'.$audioDetail->id.'/'.$audioDetail->audio_slug) }}" data-sharename="{{ $audioDetail->title }}">
                                                        <span class="common_drop_icon drop_share"></span>{{ __('frontWords.share') }}
                                                    </a>
                                                </li>
                                                
                                            </ul>                                   
                                        </li> 
                                        
                                    @endif
                                </ul>
                            @empty
                                <ul>
                                    <li class="text-center">{{ __('adminWords.no_data') }}</li>
                                </ul>
                            @endforelse
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>    

     @include('layouts.front.footer')