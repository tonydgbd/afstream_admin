@extends('layouts.front.main')
@section('title', __('adminWords.genre'))
@section('content')

<!---Artist page--->
    <div class="ms_artist_wrapper common_pages_space">
        <div class="ms_artist_inner">
            
                <!-- Top Genres section -->
                <div class="ms_artist_slider top_album_slider">
                    <div class="slider_heading_wrap">
                        <div class="slider_cheading no-border">
                            <h4 class="cheading_title">{{ __('adminWords.genre') }} &nbsp;</h4>
                        </div>
                        <!-- Add Arrows -->
                        <div class="slider_cmn_controls">
                            <div class="slider_cmn_nav "><span class="swiper-button-next1 slider_nav_next"></span></div>
                            <div class="slider_cmn_nav"><span class="swiper-button-prev1 slider_nav_prev"></span></div>
                        </div>
                    </div>
                    @php
                        if(sizeof($genres) > 0){
                    @endphp
                        <div class="ms_artist_innerslider">
                            <div class="row custom-grid">
                                    @php
                                    if(sizeof($genres) > 0){
                                        $html = '';
                                        foreach($genres as $genre) {
                                            if($genre->image != '' && file_exists(public_path('images/audio/audio_genre/'.$genre->image))){
                                                $img = '<img src="'.asset('public/images/audio/audio_genre/'.$genre->image).'" alt="" class="img-fluid">';
                                            }else{
                                                $img = '<img src="'.dummyImage('genre').'" alt="" class="img-fluid">';
                                            }

                                                echo'<a href="'.url('genre/single/'.$genre->id.'/'.$genre->genre_slug).'"><div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6  mb-4">
                                                    <div class="slider_cbox slider_artist_box text-center play_box_container mb-20">
                                                        <div class="slider_cimgbox slider_artist_imgbox play_box_img">'.$img.'
                                                        <div class="ms_play_icon">
                                                            <img src="'. asset('public/images/svg/play.svg').'" alt="play icone">
                                                        </div>
                                                        
                                                        </div>
                                                        
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
                                    }
                                    @endphp                     
                                </div>
                        </div>
                    @php
                        }else{
                            echo '<div class="ms_empty_data">
                                    <p>'.__("frontWords.no_genre").'</p>
                                </div>';
                        }
                    @endphp
                </div>
        </div>
        @include('layouts.front.footer')
    </div>


@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
@endsection
