@extends('layouts.front.main')
@section('title', __('frontWords.audio_single'))
@section('style')
    <link href="{{asset('assets/css/star-rating.css')}}" rel="stylesheet" type="text/css">
@endsection
@inject('users', 'App\User')
@inject('reply', 'App\Reply')
@section('content')
<div class="musiooFrontSinglePage">
    <div class="ms_artist_wrapper common_pages_space">
        @php 
            if(sizeof($audio) > 0){ 
                $artist_name = get_artist_name(['audio_id'=>$audio[0]->id, 'is_audio'=>1]);
                $getLikeDislikeAudio = getFavDataId(['column' => 'audio_id', 'audio_id' => $audio[0]->id]);
        @endphp
        <div class="ms_artist_single padder_top80">
            <div class="album_single_data">
                <div class="album_single_img">
                    @if($audio[0]->image != '' && file_exists(public_path('images/audio/thumb/'.$audio[0]->image)))
                        <img src="{{ asset('images/audio/thumb/'.$audio[0]->image) }}" alt="" class="img-fluid">
                    @else
                        <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                    @endif            
                </div>
                <div class="album_single_text">
                    <h2>{{ $audio[0]->audio_title }}</h2>
                    <p class="singer_name">{{ __('frontWords.by') }} - {{ $artist_name }}</p>
                    <p class="singer_name">{{ __('frontWords.duration') }} - {{ $audio[0]->audio_duration }}</p>
                    <div class="about_artist">
                        <p>{{ $audio[0]->description }}</p>
                    </div>
                    <div class="album_btn">
                        <a href="#" class="ms_btn play_btn play_music" data-musicid="{{ $audio[0]->id }}" data-musictype="audio" data-url="{{ url('/songs') }}"><span class="play_all"><img src="{{ asset('assets/images/svg/play_all.svg') }}" alt="" >{{ __('frontWords.play') }}</span><span class="pause_all"><img src="{{ asset('assets/images/svg/pause_all.svg') }}" alt="">{{ __('frontWords.pause') }}</span></a>
                        <a href="javascript:void(0);" class="ms_btn add_to_queue" data-musicid="{{ $audio[0]->id }}" data-musictype="audio"><span class="play_all"><img src="{{ asset('assets/images/svg/add_q.svg') }}" alt="">{{ __('frontWords.add_to_queue') }}</span></a>
                        <a href="javascript:void(0);" class="ms_btn lyric_show" data-musicid="{{ $audio[0]->id }}" data-musictype="audio" data-toggle="modal" data-target="#ms_lyric_modal_id"><span class="play_all">

                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 512 512"  xml:space="preserve" class=""><g><path xmlns="http://www.w3.org/2000/svg" d="M448,16H128a8,8,0,0,0-8,8V416H64a8,8,0,0,0-8,8v32a40.045,40.045,0,0,0,40,40H416a40.045,40.045,0,0,0,40-40V24A8,8,0,0,0,448,16ZM216,32H360V176H216ZM96,480a24.028,24.028,0,0,1-24-24V432H376v24a39.792,39.792,0,0,0,8.019,24Zm344-24a24,24,0,0,1-48,0V424a8,8,0,0,0-8-8H136V32h64V184a8,8,0,0,0,8,8H368a8,8,0,0,0,8-8V32h64Z" fill="#ffffff" data-original="#000000" ></path><path xmlns="http://www.w3.org/2000/svg" d="M340.923,49.694a7.988,7.988,0,0,0-6.863-1.455l-64,16A8,8,0,0,0,264,72v41.376A24,24,0,1,0,280,136V78.246l48-12v31.13A24,24,0,1,0,344,120V56A8,8,0,0,0,340.923,49.694ZM256,144a8,8,0,1,1,8-8A8.009,8.009,0,0,1,256,144Zm64-16a8,8,0,1,1,8-8A8.009,8.009,0,0,1,320,128Z" fill="#ffffff" data-original="#000000"></path><path xmlns="http://www.w3.org/2000/svg" d="M248,224h80a8,8,0,0,0,0-16H248a8,8,0,0,0,0,16Z" fill="#ffffff" data-original="#000000" ></path><path xmlns="http://www.w3.org/2000/svg" d="M368,240H208a8,8,0,0,0,0,16H368a8,8,0,0,0,0-16Z" fill="#ffffff" data-original="#000000"></path><path xmlns="http://www.w3.org/2000/svg" d="M360,280a8,8,0,0,0-8-8H224a8,8,0,0,0,0,16H352A8,8,0,0,0,360,280Z" fill="#ffffff" data-original="#000000"></path><path xmlns="http://www.w3.org/2000/svg" d="M248,320a8,8,0,0,0,0,16h80a8,8,0,0,0,0-16Z" fill="#ffffff" data-original="#000000"></path><path xmlns="http://www.w3.org/2000/svg" d="M368,352H208a8,8,0,0,0,0,16H368a8,8,0,0,0,0-16Z" fill="#ffffff" data-original="#000000" ></path><path xmlns="http://www.w3.org/2000/svg" d="M352,384H224a8,8,0,0,0,0,16H352a8,8,0,0,0,0-16Z" fill="#ffffff" data-original="#000000"></path></g></svg>
                        {{ __('frontWords.lyrics') }}</span></a>
                    </div>
                </div>
                
                <div class="album_more_optn list_more">
                    <ul>
                        <li class="list_more">
                            <a href="javascript:void(0);" class="songslist_moreicon">
                                <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.104 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.104 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg>
                                
                            </a>
                            <ul class="ms_common_dropdown ms_downlod_list list_more">  
                                
                                <li>
                                    <a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $audio[0]->id }}" data-musictype="audio">
                                    <span class="opt_icon" title="Add To Queue"><span class="icon icon_queue"></span></span>
                                    {{ __("frontWords.add_to_queue") }}
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('audio/single/'.$audio[0]->id.'/'.$audio[0]->audio_slug) }}" data-sharename="{{ $audio[0]->audio_title }}">
                                        <span class="common_drop_icon drop_share"></span>{{ __('frontWords.share') }}
                                    </a>
                                </li>
                                <li>
                                    @if(isset($audio[0]->download_price) && !empty($audio[0]->download_price))
                                        <input type="hidden" class="getAudioAmountToDownload" value="{{ $audio[0]->download_price }}">
                                        @if(Auth::check())
                                                
                                            @php 
                                                $buyedAudios = json_decode(auth()->user()->audio_download_list); 
                                            @endphp
                                            @if(!empty($buyedAudios))
                                                @if(in_array($audio[0]->id, $buyedAudios))
                                                    <a href="javascript:void(0);" class="artistDownloadTrack download_artist_track" data-musicid="{{ $audio[0]->id }}" data-type="audio">     
                                                        <span class="opt_icon common_drop_icon drop_downld"> 
                                                            <span class="icon icon_download"></span>
                                                        </span>
                                                        {{ __("frontWords.download") }}
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio[0]->id }}" data-type="audio">     
                                                        <span class="opt_icon common_drop_icon drop_downld">
                                                            <span class="icon icon_download"></span>
                                                        </span>
                                                        {{ __("frontWords.buy_to_download") }}
                                                    </a>
                                                @endif
                                            @else
                                                <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio[0]->id }}" data-type="audio">     
                                                    <span class="opt_icon common_drop_icon drop_downld">
                                                        <span class="icon icon_download"></span>
                                                    </span>
                                                    {{ __("frontWords.buy_to_download") }}
                                                </a>
                                            @endif
                                                
                                        @else
                                            <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio[0]->id }}" data-type="audio">     
                                                <span class="opt_icon common_drop_icon drop_downld">
                                                    <span class="icon icon_download"></span>
                                                </span>
                                                {{ __("frontWords.buy_to_download") }}
                                            </a>                                                           
                                        @endif                                             

                                    @elseif(!empty($userPlan) && $userPlan->is_download == 1)                                        
                                        @if($audio[0]->aws_upload == 1)
                                            <a href=" {{ getSongAWSUrlHtml($audio[0]) }} ">
                                                <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                            </a> 
                                        @else    
                                            <a href="javascript:void(0);" class="download_track" data-type="audio" data-musicid="{{ $audio[0]->id }}">
                                                <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                            </a>
                                        @endif
                                    @elseif(empty($audio[0]->download_price) && empty($userPlan))                                        
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
                </div>
            

            </div>
        </div>

        
        @php
            if(sizeof($similar_audio) > 0){
        @endphp
        <div class="ms_artist_slider recommended_artist_slider">
                <div class="slider_heading_wrap">
                    <div class="slider_cheading">
                        <h4 class="cheading_title">{{ __('frontWords.similar_track') }} &nbsp;</h4>
                    </div>                    
                    <!-- Add Arrows -->
                    <div class="slider_cmn_controls">
                        <div class="slider_cmn_nav "><span class="swiper-button-next2 slider_nav_next"></span></div>
                        <div class="slider_cmn_nav"><span class="swiper-button-prev2 slider_nav_prev"></span></div>
                    </div>
                </div>
                <div class="ms_artist_innerslider">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            @php    
                                
                                    foreach($similar_audio as $similarAudio){
                                        $getArtist = json_decode($similarAudio->artist_id);
                                        $artist_name = '';
                                        foreach($getArtist as $artistName){
                                            $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistName] ]);
                                            if(sizeof($artists) > 0){
                                                $artist_name .= $artists[0]->artist_name.', ';
                                            }
                                        }
                                        $getLikeDislikeAudio = getFavDataId(['column' => 'audio_id', 'audio_id' => $similarAudio->id]);
                                @endphp
                                    <div class="swiper-slide play_btn play_music play_icon_btn" data-musicid="{{ $similarAudio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}">
                                        <div class="slider_cbox slider_artist_box text-center play_box_container">
                                            <div class="slider_cimgbox slider_artist_imgbox play_box_img">
                                                @if($similarAudio->image != '' && file_exists(public_path('images/audio/thumb/'.$similarAudio->image)))
                                                    <img src="{{ asset('images/audio/thumb/'.$similarAudio->image) }}" alt="" class="img-fluid">
                                                @else
                                                    <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                                                @endif   
                                                <div class="ms_play_icon">
                                                    <img src="{{ asset('images/svg/play.svg') }}" alt="play icone">
                                                </div>
                                            </div>
                                            <div class="slider_ctext slider_artist_text">
                                                <a class="slider_ctitle slider_artist_ttl" href="{{ url('audio/single/'.$similarAudio->id.'/'.$similarAudio->audio_slug) }}">{{ $similarAudio->audio_title }}</a>                                          
                                                 <p class="slider_cdescription slider_artist_des">{{ $artist_name }}</p> 
                                            </div>
                                        </div>
                                    </div>     
                                
                            @php
                                    }
                            
                                }else{
                                    echo '<div class="ms_empty_data">
                                            <p>'.__("frontWords.no_track").'</p>
                                        </div>';
                                }
                            @endphp                    
                        </div>
                    </div>
                </div>
            </div>
            @php
                }
            @endphp
        
            @php
                if(sizeof($comments) > 0){
            @endphp
                <div class="ms_main_data">
                    <div id="comments" class="comments-area">
                        <div class="ms_heading">
                            <h1 class="comments-title">{{ __('frontWords.comment') }} ({{ sizeof($comments) }})</h1>
                        </div>
                        <ol class="comment-list">
                            @php
                                foreach($comments as $comment){
                                $user = select(['column' => ['name', 'image'], 'table' => 'users', 'where' =>['id' => $comment->user_id] ]);
                                $getReply = $reply->where(['comment_id' => $comment->id, 'audio_id' => $audio[0]->id])->get();
                            @endphp
                                    <li class="comment">
                                        <div class="comment-body ms_comment_section">
                                            <div class="comment-author comment_img">
                                                <img alt="" src="{{ !empty($user) && $user[0]->image != '' ? asset('images/user/'.$user[0]->image) : asset('assets/images/users/profile.svg') }}" alt="" width="50px" height="50px">
                                            </div>
                                            <div class="comment_info">
                                                <div class="comment_head">
                                                    <h3>{{ !empty($user) ? $user[0]->name : '' }}</h3>
                                                    <span class="cmnt_time">{{ !empty($comment->created_at) ? date('F d, Y', strtotime($comment->created_at)).' At '.date('h:i a', strtotime($comment->created_at)) : '' }} </span>
                                                </div>
                                                <div class="ms_test_para">
                                                    <p>{{ $comment->message }}</p>
                                                </div>
                                                
    
                                            </div>
                                        </div>
                                        
                                        @php
                                            if(sizeof($getReply) > 0){
                                                $usersInfo = $users->find($getReply[0]->user_id);   
                                        @endphp
                                            <ol class="children adminComments">
                                                <li class="comment">
                                                    <div class="comment-body ms_comment_section">
                                                        <div class="comment-author comment_img">
                                                            
                                                            <img alt="" src="{{ !empty($usersInfo) && $usersInfo->image != '' ? asset('images/user/'.$usersInfo->image) : asset('assets/images/users/profile.jpg') }}" class="avatar avatar-80 photo" height="80" width="80">
                                                        </div>
                                                        <div class="comment-meta commentmetadata comment_info">
                                                            <div class="comment_head">
                                                                <h3><cite class="fn">{{ !empty($usersInfo) ? $usersInfo->name : '' }}</cite> <span class="says">says:</span></h3>
                                                                
                                                                <p><a href="javascript:void(0);">{{ !empty($getReply[0]) ? date('F d, Y', strtotime($getReply[0]->updated_at)).' At '.date('h:i a', strtotime($getReply[0]->updated_at)) : '' }}
                                                                </a></p>
                                                            </div>
    
                                                            <p>{{ $getReply[0]->reply }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ol>
                                        @php } @endphp
                                    </li>
                            @php
                                }
                            @endphp  
        
                        </ol>
                    </div>
                </div>
                
            @php } @endphp
                
            <div class="ms_cmnt_wrapper">
                <div class="ms_heading">
                    <h1>{{ __('frontWords.leave_comment') }}</h1>
                </div>
                <div class="ms_cmnt_form">
                    <form method="post" id="commentForm" data-reset="1" action="{{ url('user/comment/audio/'.$audio_id) }}">
                        <div class="ms_input_group1">
                            <div class="ms_input">
                                <textarea name="message" class="form-control require" placeholder="{{ __('frontWords.enter_comment') }}"></textarea>
                            </div>
                        </div>
                        <div class="ms_input_group2">
                            <div class="ms_input">
                                <button type="button" class="ms_btn" data-action="submitThisForm">{{ __('frontWords.post_comment') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            @if(isset(Auth::user()->id) && Auth::user()->id == 0)
                <div class="ms_cmnt_wrapper">
                    <div class="ms_heading">
                        <h1>{{ __('frontWords.rating') }}</h1>
                    </div>
                    <div class="ms_cmnt_form">
                        <form method="post" id="ratingForm" action="{{ url('audio/rating') }}">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <div class="rating"></div>
                                    <input type="hidden" value="" name="rating" class="live-rating" />
                                    <input type="hidden" value="{{ $audio_id }}" name="audio_id" id="audioId" />
                                </div>
                            </div> 
                            <div class="form-group">
                                <div class="ms_input">
                                    <a href="javascript:;" class="ms_btn" data-action="submitThisForm">{{ __('frontWords.submit') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            
        </div>
    </div>
    @include('layouts.front.footer')
</div>
@endsection 
@section('script')
<script src="{{asset('assets/js/star-rating.js')}}"></script>

@endsection
