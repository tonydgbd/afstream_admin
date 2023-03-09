<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php $title = isset($settings['w_title']) && $settings['w_title'] != '' ? $settings['w_title'] : __('adminWords.musioo'); @endphp 
    
    <title>{{ $title }} | @yield('title') </title>
    
    <script src="{{ asset('public/js/app.js') }}" defer></script>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main class="auth_container">
            @yield('content')
        </main>
    </div>
    @yield('script')
</body>
</html>
