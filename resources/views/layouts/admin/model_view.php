<div class="ms_share_music_modal">
        <div id="ms_purchase_music_download" class="modal  centered-modal hide" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa_icon form_close"></i>
                    </button>
                    <div class="foo_sharing ms_share_text">
                        <h1>{{ __('frontWords.select_pay_method') }}</h1>
                    </div>
                    <div class="modal-body">
                        @php 
                            if(!empty($defaultCurrency->symbol)){
                                $curr = $defaultCurrency->symbol; 
                            }else{
                                $curr = session()->get('currency')['symbol'];
                            }
                            $paymentMethod = $paymentMethod->pluck('status','gateway_name')->all();   
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
                                    <div class="form-group">
                                        <label>{{ __('frontWords.card_detail') }}</label>
                                        <form action="{{ route('stripe.checkout.buySingleAudio') }}" method="POST" class="card_Detail" data-redirect="{{ url()->current() }}">
                                            {{ csrf_field() }}
                                            <input placeholder="Card number" type="tel" name="number" class="form-control">
                                            <input placeholder="Full name" type="text" name="name" class="form-control">
                                            <input placeholder="MM/YY" type="tel" name="expiry" class="form-control">
                                            <input placeholder="CVC" type="number" name="cvc" class="form-control">
                                            <input type="hidden" name="audio_id" value="{{ isset($audios) && !empty($audios->id) ? $audios->id : '' }}">
                                            <button type="button" class="ms_btn" data-action="submitThisForm"> {{ __('adminWords.pay_with').' '.__('adminWords.stripe') }} </button>
                                        </form>
                                    </div>
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
                                                <input type="hidden" name="amount" class="payableAmount" value="" /> 
                                                <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}" /> 
                                                <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="">
                                                <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                                <input type="hidden" name="taxApplied" class="taxApplied" value="">
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

            
                            @if(!empty($paymentMethod) && isset($paymentMethod['paytm']) && $paymentMethod['paytm'] == 1)
                            
                                <form class="d-none" method="GET" id="paytm-form" role="form" action="{!! URL::route('paytm') !!}" >
                                    {{ csrf_field() }}
                                    <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}">
                                    <input type="hidden" name="amount" class="payableAmount" value="">   
                                    <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="">
                                    <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                    <input type="hidden" name="taxApplied" class="taxApplied" value="">
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
                                        <input type="hidden" name="amount" class="payableAmount" value="">
                                        <input type="hidden" name="plan_id" value="{{ !empty($plan_detail) ? $plan_detail['id'] : '' }}">
                                        <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="">
                                        <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                        <input type="hidden" name="taxApplied" class="taxApplied" value="">
                                        <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                                        
                                        <div class="col-md-12">
                                            <button type="submit" class="ms_btn">{{ __('adminWords.pay_with').' '.__('adminWords.instamojo') }} </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                                
                            @if(!empty($paymentMethod) && isset($paymentMethod['razorpay']) && $paymentMethod['razorpay'] == 1)                            
                                <div id="razorpayForm" class="d-none">
                                    <button class="ms_btn" id="buy_audio_by_razorpay">{{ __('frontWords.pay_with_razorpay') }}</button>
                                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>  
                                </div>
                            @endif
                            

                            @if(!empty($paymentMethod) && isset($paymentMethod['paypal']) && $paymentMethod['paypal'] == 1)       
                                <form class="form-horizontal" method="POST" id="paypal-form" role="form" action="{{ route('paypal.buySingleAudio') }}" >
                                    {{ csrf_field() }}
                                    <input type="hidden" name="audio_id" value="{{ isset($audios) && !empty($audios->id) ? $audios->id : '' }}">
                                    <div>
                                        <button type="submit" class="ms_btn" id="paypalSubmit">
                                            {{ __('adminWords.pay_with').' '.__('adminWords.paypal') }}
                                        </button>
                                    </div>
                                </form>
                            @endif

                            @if(!empty($paymentMethod) && isset($paymentMethod['payu']) && $paymentMethod['payu'] == 1)                           
            
                                <form method="GET" action="{{ route('payWithPayu') }}" accept-charset="UTF-8" class="form-horizontal d-none" role="form" id="payu-form">
                                    <input type="hidden" name="plan_id" value="">
                                    <input type="hidden" name="amount" class="payableAmount" value="">
                                    <input type="hidden" name="productinfo" value="Musioo">
                                    <input type="hidden" name="planExactAmnt" class="planExactAmnt" value="">
                                    <input type="hidden" name="taxPercent" class="taxPercent" value="{{ !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : 0 }}">
                                    <input type="hidden" name="taxApplied" class="taxApplied" value="">
                                    <input type="hidden" name="discountApplied" class="discountApplied" value="0">
                                    <div>
                                        <button class="ms_btn" type="submit"> {{ __('adminWords.payu_btn') }} </button>
                                    </div>
                                </form>
                            @endif
                                            
                            @if(!empty($paymentMethod) && isset($paymentMethod['paystack']) && $paymentMethod['paystack'] == 1)
                                <div id="paystack-form" class="form-horizontal d-none">
                                    <button type="submit" class="ms_btn payWithPaystack" audio-id="{{ isset($audios) && !empty($audios->id) ? $audios->id : '' }}"> 
                                        {{ __('adminWords.pay_with').' '.__('adminWords.paystack') }}
                                    </button>
                                    <script src="https://js.paystack.co/v1/inline.js"></script>                                    
                                </div>
                            @endif    
            
                        @endif 
                        <input type="hidden" id="cur" value="{{ $curr }}"> 
                            
                    </div>
                </div>
            </div>
        </div>
    </div>