@extends('layouts.front.main')
@section('title', __('frontWords.payment'))
@section('content')

@php
    if(!empty($defaultCurrency->symbol)){
        $curr = $defaultCurrency->symbol; 
    }else{
        $curr = session()->get('currency')['symbol'];
    }
    $taxAmt = !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0;
    
    $plan_amount = $plan_detail->plan_amount*$rate;
    $discount = (float)$plan_amount*(float)$taxAmt/100;
    $amntAfterTax = $taxAmt != 0 ? (float)$plan_amount+$discount : $plan_amount;
@endphp
<div class="musiooFrontSinglePage paymentSinglePage">
    <div class="ms_artist_wrapper common_pages_space">
        
        <div class="slider_heading_wrap marger_bottom30">
            <div class="slider_cheading">
                <h4 class="cheading_title">
                    {{ __('frontWords.payment') }}
                </h4>
            </div>
        </div>
        
        <div class="ms_profile_box">
            <div class="ms_pro_form">
                 <div class="row">
                    <div class="col-lg-6 col-12">
                         <div class="form-group">
                            <label>{{ __('frontWords.plan_name') }}</label>
                            <input type="text" class="form-control" value="{{ !empty($plan_detail) ? $plan_detail['plan_name'] : '' }}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            <label>{{ __('frontWords.plan_price') }}</label>
                            <input type="text" class="form-control" value="{{ $curr.$plan_amount }}" readonly>
                        </div>  
                    </div>
                </div>
                @php 
                    $isEnable = 0;
                    if(!empty($paymentMethod)){
                        foreach($paymentMethod as $key=>$val){
                            if($paymentMethod[$key] == 1){
                                $isEnable = 1;
                            }
                        }
                    }                   
                @endphp
                @if($isEnable == 1)
                    <div class="form-group coupon_wrapper">
                        <!--<label>{{ __('frontWords.any_coupon') }}</label>-->
                        <label>Do you want to use coupon?</label> 
                        <div class="mira_radio_btn_wrap">
                            <div class="mira_radio_btn showCouponForm">
                                <input type="radio" name="apply_coupon" value="1" class="cardoption">
                                <label>
                                    {{ __('frontWords.yes') }}
                                </label>
                            </div>
                            
                            <div class="mira_radio_btn showCouponForm">
                                <input type="radio" name="apply_coupon" value="0" class="cardoption" checked>
                                <label>
                                    {{ __('frontWords.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <form action="{{ url('apply-coupon/'.Crypt::encrypt($plan_detail['id'])) }}" class="d-none" method="post" id="applyCouponForm" data-reset="1">
                        <div class="coupan_code_from">
                            @if(!empty($userCoupon))
                                <div class="form-group checkYourCoupon">
                                    <label>Coupon List</label>
                                    <select class="checkCoupons select2WithSearch">
                                        @forelse($userCoupon as $coupon)
                                            <option value="{{ $coupon['coupon_code'] }}" selected="">{{ $coupon['coupon_code'] }}</option>                 
                                        @empty
                                            <option value="" selected="">Sorry no coupon available</option>
                                        @endforelse
                                    </select>
                                </div>    
                            @endif
                            <div class="coupan_code_input"> 
                                <div class="form-group">
                                    <input type="text" placeholder="Enter coupon code" name="coupon_code" class="form-control" id="couponCode">
                                </div>
                                <button type="button" class="ms_btn" id="saveCoupon">{{ __('frontWords.apply') }}</button>
                            </div>  
                        </div>  
                    </form>
                @endif
                    <div class="payment_detail_view_wrap">   
                    <div class="payment_detail_view">    
                        <p><b>{{ __('frontWords.total_amount') }}</b> : <span id="ttlAmt">{{ $curr.$plan_amount }}</span></p>   
                        <p><b>{{ __('frontWords.coupon_amnt') }}</b> : <span id="couponDis"> -{{ $curr }}0  </span></p>   
                        <p><b>{{ __('frontWords.sub_total') }}</b> : <span id="subTtl"> {{ $curr.$plan_amount }}</span></p>   
                        <p><b>{{ __('frontWords.tax_applied') }} ({{ $taxAmt}}%)</b> : <span id="taxAmount"> +{{ $curr.$discount }}</span></p>   
                        <input type="hidden" id="taxApplied" value="{{ $taxAmt }}">
                        <input type="hidden" id="amountVal" value="{{ $plan_amount }}">
                        <p><b>{{ __('frontWords.grand_total') }}</b> : <span id="totalAmt">{{ $curr.$amntAfterTax }}</span></p>   
                    </div>
                    </div>
                    
                @if($isEnable == 1)
                    <div class="form-group ms_cardoption_wrapper">
                        <label>{{ __('frontWords.card_option') }}</label>
                        
                        <ul class="ms_card_options">
                            @if(!empty($paymentMethod) && isset($paymentMethod['paypal']) && $paymentMethod['paypal'] == 1)
                                <li>
                                    <label class="ms_radio_btn custom_tooltip">
                                        <input type="radio" name="cardoption" class="paymentMethod" data-name="paypal" checked>
                                        <span></span>
                                        <img src="{{ asset('public/assets/images/Payment/Paypal.png') }} " alt="">
                                    </label>
                                </li>
                            @endif                   
                            @if(!empty($paymentMethod) && isset($paymentMethod['payu']) && $paymentMethod['payu'] == 1) 
                                <li>
                                    <label class="ms_radio_btn custom_tooltip">
                                        <input type="radio" name="cardoption" class="paymentMethod" data-name="payumoney">
                                        <span></span>
                                        <img src="{{ asset('public/assets/images/Payment/Payu.png') }} " alt="">
                                    </label>
                                </li>
                            @endif
    
                            @if(!empty($paymentMethod) && isset($paymentMethod['paytm']) && $paymentMethod['paytm'] == 1)
                                <li>
                                    <label class="ms_radio_btn custom_tooltip" >
                                        <input type="radio" name="cardoption" class="paymentMethod" data-name="paytm">
                                        <span></span>
                                        <img src="{{ asset('public/assets/images/Payment/Paytm.png') }} " alt="">
                                    </label>
                                </li>
                            @endif
                            
                            @if(!empty($paymentMethod) && isset($paymentMethod['instamojo']) && $paymentMethod['instamojo'] == 1)
                                <li>
                                    <label class="ms_radio_btn custom_tooltip">
                                        <input type="radio" name="cardoption" class="paymentMethod" data-name="instamojo">
                                        <span></span>
                                        <img src="{{ asset('public/assets/images/Payment/Instamojo.png') }} " alt="">
                                    </label>
                                </li>
                            @endif
                            
                            @if(!empty($paymentMethod) && isset($paymentMethod['razorpay']) && $paymentMethod['razorpay'] == 1)
                                <li>
                                    <label class="ms_radio_btn custom_tooltip">
                                        <input type="radio" name="cardoption" class="paymentMethod" data-name="razorpay">
                                        <span></span>
                                        <img src="{{ asset('public/assets/images/Payment/Razorpay.png') }} " alt="">
                                    </label>
                                </li>
                            @endif
    
                            @if(!empty($paymentMethod) && isset($paymentMethod['braintree']) && $paymentMethod['braintree'] == 1)
                                <li>
                                    <label class="ms_radio_btn custom_tooltip">
                                        <input type="radio" name="cardoption" class="paymentMethod" data-name="braintree">
                                        <span></span>
                                        <img src="{{ asset('public/assets/images/Payment/Braintree.png') }} " alt="">
                                    </label>
                                </li>
                            @endif
    
                            @if(!empty($paymentMethod) && isset($paymentMethod['paystack']) && $paymentMethod['paystack'] == 1)
                                <li>
                                    <label class="ms_radio_btn custom_tooltip" >
                                        <input type="radio" name="cardoption" class="paymentMethod" data-name="paystack">
                                        <span></span>
                                        <img src="{{ asset('public/assets/images/Payment/Paystack.png') }} " alt="">
                                    </label>
                                </li>
                            @endif
                            
                            @if(!empty($paymentMethod) && isset($paymentMethod['stripe']) && $paymentMethod['stripe'] == 1)
                                <li>
                                    <label class="ms_radio_btn custom_tooltip">
                                        <input type="radio" name="cardoption" class="paymentMethod" data-name="stripe">
                                        <span></span>
                                        <input type="hidden" id="disAmt">
                                        <img src="{{ asset('public/assets/images/Payment/Stripe.png') }} " alt="">
                                    </label>
                                </li>
                            @endif
    
                            @if(!empty($paymentMethod) && isset($paymentMethod['manual_pay']) && $paymentMethod['manual_pay'] == 1)
                                <li class="manualapay_dv">
                                    <label class="ms_radio_btn custom_tooltip">
                                        <input type="radio" name="cardoption" class="paymentMethod" data-name="manual_pay">
                                        <span></span>
                                        <input type="hidden" id="disAmt">
                                        <img src="{{ asset('public/assets/images/Payment/Manual_pay.png') }} " alt="manualpay">
                                    </label>
                                </li>
                            @endif
                        </ul>
                    </div>
                    
                    @if(!empty($paymentMethod) && isset($paymentMethod['stripe']) && $paymentMethod['stripe'] == 1)                    
                         
                        <div class="ms_card_wrapper d-none">
                                <label>{{ __('frontWords.card_detail') }}</label>
                                <form action="{{ route('stripe.checkout') }}" method="POST" class="card_Detail" data-redirect="{{ url('/') }}">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-lg-6 col-12">
                                             <div class="form-group">
                                                 <input placeholder="Card number" type="tel" name="number" class="form-control">
                                             </div>
                                        </div> 
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <input placeholder="Full name" type="text" name="name" class="form-control">
                                             </div>
                                        </div> 
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                 <input placeholder="MM/YY" type="tel" name="expiry" class="form-control updateCardExpiryFormate">
                                             </div>
                                        </div> 
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                 <input placeholder="CVC" type="number" name="cvc" class="form-control updateCardCvvFormate">
                                             </div>
                                        </div> 
                                    </div>
                                    <input type="hidden" name="amount" value="{{ $amntAfterTax }}" class="payableAmount">
                                    <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="{{ $plan_amount }}">
                                    <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                    <input type="hidden" name="taxApplied" class="taxApplied" value="{{ $discount }}">
                                    <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                                    <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}" /> 
                                    <button type="button" class="ms_btn" data-action="submitThisForm"> {{ __('adminWords.pay_with').' '.__('adminWords.stripe') }} </button>
                                </form>
                            <div class="form-group">
                                <div class="card-wrapper"></div>
                            </div>
                        </div>
                    @endif
    
                    
                    @if((!empty($paymentMethod) && isset($paymentMethod['braintree']) && $paymentMethod['braintree'] == 1) )        
                    
                        <div class="braintree_card d-none">
                            <a href="javascript:void(0);" class="bt-btn ms_btn"><i class="fa fa-credit-card"></i> {{ __('adminWords.payvia') }}</a>
                                <div class="braintree">
                                    <form method="POST" id="bt-form" action="{{route('successBraintree')}}">
                                        {{ csrf_field() }} 
                                        <input type="hidden" name="amount" class="payableAmount" value="{{ $amntAfterTax }}" /> 
                                        <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}" /> 
                                        <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="{{ $plan_amount }}">
                                        <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                        <input type="hidden" name="taxApplied" class="taxApplied" value="{{ $discount }}">
                                        <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                                        <div class="bt-drop-in-wrapper">
                                            <div id="bt-dropin"></div>
                                        </div>
                                        <input id="nonce" name="payment_method_nonce" type="hidden" />
                                        <button class="payment-final-bt ms_btn d-none" type="submit"> {{__('adminWords.pay_now')}}</button>
                                        <div id="pay-errors" role="alert"></div>
                                    </form>
                                </div>
                        </div>
                    @endif
    
                    @if(!empty($paymentMethod) && isset($paymentMethod['paypal']) && $paymentMethod['paypal'] == 1)
                    
                                    
                        <form class="form-horizontal" method="POST" id="paypal-form" role="form" action="{!! URL::route('paypal') !!}" >
                            {{ csrf_field() }}
                            <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}">
                            <input type="hidden" name="amount" class="payableAmount" value="{{ $amntAfterTax }}">   
                            <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="{{ $plan_amount }}">
                            <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                            <input type="hidden" name="taxApplied" class="taxApplied" value="{{ $discount }}">
                            <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                            <div>
                                <button type="submit" class="ms_btn" id="paypalSubmit">
                                    {{ __('adminWords.pay_with').' '.__('adminWords.paypal') }}
                                </button>
                            </div>
                        </form>
                    @endif
    
                    @if(!empty($paymentMethod) && isset($paymentMethod['paytm']) && $paymentMethod['paytm'] == 1)
                    
                        <form class="d-none" method="GET" id="paytm-form" role="form" action="{!! URL::route('paytm') !!}" >
                            {{ csrf_field() }}
                            <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}">
                            <input type="hidden" name="amount" class="payableAmount" value="{{ $amntAfterTax }}">   
                            <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="{{ $plan_amount }}">
                            <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                            <input type="hidden" name="taxApplied" class="taxApplied" value="{{ $discount }}">
                            <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                            <div>
                                <button type="submit" class="ms_btn" id="paytmSubmit">
                                    {{ __('adminWords.pay_with').' '.__('adminWords.paytm') }}
                                </button>
                            </div>
                        </form>
                    @endif
    
                    
                    @if(!empty($paymentMethod) && isset($paymentMethod['instamojo']) && $paymentMethod['instamojo'] == 1)
                    
                        <form action="{{ url('paywithinstamojo') }}" method="POST" class="instamojo-form d-none">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <strong>{{ __('adminWords.name') }}</strong>
                                        <input type="text" name="name" class="form-control" placeholder="{{ __('adminWords.enter').' '.__('adminWords.name') }}" value="{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <strong>{{ __('adminWords.mobile').' '.__('adminWords.number') }}</strong>
                                        <input type="text" name="mobile_number" class="form-control" placeholder="{{ __('adminWords.enter').' '.__('adminWords.mobile').' '.__('adminWords.number') }}" value="{{ isset(Auth::user()->mobile) ? Auth::user()->mobile : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <strong>{{ __('adminWords.email') }}</strong>
                                        <input type="text" name="email" class="form-control" placeholder="{{ __('adminWords.enter').' '.__('adminWords.email') }}" value="{{ isset(Auth::user()->email) ? Auth::user()->email : '' }}" required>
                                    </div>
                                </div>
                                <input type="hidden" name="amount" class="payableAmount" value="{{ $amntAfterTax }}">
                                <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}">
                                <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="{{ $plan_amount }}">
                                <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                <input type="hidden" name="taxApplied" class="taxApplied" value="{{ $discount }}">
                                <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                                
                                <div class="col-md-12">
                                    <button type="submit" class="ms_btn">{{ __('adminWords.pay_with').' '.__('adminWords.instamojo') }} </button>
                                </div>
                            </div>
                        </form>
                    @endif
                        
                    @if(!empty($paymentMethod) && isset($paymentMethod['razorpay']) && $paymentMethod['razorpay'] == 1)
                    
                        <div id="razorpayForm" class="d-none">
                            <button class="ms_btn" id="razorpay_submit">{{ __('frontWords.pay_with_razorpay') }}</button>
                            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                            <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : 0 }}">
                            <input type="hidden" name="amount" class="rzrPayableAmount" value="{{ $amntAfterTax*100 }}">
                            <input type="hidden" name="_token" value="{!!csrf_token()!!}">
                            <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="{{ $plan_amount }}">
                            <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                            <input type="hidden" name="taxApplied" class="taxApplied" value="{{ $discount }}">
                            <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                        </div>
                    @endif
    
                    @if(!empty($paymentMethod) && isset($paymentMethod['payu']) && $paymentMethod['payu'] == 1)
                    
    
                        <form method="GET" action="{{ route('payWithPayu') }}" accept-charset="UTF-8" class="form-horizontal d-none" role="form" id="payu-form">
                            <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}">
                            <input type="hidden" name="amount" class="payableAmount" value="{{ $amntAfterTax }}">
                            <input type="hidden" name="productinfo" value="Musioo">
                            <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="{{ $plan_amount }}">
                            <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                            <input type="hidden" name="taxApplied" class="taxApplied" value="{{ $discount }}">
                            <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                            <div>
                                <button class="ms_btn" type="submit"> {{ __('adminWords.payu_btn') }} </button>
                            </div>
                        </form>
                    @endif
                                    
                    @if(!empty($paymentMethod) && isset($paymentMethod['paystack']) && $paymentMethod['paystack'] == 1)
                    
                        <form method="POST" action="{{ route('paywithPaystack') }}" accept-charset="UTF-8" class="form-horizontal d-none" role="form" id="paystack-form">
                            <input type="hidden" name="email" value="{{ isset(Auth::user()->email) ? Auth::user()->email : '' }}">
                            <input type="hidden" name="orderID" value="{{ uniqid() }}">
                            <input type="hidden" name="amount" class="payableAmount" value="{{ $amntAfterTax*100 }}"> {{-- required in kobo --}}
                            
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="currency" value="NGN">
                            <input type="hidden" name="metadata" value="{{ json_encode($array = ['user_id' => (isset(Auth::user()->id) ? Auth::user()->id : ''), 'plan_id' => (!empty($plan_detail) ? $plan_detail['id'] : ''), 'planExactAmnt' => $plan_amount, 'taxApplied' => $discount, 'discountApplied' => 0, 'taxPercent' => !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 ]) }}" id="paystackmetadata"> {{-- For other necessary things you want to add to your payload. it is optional though --}}
                            
                            <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}"> {{-- required --}}
                            {{ csrf_field() }} {{-- works only when using laravel 5.1, 5.2 --}}
    
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"> {{-- employ this in place of csrf_field only in laravel 5.0 --}}
    
                            <div>
                                <button type="submit" class="ms_btn">
                                    {{ __('adminWords.pay_with').' '.__('adminWords.paystack') }}
                                </button>
                            </div>
                        </form>
                    @endif       
                     
                    
    
                    @if(isset($settings['is_manual_pay']) && $settings['is_manual_pay'] == 1)
                    
                        <form method="GET" action="{{ route('payWithManualPay') }}" accept-charset="UTF-8" class="form-horizontal d-none" role="form" id="manualpay-form">
                            <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}">
                            <input type="hidden" name="amount" class="payableAmount" value="{{ $amntAfterTax }}">
                            <input type="hidden" name="currency" value="{{ session()->get('currency')['code'] }}">
                            <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="{{ $plan_amount }}">
                            <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                            <input type="hidden" name="taxApplied" class="taxApplied" value="{{ $discount }}">
                            <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                            <div class="payment_detail_view">
                                <h3>{{ __('adminWords.bank_detail') }}</h3>
                                <p><b>{{ __('adminWords.acc_name') }}</b> : <span>{{ $settings['ACCOUNT_NAME'] }}</span></p>
                                <p><b>{{ __('adminWords.acc_no') }}</b> : <span>{{ $settings['ACCOUNT_NUMBER'] }}</span></p>
                                <p><b>{{ __('adminWords.bank_name') }}</b> : <span>{{ strtoupper($settings['BANK_NAME']) }}<span></p>
                                @if($settings['IFSC_CODE'] != '')
                                    <p><b>{{ __('adminWords.ifsc_code') }}</b> : <span>{{ $settings['IFSC_CODE'] }}<span></p>
                                @else
                                    <p><b>{{ __('adminWords.swift_code') }}</b> : <span>{{ $settings['SWIFT_CODE'] }}<span></p>
                                @endif
                                <div class="form-group{{$errors->has('payment_proof') ? 'has-error' : ''}}">
                                    <label for="payment_proof" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="payment Image">
                                        <i class="icon fa fa-check"></i>
                                        {!! Form::file('payment_proof',['class' => 'form-control d-none require', 'id' => 'payment_proof']) !!}
                                        <span class="js-fileName">{{ __('adminWords.choose_image') }}</span>
                                    </label>
                                    <span class="image_title">{{ __('adminWords.payment_proof') }}</span>
                                    <small class="text-danger">{{ $errors->first('payment_proof')}}</small>
                                </div>
                                
                            </div>
                            <div>
                                <button class="ms_btn" type="button" data-action="submitThisForm"> {{ __('adminWords.submit') }} </button>
                            </div>
                        </form>
                    @endif
                @endif 
                <input type="hidden" id="cur" value="{{ $curr }}">
            </div>
        </div>
    </div>
    @include('layouts.front.footer')
