<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="{{ (isset($settings['author_name']) ? $settings['author_name'] : '' ) }}">
        <meta name="keywords" content="{{ (isset($settings['keywords']) ? $settings['keywords'] : '') }}">
        <meta name="description" content="{{ (isset($settings['meta_desc']) ? $settings['meta_desc'] : '') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title> @yield('title') || {{ $title }} </title>
        
        @if(isset($settings['favicon']))
            <link rel="shortcut icon" href="{{ asset('images/sites/'.$settings['favicon']) }}">
        @endif
        
        <link href="{{ asset('assets/css/admin/fonts.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/admin/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        @yield('style')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link href="{{ asset('assets/css/admin/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/admin/icofont.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/admin/nice-select.css') }}" rel="stylesheet" type="text/css">
        
        <link href="{{ asset('assets/css/admin/style.css') }}" rel="stylesheet" type="text/css">
      
        <link href="{{ asset('assets/plugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css">
        
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script src="https://js.paystack.co/v1/inline.js"></script>
        @php 
            $dashColor = '';
            $dashColor = session::get('dashColor');           
        @endphp
        @if(!empty($dashColor))
            <link rel="stylesheet" id="theme-change" type="text/css" href="{{ asset('assets/css/admin/css_picker/'.$dashColor.'.css') }}">
        @endif    

        <link rel="stylesheet" id="theme-change" type="text/css" href="#">
        
        <script>var adminBaseUrl = '{{url("/")}}'</script>    

        <script>
            function checkAll(ele, clas) {
                if (ele.checked)
                    $('.' + clas).prop("checked", true);
                else
                    $('.' + clas).prop("checked", false);
            }
        </script>
        @if(isset($settings['google_analysis']) && $settings['google_analysis'] != '')
            <script async="" src="https://www.googletagmanager.com/gtag/js?id={{ $settings['google_analysis'] }}"></script> 
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());
                gtag('config', {{ $settings["google_analysis"] }});
            </script>   
        @endif 
    </head> 
    <body class="musioo_body_layout vertical-layout">       
        
        <div class="loader">
          <div class="spinner">
            <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="256px" height="88px" viewBox="0 0 128 44" xml:space="preserve"><rect x="0" y="0" width="100%" height="100%" fill="#2f345b" /><g><path fill="var(--primary)" d="M-80.265.241h8.122A1.951,1.951,0,0,1-70.18,2.18V41.845a1.951,1.951,0,0,1-1.963,1.939h-8.122a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1-80.265.241Zm16.468,0h8.122A1.951,1.951,0,0,1-53.712,2.18V41.845a1.951,1.951,0,0,1-1.963,1.939H-63.8a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1-63.8.241Zm16.468,14.6h8.122a1.951,1.951,0,0,1,1.963,1.939V41.845a1.951,1.951,0,0,1-1.963,1.939h-8.122a1.951,1.951,0,0,1-1.963-1.939V16.784A1.951,1.951,0,0,1-47.329,14.845Zm16.468,15.728h8.122a1.951,1.951,0,0,1,1.963,1.939v9.333a1.951,1.951,0,0,1-1.963,1.939h-8.122a1.951,1.951,0,0,1-1.963-1.939V32.512A1.951,1.951,0,0,1-30.861,30.573Zm16.655-15.728h8.122a1.951,1.951,0,0,1,1.963,1.939V41.845a1.951,1.951,0,0,1-1.963,1.939h-8.122a1.951,1.951,0,0,1-1.963-1.939V16.784A1.951,1.951,0,0,1-14.206,14.845ZM2.074,0.241H10.2A1.951,1.951,0,0,1,12.159,2.18V41.845A1.951,1.951,0,0,1,10.2,43.784H2.074A1.951,1.951,0,0,1,.112,41.845V2.18A1.951,1.951,0,0,1,2.074.241Zm16.655,0h8.122A1.951,1.951,0,0,1,28.814,2.18V41.845a1.951,1.951,0,0,1-1.963,1.939H18.729a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1,18.729.241Zm16.468,0h8.122A1.951,1.951,0,0,1,45.282,2.18V41.845a1.951,1.951,0,0,1-1.963,1.939H35.2a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1,35.2.241Zm16.377,0H59.7A1.951,1.951,0,0,1,61.659,2.18V41.845A1.951,1.951,0,0,1,59.7,43.784H51.574a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1,51.574.241Zm16.655,0h8.122A1.951,1.951,0,0,1,78.314,2.18V41.845a1.951,1.951,0,0,1-1.963,1.939H68.229a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1,68.229.241Zm16.468,0h8.122A1.951,1.951,0,0,1,94.782,2.18V41.845a1.951,1.951,0,0,1-1.963,1.939H84.7a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1,84.7.241Zm16.532,0h8.122a1.951,1.951,0,0,1,1.963,1.939V41.845a1.951,1.951,0,0,1-1.963,1.939h-8.122a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1,101.229.241Zm16.468,0h8.122a1.951,1.951,0,0,1,1.963,1.939V41.845a1.951,1.951,0,0,1-1.963,1.939H117.7a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1,117.7.241Zm-230.962,0h8.122A1.951,1.951,0,0,1-103.18,2.18V41.845a1.951,1.951,0,0,1-1.963,1.939h-8.122a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1-113.265.241Zm16.468,0h8.122A1.951,1.951,0,0,1-86.712,2.18V41.845a1.951,1.951,0,0,1-1.963,1.939H-96.8a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1-96.8.241Zm-49.468,0h8.122A1.951,1.951,0,0,1-136.18,2.18V41.845a1.951,1.951,0,0,1-1.963,1.939h-8.122a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1-146.265.241Zm16.468,0h8.122a1.951,1.951,0,0,1,1.963,1.939V41.845a1.951,1.951,0,0,1-1.963,1.939H-129.8a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1-129.8.241Zm-49.468,0h8.122A1.951,1.951,0,0,1-169.18,2.18V41.845a1.951,1.951,0,0,1-1.963,1.939h-8.122a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1-179.265.241Zm16.468,0h8.122a1.951,1.951,0,0,1,1.963,1.939V41.845a1.951,1.951,0,0,1-1.963,1.939H-162.8a1.951,1.951,0,0,1-1.963-1.939V2.18A1.951,1.951,0,0,1-162.8.241Z"/><animateTransform attributeName="transform" type="translate" values="16.5 0;33 0;49.5 0;66 0;82.5 0;99 0;115.5 0;132 0;148.5 0;165 0;181.5 0" calcMode="discrete" dur="1320ms" repeatCount="indefinite"/></g></svg>
          </div> 
        </div>

        <div id="page-wrapper musioo-container">     
            @include('layouts.admin.rightbar')          
            @include('layouts.admin.leftbar')
            <div class="page-wrapper">
                <div class="main-content"> 
                    @yield('content')
                    <div class="musioo-footer footerbar text-center ad-footer-btm">
                        <footer class="footer">
                            <p class="mb-0">{{ isset($settings['copyrightText']) ? $settings['copyrightText'] : '' }}</p>
                        </footer>
                    </div>
                </div>
            </div>                
        </div>  
        <!-- Preview Setting Box -->
        <div class="slide-setting-box">
            <div class="slide-setting-holder">
                <div class="setting-box-head">
                    <h4>{{ __('adminWords.admin').' '.__('adminWords.dashboard') }}</h4> 
                    <a href="javascript:void(0);" class="close-btn">{{ __('adminWords.close') }}</a>
                </div>                        
                <div class="sd-color-op">
                    <h5>{{ __('adminWords.color_option') }}</h5> 
                    <div id="style-switcher">
                        <div>
                            <ul class="colors">
                                <li>
                                    <p class='colorchange' id='style'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color2'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color3'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color4'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color5'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color6'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color7'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color8'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color9'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color10'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color11'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color12'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color13'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color14'>
                                    </p>
                                </li>
                                <li>
                                    <p class='colorchange' id='color15'>
                                    </p>
                                </li>                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Preview Setting -->      
        <script>
            var jsDynamicText = '<?php echo json_encode(['create' => __('adminWords.create'),'add' => __('adminWords.add'), 'update' => __('adminWords.update'), 'fileType' => __('adminWords.file_type'), 'chooseImage' => __('adminWords.choose_image'), 'imgExtErr' => __('adminWords.img_ext'), 'dimensionErr' => __('adminWords.dimension_err'), 'selectImgErr' => __('adminWords.select_image'), 'pleaseChoose' => __('adminWords.choose'), 'blogCat' => __('adminWords.blog_cat'), 'audioGenre' => __('adminWords.audio_genre'), 'artistGenre' => __('adminWords.artist_genres'), 'notification' => __('adminWords.notification'), 'language' => __('adminWords.language'), 'delete_records' => __('adminWords.delete_records'), 'cantUndone' => __('adminWords.cantUndone'), 'delete' => __('adminWords.delete'), 'currency' => __('adminWords.currency'), 'ok' => __('adminWords.ok'), 'update_rate_text' => __('adminWords.update_rate_text'), 'are_u_sure' => __('adminWords.are_u_sure'), 'make_default' => __('adminWords.make_default'), 'default_curr' => __('adminWords.default_curr'),'playlistGenre'=>__('frontWords.playlist').' '.__('adminWords.genre'),'city'=>__('adminWords.city'),'country' => __('adminWords.country'), 'state' => __('adminWords.state'),'select' => __('adminWords.select'),'pay_with_paypal'=> __('adminWords.pay_with').' '.__('adminWords.paypal') ,'pay_with_stripe' => __('adminWords.pay_with').' '.__('adminWords.stripe'),'card_detail'=>__('frontWords.card_detail'),'coupon_discount_value_error'=>__('adminWords.coupon_discount_value_error') ]) ?>';
        </script>         
        
        <script src="{{ asset('assets/js/admin/jquery.min.js') }}"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script src="{{ asset('assets/js/admin/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/admin/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/admin/swiper.min.js') }}"></script>        
        <script src="{{ asset('assets/js/admin/nice-select.min.js') }}"></script>
        <script src="{{ asset('assets/js/admin/custom.js') }}"></script>
        <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script> 
        <script src="{{ asset('assets/js/valid.js') }}"></script> 
        <script src="{{ asset('assets/js/submit.js') }}"></script> 
        @yield('script')     
        
    </body>
</html>    