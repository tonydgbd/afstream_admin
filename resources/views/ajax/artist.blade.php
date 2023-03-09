
    <!---Artist page--->
    <div class="ms_artist_wrapper common_pages_space">
        <div class="ms_artist_inner">
            <!-- Trending section -->
            <div class="ms_artist_slider trending_artist_slider">
                <div class="slider_heading_wrap">
                    <div class="slider_cheading no-border">
                        <h4 class="cheading_title">{{ __('frontWords.featured').' '.__('frontWords.artist') }} &nbsp;</h4>
                    </div>
                </div>
                    <div class="ms_artist_innerslider">
                        <div class="row custom-grid">
                    
                            @php 
                                if(sizeof($featured_artist) > 0){

                                    foreach($featured_artist as $artists){

                                        if($artists->image != '' && file_exists(public_path('images/artist/'.$artists->image))){
                                            $img = '<img src="'.asset('public/images/artist/'.$artists->image).'" alt="" class="img-fluid">';
                                        }else{
                                            $img = '<img src="'.dummyImage('artist').'" alt="" class="img-fluid">';
                                        }                                    
                                        echo '<div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6 mb-4 play_btn play_icon_btn">
                                                <div class="slider_cbox slider_artist_box text-center play_box_container  mb-20">
                                                    <div class="slider_cimgbox slider_artist_imgbox play_box_img">'.$img.'
                                                    
                                                     <div class="ms_play_icon play_music" data-musicid="'.$artists->id.'" data-musictype="artist" data-url="'.url('/songs').'">
                                                        <img src="'. asset('public/images/svg/play.svg').'" alt="play icone">
                                                    </div>
                                                    
                                                    </div>
                                                   
                                                    <div class="slider_ctext slider_artist_text">
                                                        <a class="slider_ctitle slider_artist_ttl limited_text_line getAjaxRecord" href="javascript:void(0)" data-type="artist" data-url="'.url('artist/single/'.$artists->id.'/'.$artists->artist_slug).'">'.$artists->artist_name.'</a>
                                                    </div>
                                                </div>
                                            </div>';
                                    }
                                }else{
                                    echo '<div class="ms_empty_data">
                                            <p>'.__("frontWords.no_artist").'</p>
                                        </div>';
                                }
                            @endphp 
                    </div>
                </div>
                
            </div>
            <!-- Recommended Artists section -->
            <div class="ms_artist_slider recommended_artist_slider">
                <div class="slider_heading_wrap">
                    <div class="slider_cheading  no-border">
                        <h4 class="cheading_title">{{ __("frontWords.top_artist") }} &nbsp;</h4>
                    </div>  
                </div>
                <div class="ms_artist_innerslider">
                        <div class="row custom-grid">
                            @php 
                            if(sizeof($top_artist) > 0){
                            
                                $artists_id = json_decode($top_artist[0]->top_artist);
                                if(!empty($artists_id)){
                                    foreach($artists_id as $artist_id){
                                        $getArtist = select(['column' => '*', 'table'=>'artists', 'where'=>['id'=>$artist_id] ]); 
                                        if(!empty($getArtist)){
                                            foreach($getArtist as $artist){
                            @endphp 
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6 mb-4 play_btn play_icon_btn">
                                                <div class="slider_cbox slider_artist_box text-center play_box_container  mb-20">
                                                    <div class="slider_cimgbox slider_artist_imgbox play_box_img">
                                                        @if($artist->image != '' && file_exists(public_path('images/artist/'.$artist->image)))
                                                            <img src="{{ asset('public/images/artist/'.$artist->image) }}" alt="" class="img-fluid">
                                                        @else
                                                            <img src="{{ dummyImage('artist') }}" alt="" class="img-fluid">
                                                        @endif   
                                                        <div class="ms_play_icon play_music" data-musicid="{{ $artist->id }}" data-musictype="artist" data-url="{{ url('/songs') }}">
                                                            <img src="{{ asset('public/images/svg/play.svg') }}" alt="play icone">
                                                        </div>
                                                    </div>
                                                    <div class="slider_ctext slider_artist_text">
                                                        <a class="slider_ctitle slider_artist_ttl limited_text_line getAjaxRecord" href="javascript:void(0)" data-type="artist" data-url="{{ url('artist/single/'.$artist->id.'/'.$artist->artist_slug) }}">{{ $artist->artist_name }}</a>
                                                    </div>
                                                </div>
                                            </div>     
                                
                            @php
                                            }
                                        }
                                    }
                                }
                            @endphp
                            @php
                                }else{
                                    echo '<div class="ms_empty_data">
                                            <p>'.__("frontWords.no_artist").'</p>
                                        </div>';
                                }
                            @endphp                    
                        </div>
                </div>
            </div>
        </div>
    </div>
  
   @include('layouts.front.footer')