
<html lang="{{ app()->getLocale() }}">
<!-- Begin Head -->
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="{{ (isset($settings['meta_desc']) ? $settings['meta_desc'] : '') }}">
        <meta name="keywords" content="{{ (isset($settings['keywords']) ? $settings['keywords'] : '') }}">
        <meta name="keywords" content="@yield('meta_keywords', '' )">
        
        <meta name="author" content="{{ (isset($settings['author_name']) ? $settings['author_name'] : '' ) }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> @yield('title') || {{ $title }} </title>
        @if(isset($settings['favicon']))
            <link rel="shortcut icon" href="{{ asset('images/sites/'.$settings['favicon']) }}">
        @endif
        @yield('style')

        <link href="{{ asset('assets/css/front/fonts.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/front/bootstrap.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/js/front/plugins/swiper/css/swiper.min.css') }}" rel="stylesheet" type="text/css">
        <!--<link href="{{ asset('assets/js/front/plugins/nice_select/nice-select.css') }}" rel="stylesheet" type="text/css">-->
        <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/js/player/volume.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/js/front/plugins/scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/front/style.css') }}" rel="stylesheet" type="text/css">
        <input type="hidden" value="{{ $countAdminPlaylist }}" id="admin_playlist_count">
        <input type="hidden" value="" id="currentVideoId">
        <input type="hidden" value="" class="buy_audio_id">
        @toastr_css
        <link href="{{ asset('assets/plugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
        <link href="{{asset('assets/css/star-rating.css')}}" rel="stylesheet" type="text/css">
        <script>
            var userBaseUrl = "{{url('/')}}";
            var checkUserId = '{{ isset(Auth::user()->id) ? Auth::user()->id : ''}}';
        </script>
        @if(isset($settings['google_analysis']) && $settings['google_analysis'] != '')
            <script async="" src="https://www.googletagmanager.com/gtag/js?client={{ $settings['google_analysis'] }}"></script>     
            <script> 
                window.dataLayer = window.dataLayer || [];
                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());
                gtag('config', "{{ $settings['google_analysis'] }}");
            </script>   
        @endif  
        @if(isset($settings['fb_pixel']) && $settings['fb_pixel'] != '')
            <script>
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', "{{ $settings['fb_pixel'] }}");
                fbq('track', 'PageView');
                </script>
                <noscript><img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $settings['fb_pixel'] }}&ev=PageView&noscript=1"
                /></noscript>
        @endif  
        <script src="{{ asset('assets/js/front/jquery.js') }}"></script>
        <script src="https://www.youtube.com/iframe_api"></script>  
        <script>
            var jsDynamicText = '<?php echo json_encode(['select_lang'=>__('frontWords.select_lang'),'something_wrong' => __('frontWords.something_wrong'), 'playlist_err' => __('frontWords.playlist_err'),'select_playlist' => __('frontWords.select_playlist'), 'no_song' => __('frontWords.no_song'), 'required_fields' => __('frontWords.required_fields'), 'pass_err' => __('frontWords.pass_err'), 'cnf_pass_err' => __('frontWords.cnf_pass_err'), 'cnf_mismatch' => __('frontWords.cnf_mismatch'), 'only_allowed' => __('frontWords.only_allowed'), 'files' => __('frontWords.files'), 'login_err' => __('frontWords.login_err'), 'coupon_err' => __('frontWords.coupon_err'), 'search_err' => __('frontWords.search_err'), 'are_u_sure' => __('frontWords.are_u_sure'), 'delete_records' => __('frontWords.delete_records'), 'delete_records' => __('adminWords.delete_records'), 'delete' => __('adminWords.delete'), 'want_to_delete' =>__('frontWords.want_to_delete')]) ?>';
            
        </script>  
    </head>
        @inject('google_ad', 'Modules\Setting\Entities\GoogleAd')
        @inject('notifications', 'Modules\Setting\Entities\GoogleAd')
        @inject('paymentMethod', 'Modules\Setting\Entities\PaymentMethod')
        
        @include('layouts.front.header') 

        <div class="append_html_data">
            @yield('content')            
        </div>
        
        @include('layouts.front.audio_player') 
            
            <script>                

                var player;

                function onYouTubeIframeAPIReady() {

                    player = new YT.Player('ytPlayer', {
                        videoId: '',  
                        playerVars : {
                            'autoplay' : 0,
                            'controls' : 0,
                            'modestbranding' : 0,
							'playsinline': 1
                        },
                        events: {
                            'onReady': initialize,
                            'onStateChange': onPlayerStateChange,
                        }
                    });
                }


                function onPlayerStateChange(event) {
                     
                    if(event.data == 1){
                        $(".yt_play_pause").removeClass('yt-play').addClass('yt-pause');
                        $('.yt_play_pause').find('i').removeClass('fa fa-play').addClass('fa fa-pause'); 
                    }else if(event.data == 2){
                        $(".yt_play_pause").removeClass('yt-pause').addClass('yt-play');
                        $('.yt_play_pause').find('i').removeClass('fa fa-pause').addClass('fa fa-play'); 
                    }            
                }

                function initialize(){   
					
                    updateTimerDisplay();
                    updateProgressBar();                
                    clearInterval(time_update_interval);
                    
                    var time_update_interval = setInterval(function () {
                        updateTimerDisplay();
                        updateProgressBar();
                    }, 1000);                   

                }				
                
               	$(document).on("click",".yt_music", function() {

				    var _this = $(this);
                    var musicId = $(_this).data('musicid');                 
                    player.loadVideoById(musicId);
                    $('.jplayer_wrapper').addClass('yt_player_opened'); 

                    $('#ytPlayer').show();
                    $(".yt-now-playing").html('');

                    $(".yt_play_pause").removeClass('yt-play').addClass('yt-pause');
                    $('.yt_play_pause').find('i').removeClass('fa fa-play').addClass('fa fa-pause'); 

                    var image = $(_this).attr("data-image");  
                    var title = (_this).attr("data-title");                                 

                    var html = `<div class="jp-track-name">
                                    <span class="que_img"><img src="${image}"></span> 
                                    <div class="que_data">${title}
                                    </div>
                                </div>`;

                    $(".yt-now-playing").append(html);
				});                
                     

                function updateTimerDisplay(){        
                    $('.yt-current-time').text(formatTime( player.getCurrentTime() ));
                    $('.yt-duration').text(formatTime( player.getDuration() ));
                }


                function formatTime(value) {
                    const sec = parseInt(value, 10); // convert value to number if it's string
                    let hours   = Math.floor(sec / 3600); // get hours
                    let minutes = Math.floor((sec - (hours * 3600)) / 60); // get minutes
                    let seconds = sec - (hours * 3600) - (minutes * 60); //  get seconds                    
                    if (hours   < 10) {hours   = "0"+hours;}
                    if (minutes < 10) {minutes = "0"+minutes;}
                    if (seconds < 10) {seconds = "0"+seconds;}                    
                    if(hours > '00'){ // Check Hours
                        return hours+':'+minutes+':'+seconds; // Return is HH : MM : SS
                    }else{
                        return minutes+':'+seconds; // Return is HH : MM : SS
                    }
                }

                function updateProgressBar(){        
                    $('#progress-bar').val((player.getCurrentTime() / player.getDuration()) * 100);
                }
                

                $(document).on("click",".play_music", function() {
                    $(".main_yt_player").hide();
                    $(".main_jplayer").show();
                    player.pauseVideo();
                });           
            </script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" ></script>
            <script src="{{ asset('assets/js/front/jquery.js') }}"></script>
            <script src="{{ asset('assets/js/front/bootstrap.min.js') }}"></script>
            <script src="{{ asset('assets/js/front/plugins/swiper/js/swiper.min.js') }}"></script>
            <script src="{{ asset('assets/js/player/jplayer.playlist.min.js') }}"></script>
            <script src="{{ asset('assets/js/player/jquery.jplayer.min.js') }}"></script>
            <script src="{{ asset('assets/js/audio-player.js') }}"></script>
            <script src="{{ asset('assets/js/player/volume.js') }}"></script>
            <script src="{{asset('assets/js/star-rating.js')}}"></script>
            <!--<script src="{{ asset('assets/js/front/plugins/nice_select/jquery.nice-select.min.js') }}"></script>-->
            <script src="{{ asset('assets/js/front/plugins/scroll/jquery.mCustomScrollbar.js') }}"></script>
            <script src="{{ asset('assets/js/front/custom.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>

        @include('sweet::alert')   
            <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
            <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script> 
            <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script> 
            <script src="{{ asset('assets/js/card.js') }}"></script>
            <script src="{{ asset('assets/js/valid.js') }}"></script> 
            <script src="{{ asset('assets/js/submit.js') }}"></script> 
            <script src="{{ asset('assets/js/front/user-ajax-custom.js?'.time()) }}"></script>
        @yield('script')
            <script>
                var isInspect = 0;
                var isRightClick = 0;
            </script>
        @php
            if(!empty($settings)){
                if(isset($settings['inspect']) && $settings['inspect'] == 0){
        @endphp
            <script>
                isInspect = 1;
            </script>
        @php
            }
            if(isset($settings['right_click']) && $settings['right_click'] == 0){  @endphp
                <script>
                    isRightClick = 1;
                </script>
        @php  
            }
        }
        @endphp
        @toastr_js
        @toastr_render

        
    </body>
</html>    