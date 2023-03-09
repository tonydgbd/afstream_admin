@inject('album', 'Modules\Album\Entities\Album')
    <!---Artist page--->
    <div class="ms_artist_wrapper common_pages_space">
        <div class="ms_artist_inner">
            
                <!-- Top Albums section -->
                <div class="ms_artist_slider top_album_slider">
                    <div class="slider_heading_wrap">
                        <div class="slider_cheading no-border">
                            <h4 class="cheading_title">{{ __('frontWords.featured').' '.__('frontWords.album') }} &nbsp;</h4>
                        </div>
                    @php
                        $featuredAlbum = $album->where(['is_featured' => '1','status' => '1'])->get()->toArray();
                        if(!empty($featuredAlbum)){ 
                    @endphp
                        </div>
                    @php
                        if(sizeof($albums) > 0){
                    @endphp
                        <div class="ms_artist_innerslider">
                            <div class="row custom-grid">
                                    @php
                                        foreach($albums as $album){
                                            if($album->is_featured == 1){
                                                $artist_name = get_artist_name(['album_id'=>$album->id]);
                                                $getLikeDislikeAlbum = getFavDataId(['column' => 'album_id', 'album_id' => $album->id]);
                                    @endphp
                                    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6  mb-4 play_btn play_icon_btn">
                                        <div class="slider_cbox slider_artist_box text-center play_box_container mb-20">
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
                                                <a href="javascript:void(0);" class="slider_ctitle slider_artist_ttl limited_text_line getAjaxRecord" data-type="album" data-url="{{ url('album/single/'.$album->id.'/'.$album->album_slug) }}" href="javascript:void(0)">{{ $album->album_name }}</a>
                                            </div>
                                        </div>
                                    </div>   
                                    @php   }
                                        }
                                    @endphp                     
                                </div>
                        </div>
                    @php } }else{ @endphp
                            </div>
                                <div class="ms_empty_data">
                                    <p> {{ __("frontWords.no_featured_album") }}. </p>
                                </div>
                      @php } @endphp
                </div>
                
                
                <!-- Recommended Albums section -->
                <div class="ms_artist_slider recommended_album_slider">
                    <div class="slider_heading_wrap">
                        <div class="slider_cheading no-border">
                            <h4 class="cheading_title">{{ __("frontWords.trending").' '.__("frontWords.album") }} &nbsp;</h4>
                        </div>
                    @php
                        $trendingAlbum = $album->where(['is_trending' => '1','status' => '1'])->get()->toArray();
                        if(!empty($trendingAlbum)){
                    @endphp
                        </div>
                    @php
                        if(sizeof($albums) > 0){
                    @endphp
                        <div class="ms_artist_innerslider"> 
                            <div class="row custom-grid">
                                @php
                                    foreach($albums as $album){
                                        if($album->is_trending == 1){
                                            $artist_name = get_artist_name(['album_id'=>$album->id]);
                                            $getLikeDislikeAlbum = getFavDataId(['column' => 'album_id', 'album_id' => $album->id]);
                                @endphp
                                    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6  mb-4 play_btn play_icon_btn">
                                        <div class="slider_cbox slider_artist_box text-center play_box_container mb-20">
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
                                @php   }
                                    }
                                @endphp
                            </div>
                        </div>
                    @php } }else{ @endphp
                        </div>
                            <div class="ms_empty_data">
                                <p>{{ __('frontWords.no_trending_album') }}.</p>
                            </div>
                    @php } @endphp
                </div>
        </div>
    </div>

@include('layouts.front.footer')
      