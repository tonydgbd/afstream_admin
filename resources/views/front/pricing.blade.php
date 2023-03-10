@extends('layouts.front.main')
@section('title', __('frontWords.pricing_plan'))
@section('style')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
@php
    if(!empty($defaultCurrency->symbol)){
        $curr = $defaultCurrency->symbol; 
    }else{
        $curr = session()->get('currency')['symbol'];
    }        
@endphp
    <div class="ms_artist_wrapper common_pages_space pricing_wrapper">
        <div class="ms_account_wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="slider_heading_wrap">
                            <div class="slider_cheading">
                                <h4 class="cheading_title">{{ __('frontWords.pricing_plan') }} &nbsp;</h4>
                            </div>
                        </div>
                    </div>
                    @php
                        if(sizeof($plans) > 0){
                            foreach($plans as $plan){
                                if($plan->plan_amount != 0){
                    @endphp
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="ms_plan_box purchasingPlan">
                            <h3 class="plan_heading">{{ $plan->plan_name }}</h3>
                            
                            <div class="plan_inner">
                                <div class="ms_plan_header">
                                    
                                    <div class="ms_plan_img">
                                        @if($plan->image != '' && file_exists(public_path('images/plan/'.$plan->image)))
                                            <img src="{{ asset('images/plan/'.$plan->image) }}" alt="">
                                        @else
                                            <img src="{{ dummyImage('plan') }}" alt="" class="img-fluid">
                                        @endif
                                    </div>
                                </div>
        
                                
                                
                                <div class="plan_price">
                                <div class="plan_dolar">
                                    <sup>{{ $curr }}</sup> {{ $plan->plan_amount*$rate }}</div> 
                                </div>
                                <ul>
                                <li>{{ __('adminWords.validity') }} <span>  {{ $plan->validity.($plan->is_month_days == 1 ? ' Months' : ' Days')}} </span> </li>
                                <li>{{ __('adminWords.is_download') }} <span>  {{ ($plan->is_download == 1 ? 'Yes' : 'No') }}</span> </li>
                                <li>{{ __('adminWords.show_adv') }} <span>  {{ ($plan->show_advertisement == 1 ? 'Yes' : 'No') }}</span> </li>
                                </ul>
                                
                                <div class="ms_plan_btn">
                                    <a href="{{ url('payment-single/'.Crypt::encrypt($plan->id)) }}" target="_blank" class="ms_btn" >{{ __('frontWords.buy_now') }}</a>
                                </div>
                            </div>
                        </div>
                    </div> 
                    @php
                                }
                            }
                        }
                    @endphp
                </div>
            </div> 
        </div>
        @include('layouts.front.footer')
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script> 
@endsection