@extends('layouts.front.main')
@section('title', __('frontWords.radio_single'))
@section('content')
@php 
    if(!empty($radio)){ 
        $getLikeDislikeRadio = getFavDataId(['column' => 'radio_id', 'radio_id' => $radio[0]->id]);
        $artist_name = get_artist_name(['radio_id'=>$radio[0]->id]);
        $song_list = [];
        if(!empty($radio[0]->song_list)){
            $song_list = json_decode($radio[0]->song_list);
            $duration = multiple_audio_duration(['list'=>$radio[0]->song_list,'add'=>1]);
        }
@endphp

    <div class="album_single_data">
        <div class="album_single_img">
            @if($radio->image != '' && file_exists(public_path('images/radio/'.$radio->image)))
                <img src="{{ asset('images/radio/'.$radio[0]->image) }}" alt="" class="img-fluid">
            @else
                <img src="{{ dummyImage('radio') }}" alt="" class="img-fluid">
            @endif            
        </div>
        <div class="album_single_text">
            <h2>{{ $radio[0]->radio_name }}</h2>
            <p class="singer_name">By - {{ $artist_name != '' ? rtrim($artist_name,',') : '' }}</p>
            <div class="album_feature">
                <a href="#" class="album_date">{{ (!empty($song_list)) ? sizeof($song_list) : 0 }} {{ __('frontWords.track') }} | {{ $duration }}</a>
                <a href="#" class="album_date">Released {{ date("F d, Y", strtotime($radio[0]->created_at)) }}</a>
            </div>
            <div class="album_btn">
                <a href="javascript:;" class="ms_btn play_btn play_music" data-musicid="{{ $radio[0]->id }}" data-musictype="radio" data-url="{{ url('/songs') }}"><span class="play_all"><img src="{{ asset('assets/images/svg/play_all.svg') }}" alt="">{{ __('frontWords.play_all') }}</span><span class="pause_all"><img src="{{ asset('assets/images/svg/pause_all.svg') }}" alt="">{{ __('frontWords.pause') }}</span></a>
            </div>
        </div>
        <div class="album_more_optn ms_more_icon">
            <span><img src="{{ asset('assets/images/svg/more.svg') }}" alt=""></span>
        </div>
        <ul class="more_option">
        <li><a href="javascript:;" class="addToFavourite" data-favourite="{{ $radio[0]->id }}" data-type="radio"><span class="opt_icon"><span class="icon {{ ($getLikeDislikeRadio == 1 ? 'icon_fav_add' : 'icon_fav') }}"></span></span>{{ __('frontWords.favourites') }}</a></li>
            <li><a href="#"><span class="opt_icon"><span class="icon icon_share"></span></span>{{ __('frontWords.share') }}</a></li>
        </ul>
    </div>
    
    <div class="album_inner_list">
        <div class="album_list_wrapper">
            <ul class="album_list_name">
                <li>#</li>
                <li>{{ __('frontWords.track_title') }}</li>
                <li>{{ __('frontWords.artist') }}</li>
                <li class="text-center">{{ __('frontWords.duration') }}</li>
                <li class="text-center">{{ __('frontWords.more') }}</li>
            </ul>
            @php 
                if(!empty($song_list)){
                    $cnt = 0;
                    foreach($song_list as $song){
                        $audioDetail = audioDetail(['songid' => $song]);
                        if(!empty($audioDetail)){
                            foreach($audioDetail as $audios){
                                $cnt++;
                                $getArtist = json_decode($audios->artist_id);
                                $artist_name = '';
                                foreach($getArtist as $artistName){
                                    $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistName] ]);
                                    if(count($artists) > 0){
                                        $artist_name .= $artists[0]->artist_name.',';
                                    }
                                }
                                $getLikeDislikeRadio = getFavDataId(['column' => 'audio_id', 'audio_id' => $audios->id]);
            @endphp
            <ul>
                <li><span class="play_no">{{ $cnt }}</span><span class="play_hover play_music" data-musicid="{{ $audios->id }}" data-musictype="audio" data-url="{{ url('/songs') }}"></span></li>
                <li><a href="{{ url('audio/single/'.$audios->id.'/'.$audios->audio_slug) }}">{{ $audios->audio_title }}</a></li>
                <li>{{ rtrim($artist_name,',') }}</li>
                <li class="text-center">{{ $audios->audio_duration }}</li>
                <li class="text-center ms_more_icon"><a href="javascript:;"><span class="ms_icon1 ms_active_icon"></span></a>
                    <ul class="more_option">
                        <li><a href="javascript:;" class="addToFavourite" data-favourite="{{ $audios->id }}" data-type="audio"><span class="opt_icon"><span class="icon {{ ($getLikeDislikeRadio == 1 ? 'icon_fav_add' : 'icon_fav') }}"></span></span>{{ __('frontWords.favourites') }}</a></li>
                        <li><a href="javascript:;" class="add_to_queue" data-musicid="{{ $audios->id }}" data-musictype="audio"><span class="opt_icon"><span class="icon icon_queue"></span></span>{{ __('frontWords.add_to_queue') }}</a></li>
                        @php
                            if(!empty($userPlan) && $userPlan->is_download == 1){
                                if($audios->aws_upload == 1){
                                    echo  '<li><a href="'.getSongAWSUrlHtml($audios).'"><span class="opt_icon"><span class="icon icon_dwn"></span></span>'.__("frontWords.download_now").'</a></li>';
                                }else{
                                    echo '<li><a href="javascript:;" class="download_track" data-musicid="'.$audios->id.'"><span class="opt_icon"><span class="icon icon_dwn"></span></span>'.__('frontWords.download_now').'</a></li>';
                                }
                            }
                        @endphp
                        <li><a href="javascript:;" class="ms_add_playlist" data-musicid="{{ $audios->id }}"><span class="opt_icon"><span class="icon icon_playlst"></span></span>{{ __('frontWords.add_to_playlist') }}</a></li>
                        <li><a href="javascript:;" class="ms_share_music" data-shareuri="{{ url('images/audio/'.$audios->audio) }}" data-sharename="{{ $audios->title }}"><span class="opt_icon"><span class="icon icon_share"></span></span>{{ __('frontWords.share') }}</a></li>
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

@php
    }
@endphp

@include('layouts.front.footer')
</div>
@endsection