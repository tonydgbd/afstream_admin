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
                   
                    <div class="user-info-wrapper header-links">
                        <a href="javascript:void(0);" class="user-info">
                            @if(isset(Auth::user()->image) &&  file_exists(public_path('images/user/'.Auth::user()->image)))
                                <img src="{{ asset('public/images/user/'.Auth::user()->image) }}" alt="" class="user-img"> 
                            @else
                                <img src="{{ asset('public/assets/images/users/profile.svg') }}" alt="" class="user-img">
                            @endif   
                            
                            <img class="img_artist_verified" src="{{ asset('public/assets/images/veryfied_user.svg') }}" alt="verified">
                            
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
                                    <a href="{{ route('artist.profile') }}"> 
                                        <i class="far fa-edit mr-1"></i> {{ __('adminWords.my_profile') }}
                                    </a>
                                </li>
                                <li><a href="{{route('user.logout') }}"><i class="fas fa-sign-out-alt mr-2"></i>
                                    {{ __('frontWords.logout') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </header>