<!-- Sidebar Start -->
        <aside class="sidebar-wrapper">
            <div class="logo-wrapper">
                <a href="{{ route('artist.home') }}" class="admin-logo">
                    @if(isset($settings['large_logo']))
                        <img src="{{ asset('public/images/sites/'.$settings['large_logo']) }}" class="sp_logo" alt="large_logo">
                    @endif
                    @if(isset($settings['mini_logo']))
                        <img src="{{ asset('public/images/sites/'.$settings['mini_logo']) }}" class="sp_mini_logo" alt="mini_logo">
                    @endif                    
                </a>
            </div>
            <div class="side-menu-wrap">
                <ul class="main-menu">
                    <li>
                        <a href="{{ route('artist.home') }}" class="{{ Nav::isRoute('artist.home') ? 'active' : ''}}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.dashboard') }}
                            </span>
                        </a>                        
                    </li>

                    <li>
                        <a href="{{ route('artist.audio') }}" class="{{ Nav::isRoute('artist.audio') || Nav::isRoute('artist.audio_edit') || Nav::isRoute('artist.audio_create') ? 'active' : '' }}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-music"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>

                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.audio') }}
                            </span>
                        </a>                        
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="{{ Nav::isRoute('subscription') || Nav::isRoute('user.invoice') ? 'active' : ''}}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hard-drive"><line x1="22" y1="12" x2="2" y2="12"></line><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path><line x1="6" y1="16" x2="6.01" y2="16"></line><line x1="10" y1="16" x2="10.01" y2="16"></line></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.transactions') }} 
                            </span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('artist.sales_history') }}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.sales_history') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('artist.payment_history') }}"> 
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.payment_history') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('artist.request_payment') }}"> 
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.request_payment') }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li>
                        <a href="javascript:void(0);" class="{{ Nav::isRoute('artist.integrations') ? 'active' : '' }}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            </span>
                            <span class="menu-text">
                                {{  __('adminWords.settings') }}
                            </span>
                        </a>

                        <ul class="sub-menu {{ Nav::isRoute('artist.integrations') ? 'menu-show' : '' }}">
                            
                            <li>
                                <a class="{{ Nav::isRoute('artist.integrations') ? 'active' : ''}}" href="{{ route('artist.integrations')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.integration') }}
                                    </span>
                                </a>
                            </li>
                            
                            <li>
                                <a class="{{ Nav::isRoute('artist/api') ? 'active' : ''}}" href="{{route('artist.api')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.payment_gateway') }}
                                    </span>
                                </a>
                            </li>
                            
                        </ul>
                    </li>

                </ul>
            </div>
        </aside>