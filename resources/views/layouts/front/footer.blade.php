</div>
    @section('script')
        @if(isset($google_ad) && !empty($google_ad))
            @php
                $getAd = $google_ad->limit(1)->get();
            
                if(!empty($userPlan) && $userPlan->show_advertisement == 1){
                    echo '<div class="google_ad text-center p-5 m-5">'.
                            (sizeof($getAd) > 0 && $getAd[0]->status == 1 ? html_entity_decode($getAd[0]->google_ad_script) : '').'
                        </div>';
                }
        
                if(isset($settings['is_gotop']) && $settings['is_gotop'] == 1){
                    echo '<a href="javascript:void(0);" class="gotop extr_top"></a>'; 
                }
            @endphp
        @endif
    @endsection
    @if(Request::path() != 'home')
        @if(isset($settings['is_footer']) && $settings['is_footer'] == 1)
            <div class="ms_footer_wrapper">
                
                <div class="ms_footer_logo">                
                    <a class="getAjaxRecord" data-type="{{ $homepage }}" data-url="{{ url('/home') }}" href="javascript:void(0)"><img src="{{ (isset($settings['mini_logo']) && $settings['mini_logo'] != '' ? asset('images/sites/'.$settings['mini_logo']) : asset('images/mini_logo.png')) }}" alt="" class="img-fluid"/></a>
                </div>
                <div class="ms_footer_inner container-fluid">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="footer_box">
                                <h1 class="footer_title">{{ (isset($settings['section_1_heading']) ? $settings['section_1_heading'] : __('frontWords.mira_music'))  }}</h1>
                                <p>{{ (isset($settings['section_1_description']) ? $settings['section_1_description'] : __('frontWords.section_1_desc'))  }}</p>
    
                                @if(isset($settings['paypal_donation']) && $settings['paypal_donation'] == 1)
                                    <a href="{{ $settings['PAYPAL_DONATION_LINK'] }}" target="_blank" class="ms_btn">{{ __('frontWords.donate_with_paypal') }}</a>
                                @endif
                            </div>
                            
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="footer_box footer_app">
                                <h1 class="footer_title">{{ (isset($settings['section_2_heading']) ? $settings['section_2_heading'] : __('frontWords.download_app'))  }}</h1>
                                <p>{{ (isset($settings['section_2_description']) ? $settings['section_2_description'] : __('frontWords.section_2_desc'))  }}</p>
                                <a href="{{ isset($settings['google_play_url']) ? $settings['google_play_url'] : '#' }}" class="foo_app_btn"><img src="{{ asset('assets/images/google_play.jpg') }}" alt="" class="img-fluid"></a>
                                <a href="{{ isset($settings['app_store_url']) ? $settings['app_store_url'] : '#' }}" class="foo_app_btn"><img src="{{ asset('assets/images/app_store.jpg') }}" alt="" class="img-fluid"></a>
                            </div>
                        </div>
                        @php
                            if(isset($settings['is_newsletter']) && $settings['is_newsletter'] == 1){
                        @endphp
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="footer_box footer_subscribe">
                                <h1 class="footer_title">{{ isset($settings['section_3_heading']) ? $settings['section_3_heading'] : __('frontWords.subscribe') }}</h1>
                                <p>{{ isset($settings['section_3_description']) ? $settings['section_3_description'] : __('frontWords.section_3_desc') }}</p>
                                <form id="newsLetter" method="post" action="{{ route('newsletter') }}" data-reset="1">
                                    {{ @csrf_field() }}
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control require"  placeholder="{{ __('adminWords.enter').' '.__('adminWords.name') }}">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="email" class="form-control require" data-valid="email" data-error="Invalid email." placeholder="{{ __('adminWords.enter').' '.__('adminWords.email') }}">
                                    </div>
                                    <div class="form-group">
                                        <input type="button" value="{{ __('frontWords.sign_me_up') }}" data-action="submitThisForm" class="ms_btn" />
                                    </div>
                                </form>
                            </div>
                        </div>
                        @php } @endphp
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="footer_box footer_contacts">
                                <h1 class="footer_title">{{ isset($settings['section_4_heading']) ? $settings['section_4_heading'] : __('frontWords.contact_us') }}</h1>
                                <ul class="foo_con_info">
                                    <li>
                                        <div class="foo_con_icon">
                                            <img src="{{ asset('assets/images/svg/phone.svg') }}" alt="">
                                        </div>
                                        <div class="foo_con_data">
                                            <span class="con-title">{{ __('frontWords.call_us') }}:</span>
                                            <span>{{ isset($settings['w_phone']) ? $settings['w_phone'] : '(+1) 202-555-0176, (+1) 2025-5501' }}</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="foo_con_icon">
                                            <img src="{{ asset('assets/images/svg/message.svg') }}" alt="">
                                        </div>
                                        <div class="foo_con_data">
                                            <span class="con-title">{{ __('frontWords.email_us') }} :</span>
                                            <span>
                                                <?php
                                                    if(isset($settings['w_email'])){
                                                        $email = explode(',',$settings['w_email']);
                                                        $newArr = '';
                                                        for($i=0; $i<sizeof($email); $i++){
                                                            $newArr .= '<a href="mailto:'.$email[$i].'">'.$email[$i].'</a>,';
                                                        }
                                                        echo rtrim($newArr,',');
                                                    }else{
                                                        echo 'info@musioo.com , list.admin@musioo.com';
                                                    }
                                                ?>
                                            </span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="foo_con_icon">
                                            <img src="{{ asset('assets/images/svg/add.svg') }}" alt="">
                                        </div>
                                        <div class="foo_con_data">
                                            <span class="con-title">{{ __('frontWords.walk_in') }} :</span>
                                            <span>{{ isset($settings['w_address']) ? $settings['w_address'] : '598 Old House Drive London' }}</span>
                                        </div>
                                    </li>
                                </ul>
                                <div class="foo_sharing">
                                    <div class="share_title">{{ __('frontWords.follow_us') }} :</div>
                                    <ul>
                                        <li><a href="{{ isset($settings['facebook_url']) ? $settings['facebook_url'] : ''  }}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                        <li><a href="{{ isset($settings['linkedin_url']) ? $settings['linkedin_url'] : ''  }}" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                        <li><a href="{{ isset($settings['twitter_url']) ? $settings['twitter_url'] : ''  }}" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                        <li><a href="{{ isset($settings['google_plus_url']) ? $settings['google_plus_url'] : ''  }}" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!--Copyright Area-->
                            <div class="ms_copyright_wrapper">
                                <div class="ms_copyright">
                                    <div class="ms_pages">
                                        <div class="col-xl-6 col-lg-12">
                                            <p>{{ isset($settings['copyrightText']) ? $settings['copyrightText'] : '' }}</p>
                                        </div>
                                        <div class="col-xl-6 col-lg-12">
                                            <div class="footer-menus">
                                                <p><a href="{{ url('faqs') }}" target="_blank">{{ __('frontWords.faqs') }}</a></p>
                                                @php
                                                    $getMenu = select(['table' => 'menus', 'column' => '*', 'where' => ['status' => 1], 'limit' => 2 ]);
                                                    if(sizeof($getMenu) > 0){
                                                        foreach($getMenu as $menus){
                                                            $getPage = select(['column' => '*', 'table' => 'pages', 'where' => [ ['is_active', 1], ['id', $menus->page_id] ] ]);
                                                            if(sizeof($getPage) > 0){
                                                                foreach($getPage as $pages){
                                                                    echo '<p><a href="'.url('pages/'.Crypt::encrypt($pages->id)).'" target="_blank">'.$menus->menu_heading.'</a></p>';
                            
                                                                }
                                                            }
                                                        }
                                                    }
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        
                    </div>
                </div>
            
                
            </div>
        @endif
    @endif 
</div>
</div>


                            

        