
    @php
        if(isset(Auth::user()->id)){
    @endphp
        <div class="ms_history_wrapper common_pages_space">
                <div class="ms_history_inner">   
                    @php if(sizeof($audios) > 0){ @endphp
                        
                            <div class="slider_heading_wrap marger_bottom30">
                                <div class="slider_cheading">
                                    <h4 class="cheading_title">{{ __("frontWords.history") }} &nbsp;</h4>
                                </div>
                                <a href="javascript:void(0);" class="ms_btn hisry_clear clearAllHistory">{{ __("frontWords.clear") }}</a>
                            </div>
                            
                            <div class="row">
                            @php
                                $cnt = 0;
                                foreach($audios as $audio_id){
                                    if($cnt <= 24){
                                        $audioDetail = select(['column' => '*', 'table' => 'audio', 'where' => ['id'=> $audio_id]]);
                                        if(sizeof($audioDetail) > 0){
                                            $cnt = 0;
                                            foreach($audioDetail as $audio){
                                                $getArtist = json_decode($audio->artist_id);
                                                $artist_name = '';
                                                foreach($getArtist as $artistid){
                                                    $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistid] ]);
                                                    if(count($artists) > 0){
                                                        $artist_name .= $artists[0]->artist_name.', ';
                                                    }
                                                }
                                                
                                            @endphp
                                                <div class="col-xl-2 col-md-3 col-6">
                                                    <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                        <div class="slider_cimgbox slider_artist_imgbox play_box_img play_btn play_music play_icon_btn" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                                            @if($audio->image != '' && file_exists(public_path('images/audio/thumb/'.$audio->image)))
                                                                <img src="{{ asset('public/images/audio/thumb/'.$audio->image) }}" alt="" class="img-fluid">
                                                            @else
                                                                <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                                                            @endif
                                                            <div class="ms_play_icon">
                                                                <img src="{{ asset('public/images/svg/play.svg') }}" alt="play icone">
                                                            </div>
                                                        </div>
                                                        <div class="slider_ctext slider_artist_text">
                                                            <a class="slider_ctitle slider_artist_ttl limited_text_line getAjaxRecord" data-type="audio" data-url="{{ url('audio/single/'.$audio->id.'/'.$audio->audio_slug) }}" href="javascript:void(0)">{{ $audio->audio_title }}</a>
                                                            <p class="slider_cdescription slider_artist_des limited_text_line">{{ rtrim($artist_name,', ') }}</p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            @php
                                            }
                                        }
                                    }
                                    $cnt++;
                                }
                            @endphp
                        </div>
                    @php
                        }else{
                            echo '<div class="slider_heading_wrap marger_bottom30">
                                    <div class="slider_cheading">
                                        <h4 class="cheading_title">'. __("frontWords.history").' &nbsp;</h4>
                                    </div>
                                </div>
                                <div class="ms_empty_data">
                                    <p>'.__("frontWords.no_track").'</p>
                                </div>';
                        } 
                    @endphp
        </div>
    </div>

@php
    }else{
        echo ' <div class="ms_history_wrapper common_pages_space">
                    <article id="post-31">
                        <div class="ms_entry_content">   
                            <div class="fw-page-builder-content">
                                <section class="fw-main-row ">
                                    <div class="fw-container-fluid">
                                        <div class="fw-row">
                                            <div class="fw-col-xs-12">
                                                <div class="ms_needlogin">
                                                    <div class="needlogin_img">
                                                        <img src="'.asset('public/assets/images/svg/headphones.svg').'" alt="">
                                                    </div>
                                                    <h2>'.__("frontWords.need_to_login").'</h2>
                                                    <a href="javascript:void(0);" class="ms_btn reg_btn" data-toggle="modal" data-target="#loginModal">
                                                        <span>'.__("frontWords.register_login").'</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fw-row">
                                            <div class="fw-col-xs-12">
                                            <div class="fw-divider-space padder_top80"></div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </article>
                </div>';
        }
@endphp

  
@include('layouts.front.footer')            