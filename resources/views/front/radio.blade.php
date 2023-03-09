@extends('layouts.front.main')
@section('title', __('frontWords.radio'))
@section('content')

<div class="ms_top_artist">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="ms_heading">
                    <h1>{{ __('frontWords.live_radio') }}</h1>
                </div>
            </div>
            @php
                if(sizeof($radios)){
                    foreach($radios as $radio){
                        $artist_name = get_artist_name(['radio_id'=>$radio->id]);
            @endphp
            <div class="col-lg-2 col-md-6">
                <div class="ms_rcnt_box marger_bottom30">
                    <div class="ms_rcnt_box_img">
                        @if($radio->image != '' && file_exists(public_path('images/radio/'.$radio->image)))
                            <img src="{{ asset('images/radio/'.$radio->image) }}" alt="" class="img-fluid">
                        @else
                            <img src="{{ dummyImage('radio') }}" alt="" class="img-fluid">
                        @endif
                        <div class="ms_main_overlay">
                            <div class="ms_box_overlay"></div>
                            <div class="ms_more_icon">
                                <img src="{{ asset('public/assets/images/svg/more.svg') }}" alt="">
                            </div>
                            <ul class="more_option">
                                <li><a href="javascript:;" class="addToFavourite" data-favourite="{{ $radio->id }}" data-type="radio"><span class="opt_icon"><span class="icon icon_fav"></span></span>{{ __('frontWords.favourites') }}</a></li>
                                <li><a href="javascript:;" class="add_to_queue" data-musicid="{{ $radio->id }}" data-musictype="radio"><span class="opt_icon"><span class="icon icon_queue"></span></span>{{ __('frontWords.add_to_queue') }}</a></li>
                                <li><a href="javascript:;" class="ms_share_music" data-shareuri="{{ url('images/radio/'.$radio->image) }}" data-sharename="{{ $radio->radio_name }} "><span class="opt_icon"><span class="icon icon_share"></span></span>{{ __('frontWords.share') }}</a></li>
                            </ul>
                            <div class="ms_play_icon play_btn play_icon_btn  play_music" data-musicid="{{ $radio->id }}" data-musictype="radio" data-url="{{ url('/songs') }}">
                                <img src="{{ asset('public/assets/images/svg/play.svg') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="ms_rcnt_box_text">
                        <h3><a href="{{ url('radio/single/'.$radio->id.'/'.$radio->radio_slug) }}">{{ $radio->radio_name }}</a></h3>
                    </div>
                </div>
            </div>
            @php
                }
                    }else{
                        echo '<div class="col-lg-12"><div class="ms_empty_data">
                        <p>'.__("frontWords.no_radio").'</p>
                    </div></div>';
                    }
            @endphp
        </div>
    </div>
</div>
    @include('layouts.front.footer')
</div>
@endsection