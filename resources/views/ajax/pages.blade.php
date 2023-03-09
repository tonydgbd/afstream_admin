@extends('layouts.front.main')
@section('title', __('frontWords.blog_single'))
@section('style')
    <link href="{{ asset('public/assets/css/front/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/css/front/style.css') }}" rel="stylesheet" type="text/css">
@endsection

    @section('content')
    
        <div class="footer_page_wrapper">
            <div class="ms_artist_wrapper common_pages_space">
                <div class="footer_page_row">
                    @php
                        echo htmlspecialchars_decode($pageData->detail);
                    @endphp
                </div>
            </div>
        </div>
     
    @endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
@endsection


@include('layouts.front.footer')
 

