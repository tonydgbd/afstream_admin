@inject('artistGenre', 'Modules\Artist\Entities\ArtistGenre')
@inject('paymentMethod', 'Modules\Setting\Entities\PaymentMethod')   
@inject('pages', 'Modules\General\Entities\Pages')  

@php 
    $artistGenres = $artistGenre->pluck('genre_name','id')->all();
@endphp

    <!----Audio Player Section---->
        <div class="ms_player_wrapper jplayer_wrapper">
            <div class="ms_player_close">
                <i class="fa fa-angle-down" aria-hidden="true"></i> 
            </div>
            <div class="player_mid">
                <div class="audio-player">
                    <div id="jquery_jplayer_1" class="jp-jplayer"></div>
                    <div id="jp_container_1" class="jp-audio" role="application" aria-label="media player"> 
                        <div class="player_left">
                            <div class="ms_play_song">
                                <div class="play_song_name">
                                    <a href="javascript:void(0);" id="playlist-text">
                                        <div class="jp-now-playing flex-item yt-music-title">
                                            <div class="jp-track-name"></div>
                                            <div class="jp-artist-name"></div>
                                        </div>
                                        <div class="yt-now-playing flex-item yt-video-title"></div>
                                    </a>
                                </div>
                            </div>
                            <div class="play_song_options">
                                <ul> 
                                    @if(!empty($userPlan) && $userPlan->is_download == 1)
                                        <li><a href="javascript:void(0);" class="download_track ms_download jp_cur_download"><span class="song_optn_icon"><i class="ms_icon icon_download"></i></span>
                                            {{ __('frontWords.download_now') }}</a>
                                        </li>
                                    @endif
                                    <li><a href="javascript:void(0);" class="addToFavourite favourite_music jp_cur_favourite"><span class="song_optn_icon"><i class="ms_icon icon_fav"></i></span></a></li>
                                    <li><a href="javascript:void(0);" class="ms_add_playlist jp_cur_playlist"><span class="song_optn_icon"><i class="ms_icon icon_playlist"></i></span>{{ __('frontWords.add_to_playlist') }}</a></li>
                                    <li><a href="javascript:void(0);" class="ms_share_music jp_cur_share"><span class="song_optn_icon"><i class="ms_icon icon_share"></i></span>{{ __('frontWords.share') }}</a></li>
                                </ul>
                            </div>
                            <span class="play-left-arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </div>
                        <!----Right Queue---->  
                        <div class="jp_queue_wrapper">
                            <span class="que_text ms_btn" id="myPlaylistQueue"><i class="fa fa-angle-up" aria-hidden="true"></i> {{ __('frontWords.queue') }}</span>
                            <div id="playlist-wrap" class="jp-playlist">
                                <div class="jp_queue_cls"><i class="fa fa-angle-down" aria-hidden="true"></i></div>
                                <h2><img src="{{ asset('images/add-to-queue.png') }}" alt="Queue">{{ __('frontWords.queue') }}</h2>
                                    
                                <div class="jp_queue_list_inner"> 
                                    <ul>
                                        <li class="jp-playlist-current">&nbsp;</li>
                                    </ul>
                                </div>
                                <div class="jp_queue_btn">
                                    <!-- <a href="clear_modal" class="ms_save ms_btn" data-toggle="modal" data-target="#save_modal">Save Playlist</a> -->
                                    <a href="javascript:void(0);" class="ms_btn ms_clear" data-toggle="modal" data-target="#clear_modal">{{ __('frontWords.clear') }}</a>
                                </div>
                            </div>
                        </div>                      
                        <div class="jp-type-playlist">
                            <div class="jp-gui jp-interface flex-wrap">
                                <div class="jp-controls flex-item">
                                    <button class="jp-previous" tabindex="0">
                                        <i class="ms_play_control"></i>
                                    </button>
                                    <button class="yt_play_pause" tabindex="0">
                                        <i class="fa fa-play"></i>
                                    </button> 

                                    <button class="jp-play" tabindex="0">
                                        <i class="ms_play_control"></i>
                                    </button>

                                    <button class="jp-next" tabindex="0">
                                        <i class="ms_play_control"></i>
                                    </button>
                                </div>
                                <div class="jp-progress-container flex-item">
                                    <div class="jp-time-holder">
                                        <span class="jp-current-time music-timer" role="timer" aria-label="time">&nbsp;</span>
                                        <span class="jp-duration music-timer" role="timer" aria-label="duration">&nbsp;</span>
                                        <span class="yt-current-time yt-video-bar" role="timer" aria-label="time">&nbsp;</span>
                                        <span class="yt-duration yt-video-bar" role="timer" aria-label="duration">&nbsp;</span>
                                    </div>
                                    <div class="jp-progress">
                                        <div class="jp-seek-bar">
                                            <div class="jp-play-bar">
                                                <div class="bullet">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="range" id="progress-bar" class="custom-yt-range yt-video-bar" value="0" style="width: 100%;">
                                </div>
                                <div class="jp-volume-controls flex-item">
                                    <div class="widget knob-container">
                                        <div class="knob-wrapper-outer">
                                            <div class="knob-wrapper">
                                                <div class="knob-mask">
                                                    <div class="knob d3"><span></span></div>
                                                    <div class="handle"></div>
                                                    <div class="round">
                                                        <img src="{{ asset('assets/images/svg/volume.svg') }}" alt="volume">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <input></input> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="jp-toggles flex-item music-suffuling">
                                    <button class="jp-shuffle" tabindex="0" title="Shuffle">
                                    <i class="ms_play_control"></i></button>
                                    <button class="jp-repeat" tabindex="0" title="Repeat"><i class="ms_play_control"></i></button>
                                </div>
                                
                            </div>
                        </div>
                        <div id="ytPlayer" class="youtube-video" style="display:none;">
                        </div>   
                    </div>
                </div>
            </div>
            <!--main div-->
        </div>

    <div class="ms_register_popup">
        <div id="registerModal" class="modal fade centered-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered register_dialog">
                
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal"><i class="fa_icon form_close"></i></button>
                    <div class="modal-body">
                        <div class="ms_register_img">
                            <img src="{{ asset('assets/images/musio-logo.png') }}" alt="" class="img-fluid" />
                        </div>
                        <div class="ms_register_form">
                            <form id="registrationModal" method="post" action="{{ route('user_register') }}" data-reset="1" data-modal="1" modal-open="loginModal">
                            {{ @csrf_field() }}
                                <h2>{{ __('frontWords.register_heading') }}</h2>
                                 <div class="row">
                                     <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.name') }}" class="form-control require" name="name">
                                            <span class="form_icon">
                                        <i class="fa_icon form-user" aria-hidden="true"></i>
                                        </span>
                                        </div>
                                     </div>   
                                     <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.email') }}" class="form-control require" name="email" data-valid="email" data-error="Invalid email.">
                                            <span class="form_icon">
                                                <i class="fa_icon form-envelope" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                     </div>
                                     <div class="col-lg-6">
                                         <div class="form-group">
                                            <input type="password" placeholder="{{ __('adminWords.enter').' '.__('adminWords.password') }}" class="form-control require" name="password" length="6" data-length-error="Password must contain atleast 6 character." id="userPass">
                                            <span class="form_icon">
                                                <i class="fa_icon form-lock" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                     </div>
                                      <div class="col-lg-6">
                                         <div class="form-group">
                                            <input type="password" placeholder="{{ __('adminWords.confirm_password') }}" name="cnf_password" data-error="{{ __('frontWords.cnf_pass_err') }}" class="form-control require" >
                                            <span class="form_icon">
                                                <i class=" fa_icon form-lock" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                     </div>
                                      <div class="col-lg-12">
                                         <div class="form-group">
                                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.mobile').' '.__('adminWords.number') }}" class="form-control" name="mobile" data-valid="mobile" data-error="Invalid mobile number.">
                                            <span class="form_icon">
                                                <i class="fa_icon form-lock" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                     </div>
                                </div>
                                <div class="form-group{{ $errors->has('audio_language_id') ? ' has-error' : '' }} selectToArtist">
                                    <label for="audio_language_id">{{ __('adminWords.select').' '.__('adminWords.audio').' '.__('adminWords.language') }}<sup>*</sup></label>
                                    <select name="audio_language_id[]" class="registerArtistField multipleSelectWithSearch" data-placeholder="{{__('adminWords.choose')}}"  multiple="multiple">
                                        @if(!empty($audioLanguage))
                                            @foreach($audioLanguage as $key => $language) 
                                                <option value="{{$key}}">{{ $language }}</option>
                                            @endforeach     
                                        @endif
                                    </select>
                                </div>            

                                <div class="form-group{{ $errors->has('artist_genre') ? ' has-error' : '' }} selectToArtist">
                                    <label for="artist_genre">{{ __('adminWords.select').' '.__('adminWords.artist_genres') }}<sup>*</sup></label> 

                                    <select name="artist_genre_id" class="registerArtistField select2WithSearch" data-placeholder="{{__('adminWords.choose')}}">
                                        @if(!empty($artistGenres))
                                            @foreach($artistGenres as $key => $genres) 
                                                <option value="{{$key}}">{{ $genres }}</option>
                                            @endforeach         
                                        @endif
                                    </select>                                    
                                </div>
                                
                                @if(isset($settings['is_artist_register']) && $settings['is_artist_register'] == 1)
                                    <div class="musioo_checkbox" style="font-size: 12px; color: #7188a4; margin: 0 0 20px;">
                                    	<label for="checkArtist">{{ __('frontWords.want_to_register_artistmanager') }} <input type="checkbox" name="is_artist" id="checkArtist"><span class="checkmark"></span></label>
                                    </div>
                                @endif  
                                @php
                                    $pageType = ['terms-of-use','privacy-policy'];
                                    $checkPages = $pages->whereIn('slug',$pageType)->get()->toArray();
                                @endphp
                                @if(isset($checkPages) && !empty($checkPages))
                                    <div>
                                        <div class="privacy_link_wrap">
                                            <div class="musioo_checkbox">
                                            	<label for="privacy_link_wrap">{{__('frontWords.signing_warning') }}<input type="checkbox" value="1" name="accept_term_and_policy" class="require" id="privacy_link_wrap"><span class="checkmark"></span></label>
                                            </div><p><a href="{{ route('user.termsofuse')}}" class="termsofuse" target="_blank">{{__('frontWords.term_and_condition') }}</a> {{ __('frontWords.and') }} <a href="{{ route('user.privacy_policy') }}" target="_blank">{{ __('frontWords.privacy_policy') }}</a> </p>
                                        </div>
                                    </div>
                                @endif
                                
                                <a class="ms_btn" data-action="submitThisForm">{{ __('frontWords.register').' '.__('frontWords.now') }}</a> 
                                
                            </form>
                            <div class="auth_controls" style="text-align: initial;">
                                <p>{{ __('frontWords.already_acc') }} ? <a href="#loginModal" data-toggle="modal" class="ms_modal hideCurrentModel">{{ __('frontWords.login_here') }}</a></p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="ms_lang_popup">
        <div id="lang_modal" class="modal fade centered-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content language_modal add_lang">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa_icon form_close"></i>
                    </button>
            
                    <div class="modal-body">
                        <h1>{{ __('frontWords.lang_section') }}</h1>
                        <p>{{ __('frontWords.select_languages') }}</p>
                        <ul class="lang_list">
                            @php
                            if(!empty($audioLanguage)){
                                $lang = [];
                                if(isset(Auth::user()->id)){
                                    $checkLang = select(['column' => 'user_language', 'table' => 'favourites', 'where' => ['user_id' => Auth::user()->id] ]);
                                    if(sizeof($checkLang) > 0 && $checkLang[0]->user_language != ''){
                                        $lang = json_decode($checkLang[0]->user_language);
                                    }
                                }
                                foreach($audioLanguage as $key=>$value){ @endphp
                                    <li>
                                        <label class="lang_check_label"> {{ $value }} 
                                            <input type="checkbox" name="check" class="lang_filter" value="{{ $key }}" {{ (sizeof($lang) > 0 && in_array($key, $lang) ? 'checked' : '' ) }}> 
                                            <span class="label-text"></span>
                                        </label>
                                    </li>
                            @php   }
                                }
                            @endphp
                        </ul>
                        <div class="ms_lang_btn">
                            <a href="javascript:void(0);" class="ms_btn language_filter">{{ __('frontWords.apply') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loginModal" class="modal fade centered-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered login_dialog">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa_icon form_close"></i>
                </button>
                <div class="modal-body">
                    <form id="userLogin" method="post" action="{{ route('user.login') }}" data-modal="1" data-redirect="{{ url('/home') }}">
                    {{ @csrf_field() }}
                        <div class="ms_register_img">
                            <img src="{{ asset('assets/images/musio-logo.png') }}" alt="" class="img-fluid" />
                        </div>
                        <div class="ms_register_form">
                            <h2>{{ __('frontWords.login_heading') }}</h2>
                            <div class="form-group">
                                <input type="email" required placeholder="{{ __('adminWords.enter').' '.__('adminWords.email') }}" class="form-control require" data-valid="email" data-error="Invalid email." name="email">
                                <span class="form_icon">
                            <i class="fa_icon form-envelope" aria-hidden="true"></i>
                        </span>
                            </div>
                            <div class="form-group">
                                <input type="password" required placeholder="{{ __('adminWords.enter').' '.__('adminWords.password') }}" class="form-control require" name="password">
                                <span class="form_icon">
                        <i class="fa_icon form-lock" aria-hidden="true"></i>
                        </span>
                            </div>
                            <div class="remember_checkbox">
                                <label>{{ __('adminWords.remember_me') }} 
                            <input type="checkbox">
                            <span class="checkmark"></span>
                        </label>
                            </div>
                            <input type="submit" class="ms_btn" value="{{ __('frontWords.login').' '.__('frontWords.now') }}" /> 
                            <!--<a class="ms_btn" data-action="submitThisForm">login now</a>-->
                                <div class="form-group">
                                    <div class="foo_sharing">
                                        <ul class="p-0">
                                            @if(isset($settings['is_fb']) && $settings['is_fb'] == 1)
                                                <li><a href="{{route('socialLogin','facebook')}}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                            @endif   
                                            @if(isset($settings['is_google']) && $settings['is_google'] == 1)
                                                <li><a href="{{route('socialLogin','google')}}" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                                            @endif
                                            @if(isset($settings['is_twitter']) && $settings['is_twitter'] == 1)
                                                <li><a href="{{route('socialLogin','twitter')}}" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                            @endif
                                             @if(isset($settings['is_linkedin']) && $settings['is_linkedin'] == 1)
                                                <li><a href="{{route('socialLogin','linkedin')}}" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                            @endif
                                            @if(isset($settings['is_github']) && $settings['is_github'] == 1)
                                                <li><a href="{{ route('socialLogin','github') }}" target="_blank"><i class="fa fa-github" aria-hidden="true"></i></a></li>
                                            @endif
                                            @if(isset($settings['is_amazon']) && $settings['is_amazon'] == 1)
                                                <li><a href="{{route('socialLogin','amazon')}}" target="_blank"><i class="fa fa-amazon" aria-hidden="true"></i></a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="auth_controls">
                                    <div class="popup_forgot">
                                        <a href="#myModalForgot" data-toggle="modal" data-target="#myModalForgot" class="ms_modal1">{{ __('adminWords.forgot_password') }}</a>
                                    </div>
                                    <p>{{ __('frontWords.havenot_acc') }} <a href="#registerModal" data-toggle="modal" class="ms_modal1 hideCurrentModel">{{ __('frontWords.register_here') }}</a></p>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <div id="myModalForgot" class="modal fade centered-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered login_dialog">
            <div class="myLoader"></div>
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa_icon form_close"></i>
                </button>
                <div class="modal-body">
                    <form action="{{ route('password.email') }}" method="POST">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>{{ __('adminWords.error_msg') }}</strong><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    {{ @csrf_field() }}
                        <div class="ms_register_img">
                            <img src="{{ asset('assets/images/musio-logo.png') }}" alt="" class="img-fluid" />
                        </div>
                        <div class="ms_register_form">
                            <h2>{{ __('adminWords.forgot_password') }}</h2>
                            <div class="form-group">
                                <input id="userForgotEmail" name="email" required type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.email') }}" class="form-control require">
                                <span class="form_icon">
                                    <i class="fa_icon form-envelope" aria-hidden="true"></i>
                                </span>
                            </div>
                            <input type="submit" id="forgotButton" class="ms_btn pointer" value="{{ __('frontWords.send_pass') }}">
                            <div class="auth_controls">
                                <p>{{ __('frontWords.forgot_pass_msg') }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="ms_create_playlist_modal">
        <div id="create_playlist_modal" class="modal  centered-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa_icon form_close"></i>
                    </button>
                    <div class="modal-body">
                        <div class="ms_share_img">
                            <img src="{{ asset('assets/images/svg/playlist.svg') }}" class="img-fluid" alt="Playlist">
                        </div>
                        <div class="ms_share_text">
                            <h1>{{ __('frontWords.create_playlist') }}</h1>
                            <input type="text" name="playlist_name" id="playlist_name" class="form-control require" placeholder="{{ __('frontWords.playlist').' '.__('adminWords.name') }}">
                            <div class="clr_modal_btn">
                                <a href="javascript:void(0);" class="ms_btn create_new_playlist">{{ __('frontWords.create') }}</a>
                                <button class="hst_loader hide"><i class="fa fa-circle-o-notch fa-spin"></i>
                                    {{ __('frontWords.loading') }}   
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ms_add_in_playlist_modal">
        <div id="add_in_playlist_modal" class="modal  centered-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa_icon form_close"></i>
                    </button>
                    <div class="modal-body"> 
                        <div class="ms_share_img">
                            <img src="{{ asset('assets/images/svg/playlist.svg') }}" class="img-fluid" alt="Playlist">
                        </div>
                        <div class="ms_share_text">
                        <h1>{{ __('frontWords.playlist') }}</h1>
                            <select name="playlistname" class="form-control">
                                @php 
                                    if(isset($playlist) && sizeof($playlist) > 0){
                                        foreach($playlist as $list){
                                            echo '<option value="'.$list->id.'">'.$list->playlist_name.'</option>';
                                        }
                                    }else{
                                        echo '<option value="">'.__('adminWords.select').' '.__('frontWords.playlist').'</option>';
                                    }
                                @endphp
                            </select>
                            <div class="clr_modal_btn">
                                <a href="javascript:void(0);" class="add_in_playlist ms_add_in_playlist ms_btn ms_btn_pad">
                                      {{ __('adminWords.add').' '.__('adminWords.to').' '.__('frontWords.playlist') }}
                                </a>
                                     <a href="javascript:void(0);" class="ms_btn ms_btn_pad create_playlist">
                                    {{ __('adminWords.create').' '.__('frontWords.playlist')}}
                                 </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ms_share_music_modal">
        <div id="ms_share_music_modal_id" class="modal  centered-modal hide" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa_icon form_close"></i>
                    </button>
                    <div class="modal-body">
                        <div class="ms_share_img">
                            <img src="{{ url('assets/images/svg/sharing.svg') }}" class="img-fluid" alt="Share">
                        </div>
                        <div class="foo_sharing ms_share_text">
                            <h1>{{ __('frontWords.share_with') }}</h1>
                            <ul>
                                <li><a href="javascript:void(0);" class="ms_share_facebook" onclick=""><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="javascript:void(0);" class="ms_share_linkedin" onclick=""><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                <li><a href="javascript:void(0);" class="ms_share_twitter" onclick=""><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="javascript:void(0);" class="ms_share_googleplus" onclick=""><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ms_share_music_modal">
        <div id="ms_purchase_music_download" class="modal  centered-modal hide" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa_icon form_close"></i>
                    </button>
                    <div class="foo_sharing ms_share_text">
                        <h4 class="buyAudioHead">{{ __('frontWords.select_pay_method') }}</h4>
                    </div>
                    <div class="modal-body">
                        @php 
                            if(!empty($defaultCurrency->symbol)){
                                $curr = $defaultCurrency->symbol; 
                            }else{
                                $curr = session()->get('currency')['symbol'];
                            }
                            $paymentMethod = $paymentMethod->pluck('status','gateway_name')->all();   
                            $isEnable = 0;
                            if(!empty($paymentMethod)){
                                foreach($paymentMethod as $key=>$val){
                                    if($paymentMethod[$key] == 1){
                                        $isEnable = 1;
                                    }
                                } 
                            } 
                        @endphp
                        @if($isEnable == 1)
                            <div class="form-group ms_cardoption_wrapper">
                                <div class="card_option_header">
                                    <label class="buyAudioinfo">{{ __('frontWords.card_option') }}</label>
                                    <label class="buyAudioinfo">{{$curr}}<span class="buyAudioPrice"></span>
                                    </label>                                    
                                </div>


                                <ul class="ms_card_options">
                                    @if(!empty($paymentMethod) && isset($paymentMethod['paypal']) && $paymentMethod['paypal'] == 1)
                                        <li>
                                            <label class="ms_radio_btn custom_tooltip">
                                                <input type="radio" name="cardoption" class="paymentMethod" data-name="paypal" checked>
                                                <span></span>
                                                <img src="{{ asset('assets/images/Payment/Paypal.png') }} " alt="">
                                            </label>
                                        </li>
                                    @endif                   
                                    @if(!empty($paymentMethod) && isset($paymentMethod['payu']) && $paymentMethod['payu'] == 1)
                                        <li>
                                            <label class="ms_radio_btn custom_tooltip">
                                                <input type="radio" name="cardoption" class="paymentMethod" data-name="payumoney">
                                                <span></span>
                                                <img src="{{ asset('assets/images/Payment/Payu.png') }} " alt="">
                                            </label>
                                        </li>
                                    @endif
            
                                    @if(!empty($paymentMethod) && isset($paymentMethod['paytm']) && $paymentMethod['paytm'] == 1)
                                        <li>
                                            <label class="ms_radio_btn custom_tooltip" >
                                                <input type="radio" name="cardoption" class="paymentMethod" data-name="paytm">
                                                <span></span>
                                                <img src="{{ asset('assets/images/Payment/Paytm.png') }} " alt="">
                                            </label>
                                        </li>
                                    @endif
                                    
                                    @if(!empty($paymentMethod) && isset($paymentMethod['instamojo']) && $paymentMethod['instamojo'] == 1)
                                        <li>
                                            <label class="ms_radio_btn custom_tooltip">
                                                <input type="radio" name="cardoption" class="paymentMethod" data-name="instamojo">
                                                <span></span>
                                                <img src="{{ asset('assets/images/Payment/Instamojo.png') }} " alt="">
                                            </label>
                                        </li>
                                    @endif
                                    
                                    @if(!empty($paymentMethod) && isset($paymentMethod['razorpay']) && $paymentMethod['razorpay'] == 1)
                                        <li>
                                            <label class="ms_radio_btn custom_tooltip">
                                                <input type="radio" name="cardoption" class="paymentMethod" data-name="razorpay">
                                                <span></span>
                                                <img src="{{ asset('assets/images/Payment/Razorpay.png') }} " alt="">
                                            </label>
                                        </li>
                                    @endif
            
                                    @if(!empty($paymentMethod) && isset($paymentMethod['braintree']) && $paymentMethod['braintree'] == 1)
                                        <li>
                                            <label class="ms_radio_btn custom_tooltip">
                                                <input type="radio" name="cardoption" class="paymentMethod" data-name="braintree">
                                                <span></span>
                                                <img src="{{ asset('assets/images/Payment/Braintree.png') }} " alt="">
                                            </label>
                                        </li>
                                    @endif
            
                                    @if(!empty($paymentMethod) && isset($paymentMethod['paystack']) && $paymentMethod['paystack'] == 1)
                                        <li>
                                            <label class="ms_radio_btn custom_tooltip" >
                                                <input type="radio" name="cardoption" class="paymentMethod" data-name="paystack">
                                                <span></span>
                                                <img src="{{ asset('assets/images/Payment/Paystack.png') }} " alt="">
                                            </label>
                                        </li>
                                    @endif
                                    
                                    @if(!empty($paymentMethod) && isset($paymentMethod['stripe']) && $paymentMethod['stripe'] == 1)
                                        <li>
                                            <label class="ms_radio_btn custom_tooltip">
                                                <input type="radio" name="cardoption" class="paymentMethod" data-name="stripe">
                                                <span></span>
                                                <input type="hidden" id="disAmt">
                                                <img src="{{ asset('assets/images/Payment/Stripe.png') }} " alt="">
                                            </label>
                                        </li>
                                    @endif
            
                                    @if(!empty($paymentMethod) && isset($paymentMethod['manual_pay']) && $paymentMethod['manual_pay'] == 1)
                                        <li class="manualapay_dv">
                                            <label class="ms_radio_btn custom_tooltip">
                                                <input type="radio" name="cardoption" class="paymentMethod" data-name="manual_pay">
                                                <span></span>
                                                <input type="hidden" id="disAmt">
                                                <img src="{{ asset('assets/images/Payment/Manual_pay.png') }} " alt="manualpay">
                                            </label>
                                        </li>
                                    @endif
                                    
                                </ul>
                            </div>
                            
                            @if(!empty($paymentMethod) && isset($paymentMethod['stripe']) && $paymentMethod['stripe'] == 1)                           
                                
                                <div class="ms_card_wrapper buyAudioStripe d-none">
                                    <div class="form-group">
                                        <label>{{ __('frontWords.card_detail') }}</label>
                                        <form action="{{ route('stripe.checkout.buySingleAudio') }}" method="POST" class="card_Detail" data-redirect="{{ url()->current() }}">
                                            {{ csrf_field() }}
                                            <input placeholder="Card number" type="tel" name="number" class="form-control">
                                            <input placeholder="Full name" type="text" name="name" class="form-control">
                                            <input placeholder="MM/YY" type="tel" name="expiry" class="form-control">
                                            <input placeholder="CVC" type="number" name="cvc" class="form-control">
                                            <input type="hidden" class="audioIdToStripe" name="audio_id" value="">
                                            <button type="button" class="ms_btn" data-action="submitThisForm"> {{ __('adminWords.pay_with').' '.__('adminWords.stripe') }} </button>
                                        </form>
                                    </div>
                                    <div class="form-group">
                                        <div class="card-wrapper"></div>
                                    </div>
                                </div>
                            @endif
            
                            
                            @if((!empty($paymentMethod) && isset($paymentMethod['braintree']) && $paymentMethod['braintree'] == 1) )        
                            
                                <div class="braintree_card d-none">
                                    <a href="javascript:void(0);" class="bt-btn ms_btn"><i class="fa fa-credit-card"></i> {{ __('adminWords.payvia') }}</a>
                                        <div class="braintree">
                                            <form method="POST" id="bt-form" action="{{route('successBraintree')}}">
                                                {{ csrf_field() }} 
                                                <input type="hidden" name="amount" class="payableAmount" value="" /> 
                                                <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}" /> 
                                                <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="">
                                                <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                                <input type="hidden" name="taxApplied" class="taxApplied" value="">
                                                <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                                                <div class="bt-drop-in-wrapper">
                                                    <div id="bt-dropin"></div>
                                                </div>
                                                <input id="nonce" name="payment_method_nonce" type="hidden" />
                                                <button class="payment-final-bt ms_btn d-none" type="submit"> {{__('adminWords.pay_now')}}</button>
                                                <div id="pay-errors" role="alert"></div>
                                            </form>
                                        </div>
                                </div>
                            @endif

            
                            @if(!empty($paymentMethod) && isset($paymentMethod['paytm']) && $paymentMethod['paytm'] == 1)
                            
                                <form class="d-none" method="GET" id="paytm-form" role="form" action="{!! URL::route('paytm') !!}" >
                                    {{ csrf_field() }}
                                    <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}">
                                    <input type="hidden" name="amount" class="payableAmount" value="">   
                                    <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="">
                                    <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                    <input type="hidden" name="taxApplied" class="taxApplied" value="">
                                    <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                                    <div>
                                        <button type="submit" class="ms_btn" id="paytmSubmit">
                                            {{ __('adminWords.pay_with').' '.__('adminWords.paytm') }}
                                        </button>
                                    </div>
                                </form>
                            @endif
            
                            
                            @if(!empty($paymentMethod) && isset($paymentMethod['instamojo']) && $paymentMethod['instamojo'] == 1)
                            
                                <form action="{{ url('paywithinstamojo') }}" method="POST" class="instamojo-form d-none">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <strong>{{ __('adminWords.name') }}</strong>
                                                <input type="text" name="name" class="form-control" placeholder="{{ __('adminWords.enter').' '.__('adminWords.name') }}" value="{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <strong>{{ __('adminWords.mobile').' '.__('adminWords.number') }}</strong>
                                                <input type="text" name="mobile_number" class="form-control" placeholder="{{ __('adminWords.enter').' '.__('adminWords.mobile').' '.__('adminWords.number') }}" value="{{ isset(Auth::user()->mobile) ? Auth::user()->mobile : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <strong>{{ __('adminWords.email') }}</strong>
                                                <input type="text" name="email" class="form-control" placeholder="{{ __('adminWords.enter').' '.__('adminWords.email') }}" value="{{ isset(Auth::user()->email) ? Auth::user()->email : '' }}" required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="amount" class="payableAmount" value="">
                                        <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}">
                                        <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="">
                                        <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                        <input type="hidden" name="taxApplied" class="taxApplied" value="">
                                        <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                                        
                                        <div class="col-md-12">
                                            <button type="submit" class="ms_btn">{{ __('adminWords.pay_with').' '.__('adminWords.instamojo') }} </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                                
                            @if(!empty($paymentMethod) && isset($paymentMethod['razorpay']) && $paymentMethod['razorpay'] == 1)                            
                                <div id="razorpayForm" class="d-none">
                                    <button class="ms_btn" id="buy_audio_by_razorpay">{{ __('frontWords.pay_with_razorpay') }}</button>
                                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>  
                                </div>
                            @endif
                            

                            @if(!empty($paymentMethod) && isset($paymentMethod['paypal']) && $paymentMethod['paypal'] == 1)       
                                <form class="form-horizontal" method="POST" id="paypal-form" role="form" action="{{ route('paypal.buySingleAudio') }}" >
                                    {{ csrf_field() }}
                                    <input type="hidden" name="audio_id" class="buy_audio_id" value="{{ isset($audios) && !empty($audios->id) ? $audios->id : '' }}">
                                    <div>
                                        <button type="submit" class="ms_btn" id="paypalSubmit">
                                            {{ __('adminWords.pay_with').' '.__('adminWords.paypal') }}
                                        </button>
                                    </div>
                                </form>
                            @endif

                            @if(!empty($paymentMethod) && isset($paymentMethod['payu']) && $paymentMethod['payu'] == 1)                           
            
                                <form method="GET" action="{{ route('payWithPayu') }}" accept-charset="UTF-8" class="form-horizontal d-none" role="form" id="payu-form">
                                    <input type="hidden" name="plan_id" value="">
                                    <input type="hidden" name="amount" class="payableAmount" value="">
                                    <input type="hidden" name="productinfo" value="Musioo">
                                    <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="">
                                    <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                    <input type="hidden" name="taxApplied" class="taxApplied" value="">
                                    <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                                    <div>
                                        <button class="ms_btn" type="submit"> {{ __('adminWords.payu_btn') }} </button>
                                    </div>
                                </form>
                            @endif
                                            
                            @if(!empty($paymentMethod) && isset($paymentMethod['paystack']) && $paymentMethod['paystack'] == 1)
                                <div id="paystack-form" class="form-horizontal d-none">
                                    <button type="submit" class="ms_btn payWithPaystack" audio-id="{{ isset($audios) && !empty($audios->id) ? $audios->id : '' }}"> 
                                        {{ __('adminWords.pay_with').' '.__('adminWords.paystack') }}
                                    </button>
                                    <script src="https://js.paystack.co/v1/inline.js"></script>                                    
                                </div>
                            @endif    
                        @else
                            <div style="text-align: center;">
                                {{ __('frontWords.no_payment_gateway') }}
                            </div>
                        @endif 
                        <input type="hidden" id="cur" value="{{ $curr }}"> 
                            
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ms_share_music_modal">
        <div id="ms_lyric_modal_id" class="modal  centered-modal hide" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa_icon form_close"></i>
                    </button>
                    <div class="modal-body">
                        <div class="lyric_box box_open_dv">
                            @if(isset($is_single) && isset($audio) && sizeof($audio) > 0)
                                <h4>{{ $audio[0]->audio_title }}</h4>
                                @php 
                                    if($audio[0]->lyrics != ""){
                                        echo htmlspecialchars_decode($audio[0]->lyrics);
                                    }else{
                                        echo '<p>'.__('frontWords.lyrics_err').'</p>';
                                    }
                                @endphp
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ms_clear_modal">
        <div id="clear_modal" class="modal  centered-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa_icon form_close"></i>
                    </button>
                    <div class="modal-body">
                        <h1>{{ __('frontWords.clear_queue') }}</h1>
                        <div class="clr_modal_btn">
                            <a href="javascript:void(0);" class="ms_btn ms_remove_all">{{ __('frontWords.clear_all') }}</a>
                            <a href="javascript:void(0);" class="ms_btn ms_cancel">{{ __('frontWords.cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="pricingPlanModal" class="modal fade centered-modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered login_dialog">
            
            <input type="hidden" value="" id="planDetail" />
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa_icon form_close"></i>
                </button>
                <div class="modal-body">
                    <form id="userPlanPayment" method="post" action="{{ route('user.login') }}" data-modal="1" data-redirect="{{ url('/home') }}">
                    {{ @csrf_field() }}
                        <div class="ms_register_img">
                            <img src="{{ asset('assets/images/register_img.png') }}" alt="" class="img-fluid" />
                        </div>
                        <div class="ms_register_form">
                            <h2>{{ __('frontWords.payment_gateway') }}</h2>
                            <div class="form-group">
                                <select name="payment_method" class="form-control" id="startPayment">
                                    @php 
                                        if(isset($payments) && sizeof($payments) > 0){
                                                echo '<option value="">'.__('frontWords.select_pay_method').'</option>';
                                            foreach($payments as $payment){
                                                echo '<option value="'.$payment->gateway_name.'">'.$payment->gateway_name.'</option>';
                                            }
                                        }else{
                                            echo '<option value="">'.__('frontWords.select_pay_method').'</option>';
                                        }
                                    @endphp
                                </select>
                            </div>
                            <div class="paymentGateway">
                                <input type="button" class="ms_btn" value="Purchase Now" id="purchasePlan"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  


