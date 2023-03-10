@inject('comments' , 'App\Comment');
@inject('notifications' , 'Modules\Setting\Entities\Notification');
@inject('payment_requests' , 'App\ArtistPaymentRequest');

<!-- Header Start -->
    <header class="header-wrapper main-header">
        <div class="header-inner-wrapper">
            <div class="header-right">
                <div class="header-left">
                    <div class="header-links">
                        <a href="javascript:void(0);" class="toggle-btn">
                            <span></span>
                        </a>
                    </div>                        
                </div> 
                <div class="header-controls">
                    <div class="setting-wrapper header-links">
                        <a href="javascript:void(0);" class="setting-info">
                            <span class="header-icon">

                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="60" height="60" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><path d="M408,256a151.487,151.487,0,0,1-44.52,107.48l-62.22-62.22A63.8,63.8,0,0,0,320,256Z" fill="#91cc04" data-original="#91cc04"></path><path d="M363.48,363.48A151.487,151.487,0,0,1,256,408V320a63.8,63.8,0,0,0,45.26-18.74Z" fill="#00d7df" data-original="#00d7df"></path><line x1="301.26" y1="301.26" x2="301.25" y2="301.25" fill="#d8d7da" data-original="#d8d7da"></line><line x1="301.26" y1="210.74" x2="301.25" y2="210.75" fill="#d8d7da" data-original="#d8d7da"></line><g><g><path d="M496,256H408a151.487,151.487,0,0,0-44.52-107.48l62.23-62.23A239.249,239.249,0,0,1,496,256Z" fill="#ffda44" data-original="#ffda44"></path><path d="M496,256a239.249,239.249,0,0,1-70.29,169.71l-62.23-62.23A151.487,151.487,0,0,0,408,256Z" fill="#91cc04" data-original="#91cc04"></path><path d="M425.71,425.71A239.249,239.249,0,0,1,256,496V408a151.487,151.487,0,0,0,107.48-44.52Z" fill="#00d7df" data-original="#00d7df"></path><path d="M256,408v88A239.249,239.249,0,0,1,86.29,425.71l62.23-62.23A151.487,151.487,0,0,0,256,408Z" fill="#78b9eb" data-original="#78b9eb"></path><path d="M148.52,363.48,86.29,425.71A239.249,239.249,0,0,1,16,256h88A151.487,151.487,0,0,0,148.52,363.48Z" fill="#006df0" data-original="#006df0"></path><path d="M148.52,148.52A151.487,151.487,0,0,0,104,256H16A239.249,239.249,0,0,1,86.29,86.29Z" fill="#ea348b" data-original="#ea348b"></path><path d="M256,16v88a151.487,151.487,0,0,0-107.48,44.52L86.29,86.29A239.249,239.249,0,0,1,256,16Z" fill="#d80027" data-original="#d80027"></path><path d="M425.71,86.29l-62.23,62.23A151.487,151.487,0,0,0,256,104V16A239.249,239.249,0,0,1,425.71,86.29Z" fill="#ff9811" data-original="#ff9811"></path><path d="M408,256H320a63.8,63.8,0,0,0-18.74-45.26l62.22-62.22A151.487,151.487,0,0,1,408,256Z" fill="#ffda44" data-original="#ffda44"></path></g><path d="M256,320v88a151.487,151.487,0,0,1-107.48-44.52l62.22-62.22A63.8,63.8,0,0,0,256,320Z" fill="#78b9eb" data-original="#78b9eb"></path><path d="M210.74,210.74A63.8,63.8,0,0,0,192,256H104a151.487,151.487,0,0,1,44.52-107.48Z" fill="#ea348b" data-original="#ea348b"></path><path d="M256,104v88a63.8,63.8,0,0,0-45.26,18.74l-62.22-62.22A151.487,151.487,0,0,1,256,104Z" fill="#d80027" data-original="#d80027"></path><line x1="210.75" y1="210.75" x2="210.74" y2="210.74" fill="#d8d7da" data-original="#d8d7da"></line><path d="M363.48,148.52l-62.22,62.22A63.8,63.8,0,0,0,256,192V104A151.487,151.487,0,0,1,363.48,148.52Z" fill="#ff9811" data-original="#ff9811"></path><path d="M210.74,301.26l-62.22,62.22A151.487,151.487,0,0,1,104,256h88A63.8,63.8,0,0,0,210.74,301.26Z" fill="#006df0" data-original="#006df0"></path><line x1="210.75" y1="301.25" x2="210.74" y2="301.26" fill="#d8d7da" data-original="#d8d7da"></line><path d="M431.38,80.65c-.01,0-.01-.01-.02-.01a.01.01,0,0,0-.01-.01,247.988,247.988,0,0,0-350.7,0,.01.01,0,0,0-.01.01c-.01,0-.01.01-.02.01a248.012,248.012,0,0,0,0,350.7c.01,0,.01.01.02.01a.01.01,0,0,0,.01.01,247.988,247.988,0,0,0,350.7,0,.01.01,0,0,0,.01-.01c.01,0,.01-.01.02-.01a248.012,248.012,0,0,0,0-350.7ZM487.85,248H415.8a159.4,159.4,0,0,0-41.2-99.28l51-51A230.161,230.161,0,0,1,487.85,248ZM312.22,300.91A71.678,71.678,0,0,0,327.54,264h72.23a143.431,143.431,0,0,1-36.5,87.96Zm39.74,62.36A143.431,143.431,0,0,1,264,399.77V327.54a71.678,71.678,0,0,0,36.91-15.32ZM327.54,248a71.678,71.678,0,0,0-15.32-36.91l51.05-51.05A143.431,143.431,0,0,1,399.77,248ZM264,24.15A230.258,230.258,0,0,1,414.29,86.4l-51.01,51A159.4,159.4,0,0,0,264,96.2Zm0,88.08a143.431,143.431,0,0,1,87.96,36.5l-51.05,51.05A71.678,71.678,0,0,0,264,184.46ZM248,24.15V96.2a159.4,159.4,0,0,0-99.28,41.2l-51.01-51A230.258,230.258,0,0,1,248,24.15ZM184.46,248H112.23a143.431,143.431,0,0,1,36.5-87.96l51.05,51.05A71.678,71.678,0,0,0,184.46,248Zm15.32,52.91-51.05,51.05A143.431,143.431,0,0,1,112.23,264h72.23A71.678,71.678,0,0,0,199.78,300.91ZM160.04,148.73A143.431,143.431,0,0,1,248,112.23v72.23a71.678,71.678,0,0,0-36.91,15.32ZM86.4,97.72l51,51A159.4,159.4,0,0,0,96.2,248H24.15A230.161,230.161,0,0,1,86.4,97.72ZM24.15,264H96.2a159.4,159.4,0,0,0,41.2,99.28l-51,51A230.161,230.161,0,0,1,24.15,264ZM248,487.85A230.258,230.258,0,0,1,97.71,425.6l51.01-51A159.4,159.4,0,0,0,248,415.8Zm0-88.08a143.431,143.431,0,0,1-87.96-36.5l51.05-51.05A71.678,71.678,0,0,0,248,327.54ZM200,256a56,56,0,1,1,56,56A56.062,56.062,0,0,1,200,256Zm64,231.85V415.8a159.4,159.4,0,0,0,99.28-41.2l51.01,51A230.258,230.258,0,0,1,264,487.85Zm161.6-73.57-51-51A159.4,159.4,0,0,0,415.8,264h72.05A230.161,230.161,0,0,1,425.6,414.28Z" fill="#000000" data-original="#000000"></path></g></g></g></svg>
                            </span>
                        </a>
                    </div>
                    
                    <div class="notification-wrapper header-links">
                        <a href="javascript:void(0);" class="notification-info">
                            <span class="header-icon">
                                <svg enable-background="new 0 0 512 512" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m450.201 407.453c-1.505-.977-12.832-8.912-24.174-32.917-20.829-44.082-25.201-106.18-25.201-150.511 0-.193-.004-.384-.011-.576-.227-58.589-35.31-109.095-85.514-131.756v-34.657c0-31.45-25.544-57.036-56.942-57.036h-4.719c-31.398 0-56.942 25.586-56.942 57.036v34.655c-50.372 22.734-85.525 73.498-85.525 132.334 0 44.331-4.372 106.428-25.201 150.511-11.341 24.004-22.668 31.939-24.174 32.917-6.342 2.935-9.469 9.715-8.01 16.586 1.473 6.939 7.959 11.723 15.042 11.723h109.947c.614 42.141 35.008 76.238 77.223 76.238s76.609-34.097 77.223-76.238h109.947c7.082 0 13.569-4.784 15.042-11.723 1.457-6.871-1.669-13.652-8.011-16.586zm-223.502-350.417c0-14.881 12.086-26.987 26.942-26.987h4.719c14.856 0 26.942 12.106 26.942 26.987v24.917c-9.468-1.957-19.269-2.987-29.306-2.987-10.034 0-19.832 1.029-29.296 2.984v-24.914zm29.301 424.915c-25.673 0-46.614-20.617-47.223-46.188h94.445c-.608 25.57-21.549 46.188-47.222 46.188zm60.4-76.239c-.003 0-213.385 0-213.385 0 2.595-4.044 5.236-8.623 7.861-13.798 20.104-39.643 30.298-96.129 30.298-167.889 0-63.417 51.509-115.01 114.821-115.01s114.821 51.593 114.821 115.06c0 .185.003.369.01.553.057 71.472 10.25 127.755 30.298 167.286 2.625 5.176 5.267 9.754 7.861 13.798z"/></svg>
                            </span>
                            <span class="count-notification"></span>
                        </a>
                        <div class="recent-notification">
                            @php
                                $totalCount = '';
                                $totalComments = $comments->where('admin_view','0')->where('user_id','!=','1')->get()->toArray();
                                $totalNotifications = $notifications->where('admin_view' , '0')->where('notifiable_id','!=','1')->get()->toArray();
                                $paymentRequestNotifications = $payment_requests->where('admin_view' , '0')->get()->toArray();
                                $totalCount = count($totalComments) + count($totalNotifications) + count($paymentRequestNotifications);
                            @endphp
                            <div class="drop-down-header">
                                <h4>{{ __('frontWords.all_notifications') }}</h4>
                                @if(isset($totalCount) && !empty($totalCount))
                                    <p>{{ __('adminWords.you_have').' '.$totalCount.' '.__('adminWords.new_notifications') }} </p>
                                @endif
                            </div>
                            <ul>
                                    
                                @if(isset($totalCount) && !empty($totalCount))
                                
                                    @if(isset($paymentRequestNotifications) && !empty($paymentRequestNotifications))
                                        @foreach($paymentRequestNotifications as $paymentRequest)
                                            @php 
                                                $artistDetail =  \App\User::find($paymentRequest['artist_id']); 
                                                $currSymbol = getDefaultCurrency();
                                            @endphp
                                            @if(isset($artistDetail) && !empty($artistDetail))
                                                <li>
                                                    <a href="{{ route('admin.payment_request') }}">
                                                        <h5><i class="fa fa-bell mr-2"></i>{{ $artistDetail['name'] }}</h5>
                                                        <p>{{ __('adminWords.request_amount') }} - {{ $currSymbol.$paymentRequest['request_amount'] }}</p>
                                                    </a>
                                                </li>
                                            @endif    
                                        @endforeach
                                    @endif   
                                
                                    @if(isset($totalComments) && !empty($totalComments))
                                        @foreach($totalComments as $comments)
                                            <li>
                                                @if(isset($comments['audio_id']) && $comments['audio_id'] != 0)
                                                    @php $audioDetail =  \Modules\Audio\Entities\Audio::find($comments['audio_id']); @endphp
                                                    @if(isset($audioDetail) && !empty($audioDetail))
                                                            
                                                        <a href="{{ url('comments/audio/'.$audioDetail['audio_title'].'/'.Crypt::encrypt($audioDetail['id'])) }}">
                                                            <h5><i class="fas fa-exclamation-circle mr-2"></i>{{ $audioDetail['audio_title'] }}</h5>
                                                            <p>{{ $comments['message'] }}</p>
                                                        </a>
                                                    @endif
                                                @endif    
                                                @if(isset($comments['blog_id']) && $comments['blog_id'] != 0)
                                                    @php $blogDetail =  \Modules\General\Entities\Blogs::find($comments['blog_id']);  @endphp
                                                    @if(isset($blogDetail) && !empty($blogDetail))
                                                        <a href="{{ url('comments/blog/'.$blogDetail['title'].'/'.Crypt::encrypt($blogDetail['id'])) }}">
                                                            <h5><i class="fas fa-comment-dots mr-2"></i>{{ $blogDetail['title'] }}</h5>
                                                            <p>{{ $comments['message'] }}</p>
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @endforeach
                                    @endif
                                    
                                    @if(isset($totalNotifications) && !empty($totalNotifications))
                                        @foreach($totalNotifications as $notification)
                                            @php 
                                                $userDetail =  \App\User::find($notification['notifiable_id']); 
                                                $data = json_decode($notification['data']);
                                            @endphp
                                            @if(isset($userDetail) && !empty($userDetail))
                                                <li>
                                                    <a href="{{ route('notifications') }}">
                                                        <h5><i class="fa fa-bell mr-2"></i>{{ $userDetail['name'] }}</h5>
                                                        <p>@php echo htmlspecialchars_decode($data->data) @endphp</p>
                                                    </a>
                                                </li>
                                            @endif    
                                        @endforeach
                                    @endif   
                                    
                                @else
                                    <li class="drop-down-footer no_new_notification">
                                        <b style="color:red;">{{ __('adminWords.no_notification') }}</b>
                                    </li> 
                                @endif
                            </ul>
                        </div>
                    </div>
                        
                    <div class="notification-wrapper languagebar-select header-links">
                        <div class="languagebar">

                            <label class="mb-0 toltiped ml-2" data-original-title="This change will reflect the whole website">
                                <svg class="mr-2" height="20px" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 469.333 469.333" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><g><g><path d="M253.227,300.267L253.227,300.267L199.04,246.72l0.64-0.64c37.12-41.387,63.573-88.96,79.147-139.307h62.507V64H192     V21.333h-42.667V64H0v42.453h238.293c-14.4,41.173-36.907,80.213-67.627,114.347c-19.84-22.08-36.267-46.08-49.28-71.467H78.72     c15.573,34.773,36.907,67.627,63.573,97.28l-108.48,107.2L64,384l106.667-106.667l66.347,66.347L253.227,300.267z" fill="currentColor" data-original="#000000"/><path d="M373.333,192h-42.667l-96,256h42.667l24-64h101.333l24,64h42.667L373.333,192z M317.333,341.333L352,248.853     l34.667,92.48H317.333z" fill="currentColor" data-original="#000000"/></g></g></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g></g></svg>    
                            </label> 
                            
                            <div class="dropdown">
                                @inject('languages', 'Modules\Language\Entities\Language')
                                @php
                                    $getDefault = $languages->where('is_default', 1)->get();
                                @endphp
                                <a class="dropdown-toggle" href="#" role="button" id="languagelink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="live-icon">{{ (sizeof($getDefault) > 0 ? ucfirst($getDefault[0]->language_name) : 'English') }}</span><span class="feather icon-chevron-down live-icon"></span></a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="languagelink">
                                     <div class="drop-down-header">
                                        <h4>Select Language</h4>
                                    </div>
                                    <div class="languages_option">
                                        @php
                                            $getLang = $languages->where('status', 1)->get();
                                            if(sizeof($getLang) > 0){
                                                foreach($getLang as $lang){ 
                                                    $flag = 'flag-icon-'.strtolower($lang->language_code);
                                                    echo '<a class="dropdown-item '.(Session::get('locale') == $lang->language_code ? 'active' : '').'" href="'.url('locale/'.$lang->language_code).'"><i class="flag '.$flag.' flag-icon-squared"></i>'.ucfirst($lang->language_name).'</a>';
                                                }
                                            }
                                        @endphp
                                    </div>
                                </div>
                            </div>
                            
                        </div> 
                    </div>                    
                   
                    <div class="user-info-wrapper header-links">
                        <a href="javascript:void(0);" class="user-info">
                            @if(isset(Auth::user()->image) &&  file_exists(public_path('images/user/'.Auth::user()->image)))
                                <img src="{{ asset('images/user/'.Auth::user()->image) }}" alt="" class="user-img"> 
                            @else
                                <img src="{{ asset('assets/images/users/profile.svg') }}" alt="" class="user-img">
                            @endif   
                            <div class="blink-animation">
                                <span class="blink-circle"></span>
                                <span class="main-circle"></span>
                            </div>
                        </a>
                        <div class="user-info-box">
                            <div class="drop-down-header">
                                <h4>{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}</h4>
                            </div>
                            <ul>
                                <li>
                                    <a target="_blank" href="{{ url('/home') }}">
                                        <i class="fas fa-eye mr-2"></i>{{ __('adminWords.live_preview') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('edit/'.Auth::user()->id) }}">
                                        <i class="far fa-edit mr-1"></i> {{ __('adminWords.my_profile') }}
                                    </a>
                                </li>
                                <li><a href="{{route('logout') }}"><i class="fas fa-sign-out-alt mr-2"></i>
                                    {{ __('frontWords.logout') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </header>