</div>
@endsection 
@section('script')
<script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>
<script>
    var client_token = null;   
    $(function(){
        $('.bt-btn').on('click', function(){
            $(".ms_ajax_loader").show();
            $('.bt-btn').addClass('load');
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                type: "GET",
                url: "{{ route('bttoken') }}", 
                success: function(t) {   
                    $(".ms_ajax_loader").hide();
                    if(t.client != null){
                        client_token = t.client;
                        btform(client_token);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $(".ms_ajax_loader").hide();
                    console.log(XMLHttpRequest);
                    $('.bt-btn').removeClass('load');
                    alert('Payment error. Please try again later.');
                }
            });
        });
    });

    function btform(token){
        var payform = document.querySelector('#bt-form');  
        braintree.dropin.create({
            authorization: token,
            selector: '#bt-dropin',  
            paypal: {
                flow: 'vault'
            },
        }, function (createErr, instance) {
            if (createErr) {
                console.log('Create Error', createErr);
                
                $('.preL').fadeOut('fast');
                $(".ms_ajax_loader").hide();
                $('.preloader3').fadeOut('fast');
                return false;
            }
            else{
                $('.bt-btn').hide();
                $('.payment-final-bt').removeClass('d-none');
            }
            payform.addEventListener('submit', function (event) {
                event.preventDefault();
                instance.requestPaymentMethod(function (err, payload) {
                if (err) {
                    console.log('Request Payment Method Error', err);
                    swal({
                        title: "Oops ! ",
                        text: 'Payment Error please try again later !',
                        icon: 'warning'
                    });
                    $('.preL').fadeOut('fast');
                    $(".ms_ajax_loader").hide();
                    $('.preloader3').fadeOut('fast');
                    return false;
                }
                
                document.querySelector('#nonce').value = payload.nonce;
                payform.submit();
                });
            });
        });
    }
</script>
@endsection