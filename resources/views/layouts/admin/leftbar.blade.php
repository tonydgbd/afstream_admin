<!-- Sidebar Start -->
        <aside class="sidebar-wrapper">
            <div class="logo-wrapper">
                <a href="{{url('/admin')}}" class="admin-logo">
                    @if(isset($settings['large_logo']))
                        <img src="{{ asset('images/sites/'.$settings['large_logo']) }}" class="sp_logo" alt="large_logo">
                    @endif
                    @if(isset($settings['mini_logo']))
                        <img src="{{ asset('images/sites/'.$settings['mini_logo']) }}" class="sp_mini_logo" alt="mini_logo">
                    @endif                    
                </a>
            </div>
            <div class="side-menu-wrap">
                <ul class="main-menu">
                    <li>
                        <a href="{{url('/admin')}}" class="{{ Nav::isRoute('admin') ? 'active' : ''}}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.dashboard') }}
                            </span>
                        </a>                        
                    </li>
                    
                    <li>
                        <a href="{{url('/users')}}" class="{{ Nav::isRoute('users') || Nav::isRoute('create') || Nav::isRoute('editUser') ? 'active' : '' }}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.manage_users') }}
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="{{ Nav::isRoute('artist') || Nav::isRoute('artist.create') || Nav::isRoute('artist.edit') ? 'active' : '' }}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.artist').' '.__('adminWords.manage') }}
                            </span>
                        </a>
                        <ul class="sub-menu {{ Nav::isRoute('artist.genre') || Nav::isRoute('artist') || Nav::isRoute('artist.create') || Nav::isRoute('artist.edit') ? 'menu-show' : '' }}">
                            <li>
                                <a class="{{ Nav::isRoute('artist.genre') ? 'active' : '' }}" href="{{url('/artist/genre')}}">
                                    <span class="icon-dash">
                                    </span>
                                    <span class="menu-text">
                                        {{ __('adminWords.artist_genres') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('artist') || Nav::isRoute('artist.create') || Nav::isRoute('artist.edit') ? 'active' : '' }}" href="{{url('/artist')}}">
                                    <span class="icon-dash">
                                    </span>
                                    <span class="menu-text">
                                        {{ __('adminWords.artist') }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>                    

                    <li>
                        <a href="javascript:void(0);" class="{{ Nav::isRoute('audio') || Nav::isRoute('audio.create') || Nav::isRoute('audio.edit') ? 'active' : '' }}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-music"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>

                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.audio').' '.__('adminWords.manage') }}
                            </span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{url('/audio_genres')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.audio_genres') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('audio') || Nav::isRoute('audio.create') || Nav::isRoute('audio.edit') ? 'active' : ''}}" href="{{url('/audio')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.audio') }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="{{ Nav::isRoute('sales_history') || Nav::isRoute('payment_history') || Nav::isRoute('admin.payment_request') ? 'active' : ''}}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.transactions') }} 
                            </span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('sales_history') }}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.sales_history') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('payment_history') }}"> 
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.payment_history') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.payment_request') }}"> 
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.request_payment') }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{url('/subscription')}}" class="{{ Nav::isRoute('subscription') || Nav::isRoute('user.invoice') ? 'active' : ''}}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hard-drive"><line x1="22" y1="12" x2="2" y2="12"></line><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path><line x1="6" y1="16" x2="6.01" y2="16"></line><line x1="10" y1="16" x2="10.01" y2="16"></line></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.subscription') }} 
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{url('/admin/playlist')}}" class="{{ Nav::isRoute('admin.playlist') || Nav::isRoute('admin.playlist.create') || Nav::isRoute('admin.playlist.edit') ? 'active' : ''}}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('frontWords.playlist').' '.__('adminWords.manage') }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/album')}}" class="{{ Nav::isRoute('album.create') || Nav::isRoute('album.edit') ? 'active' : ''}}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-film"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.album').' '.__('adminWords.manage') }}
                            </span>
                        </a>
                    </li>                    

                    <li>
                        <a href="{{url('/coupon_management')}}" class="{{ Nav::isRoute('coupon_management') || Nav::isRoute('coupon.create') || Nav::isRoute('coupon.edit') ? 'active' : ''}}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-percent"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.coupon') }}
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{url('/advertisement')}}" class="{{ Nav::isRoute('adv') ||  Nav::isRoute('adv.create') ||  Nav::isRoute('adv.edit') ? 'active' : ''}}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sunrise"><path d="M17 18a5 5 0 0 0-10 0"></path><line x1="12" y1="2" x2="12" y2="9"></line><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line><line x1="1" y1="18" x2="3" y2="18"></line><line x1="21" y1="18" x2="23" y2="18"></line><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line><line x1="23" y1="22" x2="1" y2="22"></line><polyline points="8 6 12 2 16 6"></polyline></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.adv') }}
                            </span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="javascript:void(0);" class="{{ Nav::isRoute('blog') || Nav::isRoute('create_blog') || Nav::isRoute('editBlog') || Nav::isRoute('blog_category') || Nav::isRoute('comments') ? 'active' : '' }}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.blog').' '.__('adminWords.setting') }}
                            </span>
                        </a>
                        <ul class="sub-menu {{ Nav::isRoute('blog') || Nav::isRoute('create_blog') || Nav::isRoute('editBlog') || Nav::isRoute('comments') ? 'menu-show' : '' }}">
                            <li>
                                <a href="{{url('/blog_category')}}">
                                    <span class="icon-dash">
                                    </span>
                                    <span class="menu-text">
                                        {{ __('adminWords.blog_cat') }}
                                    </span>
                                </a>
                            </li>                                   
                            <li>
                                <a class="{{ Nav::isRoute('blog') || Nav::isRoute('create_blog') || Nav::isRoute('editBlog') || Nav::isRoute('comments') ? 'active' : '' }}" href="{{url('/blog')}}">
                                    <span class="icon-dash">
                                    </span>
                                    <span class="menu-text">
                                        {{ __('adminWords.blog') }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="{{ Nav::isRoute('country') || Nav::isRoute('state') || Nav::isRoute('city') ? 'active' : '' }}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </span>
                            <span class="menu-text">
                                {{ __('adminWords.location') }}
                            </span>
                        </a>
                        <ul class="sub-menu {{ Nav::isRoute('artist.genre') || Nav::isRoute('artist') || Nav::isRoute('artist.create') || Nav::isRoute('artist.edit') ? 'menu-show' : '' }}">
                            <li>
                                <a class="{{ Nav::isRoute('country') ? 'active' : '' }}" href="{{url('/country')}}">
                                    <span class="icon-dash">
                                    </span>
                                    <span class="menu-text">
                                        {{ __('adminWords.country') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('state') ? 'active' : '' }}" href="{{url('/state')}}">
                                    <span class="icon-dash">
                                    </span>
                                    <span class="menu-text">
                                        {{ __('adminWords.state') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('city') ? 'active' : '' }}" href="{{url('/city')}}">
                                    <span class="icon-dash">
                                    </span>
                                    <span class="menu-text">
                                        {{ __('adminWords.city') }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="{{ Nav::isRoute('integration') || Nav::isRoute('seo') || Nav::isRoute('mail') || Nav::isRoute('currency') || Nav::isRoute('api') || Nav::isRoute('site') || Nav::isRoute('social_login') || Nav::isRoute('commonsetting') || Nav::isRoute('menu.setting') || Nav::isRoute('create_menu') || Nav::isRoute('edit_menu') || Nav::isRoute('show_google_ad') || Nav::isRoute('adminsetting') || Nav::isRoute('faq') || Nav::isRoute('addFaq') || Nav::isRoute('edit.faq') || Nav::isRoute('blog') || Nav::isRoute('create_blog') || Nav::isRoute('editBlog') || Nav::isRoute('blog_category') || Nav::isRoute('pages') || Nav::isRoute('editPage') || Nav::isRoute('create_page') || Nav::isRoute('testimonial') || Nav::isRoute('testimonial.create') || Nav::isRoute('testimonial.edit') || Nav::isRoute('slider') || Nav::isRoute('create_slider') || Nav::isRoute('editSlider') || Nav::isRoute('plans') || Nav::isRoute('plan.create') || Nav::isRoute('plan.edit') || Nav::isRoute('notifications') || Nav::isRoute('languages') || Nav::isRoute('manual_transaction') || Nav::isRoute('invoice_setting') || Nav::isRoute('comments') ? 'active' : '' }}">
                            <span class="icon-menu feather-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            </span>
                            <span class="menu-text">
                                {{  __('adminWords.settings') }}
                            </span>
                        </a>
                        <ul class="sub-menu {{ Nav::isRoute('artist.genre') || Nav::isRoute('artist') || Nav::isRoute('artist.create') || Nav::isRoute('artist.edit') ? 'menu-show' : '' }}">
                            
                            <li>
                                <a class="{{ Nav::isRoute('integration') ? 'active' : ''}}" href="{{url('/integration')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.integration') }} 
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a class="{{ Nav::isRoute('seo') ? 'active' : ''}}" href="{{url('/seo')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.seo') }}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a class="{{ Nav::isRoute('mail') ? 'active' : '' }}" href="{{url('/mail')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.mail') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('currency') ? 'active' : ''}}" href="{{url('/currency')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.currency') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('api') ? 'active' : ''}}" href="{{url('/api')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.payment_gateway') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('site') ? 'active' : ''}}" href="{{url('/site')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.site') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('social_login') ? 'active' : ''}}" href="{{url('/social_login')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.social_login') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('commonsetting') ? 'active' : ''}}" href="{{url('/commonsetting')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.footer') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('show_google_ad') ? 'active' : ''}}" href="{{url('/google/ad')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.google_ad') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('menu.setting') || Nav::isRoute('create_menu') || Nav::isRoute('edit_menu') ? 'active' : '' }}" href="{{url('/menusetting')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.menu') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('adminsetting') ? 'active' : ''}}" href="{{url('/adminsetting')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.dashboard') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('open_exchange') ? 'active' : '' }}" href="{{url('/open_exchange')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.currency').' '.__('adminWords.key') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('taxn_commission') ? 'active' : ''}}" href="{{ route('taxn_commission') }}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.taxn_commission') }}
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a class="{{ Nav::isRoute('faq') || Nav::isRoute('addFaq') || Nav::isRoute('edit.faq')  ? 'active' : '' }}" href="{{url('/faq')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.faq').' '.__('adminWords.setting') }}
                                    </span>
                                </a>
                            </li>                            

                            <li>
                                <a class="{{ Nav::isRoute('pages') || Nav::isRoute('editPage') || Nav::isRoute('create_page') ? 'active'  : '' }}" href="{{url('/pages')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.pages').' '.__('adminWords.setting') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('plans') || Nav::isRoute('plan.create') || Nav::isRoute('plan.edit') ? 'active' : '' }}" href="{{url('/plans')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.plan').' '.__('adminWords.setting') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('invoice_setting') ? 'active' : '' }}" href="{{url('/invoice_setting')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.invoice').' '.__('adminWords.setting') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('notifications') ? 'active' : '' }}" href="{{url('/notifications')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.notification') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('languages') ? 'active' : '' }}" href="{{url('/languages')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.language') }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ Nav::isRoute('audio_languages') ? 'active' : '' }}" href="{{url('/audio_languages')}}">
                                    <span class="icon-dash"></span>
                                    <span class="menu-text">
                                        {{ __('adminWords.audio').' '.__('adminWords.language') }}
                                    </span>
                                </a>
                            </li>  
                            
                        </ul>
                    </li>
                  
                </ul>
            </div>
        </aside>