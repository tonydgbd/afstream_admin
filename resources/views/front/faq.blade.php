@extends('layouts.front.main')
@section('title', __('frontWords.faqs'))
@section('content')
    <div class="ms_history_wrapper common_pages_space">
        <div class="ms_history_inner">  
            <div class="slider_heading_wrap marger_bottom30">
                <div class="slider_cheading">
                    <h4 class="cheading_title"> {{ __('frontWords.faqs') }} &nbsp;</h4>
                </div>
            </div>

            @php
                if(sizeof($faqs) > 0){
            @endphp
                <div id="accordion" class="ms_faq_wrapper">
                    @php foreach($faqs as $faq){ @endphp
                    <div class="card">
                        <div class="card-header" id="heading{{$faq->id}}">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed accordion-button" data-toggle="collapse" data-target="#collapse{{$faq->id}}" aria-expanded="true" aria-controls="collapse{{$faq->id}}">{{ $faq->question }}</button>
                            </h5>
                        </div>
        
                        <div id="collapse{{$faq->id}}" class="collapse" aria-labelledby="heading{{$faq->id}}" data-parent="#accordion">
                            <div class="card-body">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    </div>
                    @php } @endphp
                </div>
            @php }else{
                echo '<div class="ms_empty_data">
                            <p>'.__("frontWords.no_faq").'</p>
                        </div>';
            } @endphp
        </div>
        @include('layouts.front.footer')
    </div>
@endsection