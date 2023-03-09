@extends('layouts.app')
@section('title', __('frontWords.privacy_policy'))
@inject('pages', 'Modules\General\Entities\Pages')  
@section('content')
        
        @php
            $pageDetail = $pages->where('slug','privacy-policy')->first();
        @endphp
        
        <div class="musio_container">
            <div class="musio_condition_wrap">
                    <div class="musio_info_section">
                        <div class="musioo_logo_wrap">
                            <a href="{{ url('/home') }}">
                                <img src="{{ (isset($settings['large_logo']) && $settings['large_logo'] != '' ? asset('public/images/sites/'.$settings['large_logo']) : '' ) }}" alt="" class="img-fluid"/>
                            </a>
                            <h4>
                                 <a href="{{ url('/home') }}">
                                    {{ __('frontWords.home') }}
                                </a> / <span> {{ __('frontWords.privacy_policy') }} </span>
                            </h4>
                        </div>
                    </div>
                    <div class="musio_info_section">
                        <h4 class="musio_title"><span>{{ $pageDetail->title }}</span></h4>
                        <p>{{ strip_tags(htmlspecialchars_decode($pageDetail->detail)) }}</p>
                    </div>
            </div>
        </div>
    
@endsection