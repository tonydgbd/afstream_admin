<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{ (isset($settings['meta_desc']) ? $settings['meta_desc'] : 'Musioo laravel admin dashboard template') }}">
    <meta name="keywords" content="{{ (isset($settings['keywords']) ? $settings['keywords'] : 'songs') }}">
    <meta name="author" content="{{ (isset($settings['author_name']) ? $settings['author_name'] : __('adminWords.musioo') ) }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ (isset($settings['w_title']) ? $settings['w_title'] : __('adminWords.musioo')) }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
</head>
<body class="vertical-layout error-page-wrapper">
    <div id="containerbar" class="containerbar authenticate-bg">
        <div class="container">
            <div class="auth-box error-box">
                 <div class="row justify-content-center text-center">
                    <div class="error-content-wrap">
                        <div class="error-logo-wrap">
                            @if(isset($settings['large_logo']))
                                <img src="{{ asset('images/sites/'.$settings['large_logo']) }}" class="img-fluid error-logo" alt="logo">
                            @endif
                        </div>
                        <div class="error-img-wrap">
                            <img src="{{ asset('assets/images/error/404.svg') }}" class="img-fluid error-image" alt="404">
                        </div>
                        <h4 class="error-subtitle mb-4">{{ __('adminWords.page_not_found') }}</h4>
                        <p class="mb-4">{{ __('adminWords.error_404') }}</p>
                        <a href="{{url('/')}}" class="btn ms_btn font-16">
                            <i class="feather icon-home mr-2"></i> {{ __('adminWords.go_to_db') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>