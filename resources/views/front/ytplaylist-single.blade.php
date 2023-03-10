@extends('layouts.front.main')
@section('title', __('frontWords.playlist_single'))
@section('content')

<div class="musiooFrontSinglePage">
    <div class="fw-col-xs-12">
    	<div class="ms_artist_wrapper common_pages_space">
            <div class="ms_heading">
                <h1>{{ __("adminWords.youtube").' '.__("frontWords.playlist").' '.__("frontWords.video") }}</h1>
            </div>            

            <div class="container-fluid ms_track_slides">
                <div class="row">
                            
                    @if(!empty($is_youtube) && $is_youtube == 1 && $playlistItems != '')
                        @php                            
                            $ytImage = '';    
                            $videoId = '';

                            foreach($playlistItems as $music){
                                
                                if(isset($music->snippet->thumbnails->medium->url) && !empty($music->snippet->thumbnails->medium->url)){
                                    $ytImage = $music->snippet->thumbnails->medium->url;
                                }elseif(isset($music->snippet->thumbnails->high->url) && $music->snippet->thumbnails->high->url){
                                    $ytImage = $music->snippet->thumbnails->high->url;
                                }elseif(isset($music->snippet->thumbnails->default->url) && !empty($music->snippet->thumbnails->default->url)){
                                    $ytImage = $music->snippet->thumbnails->default->url;
                                }else{ $ytImage = 'images/yt_music.webp'; }

                                if(isset($music->snippet->resourceId->videoId) && !empty($music->snippet->resourceId->videoId)){
                                    $videoId = $music->snippet->resourceId->videoId;
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
                                            <div class="ms_main_overlay">
                                                <div class="ms_box_overlay"></div>                                                             
                                                <div class="ms_play_icon play_btn yt_music" data-musicid="{{ $videoId }}" data-title="{{ $music->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">
                                                    <img src="{{ asset('assets/images/svg/play.svg') }}" alt="">
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
                        @php }  @endphp
                    @else
                        <div class="col-lg-12"><div class="ms_empty_data"><p>{{ __('frontWords.no_video') }} </p></div></div>     
                    @endif
                </div>
            </div>
        </div>
        
    </div>
    @include('layouts.front.footer')
</div>
@endsection