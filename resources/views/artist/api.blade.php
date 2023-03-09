@extends('layouts.artist.main')
@section('title', __('adminWords.payment').' '.__('adminWords.setting'))
@section('style')
  <link href="{{ asset('public/assets/plugins/switchery/switch.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')


<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.payment').' '.__('adminWords.setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list paymentGatewayAccordation">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{route('artist.home')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.payment').' '.__('adminWords.setting') }}</li>
                </ul>
                <input type="hidden" value="{{route('artist.updateStatus')}}" id="URL">
            </div>
        </div>
    </div>
</div>
@php //echo'<pre>'; print_r(Auth::user()->id); die; @endphp 

@php //echo'<pre>'; print_r($artistApi); die; @endphp 

<div class="contentbar">  
  <div class="row">
    <div class="col-lg-12">
        {!! Form::open(['method' => 'POST', 'route'=>['artist.api.update','razor']]) !!}
        <div class="card m-b-30">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8">
                        <h5>{{ __('adminWords.razorpay_setting') }}</h5>
                    </div>
                    <div class="col-lg-4 text-right">
                        <div class="form-group">
                            <div class="custom-switch checkbox mr-4">
                                {!! Form::checkbox('is_razorpay', 1, isset($artistApi['is_razorpay']) ? $artistApi['is_razorpay'] : null, ['class' => ' updateSettingRecords', 'data-id'=>"razorpay_box", 'required-id'=>'#razorpay_key, #razorpay_secret', 'data-type'=>'is_razorpay', 'id'=>'razorpay_check', 'data-name' => 'razorpay']) !!}
                                <label for="razorpay_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                            </div> 
                        </div> 
                    </div>                
                </div>
            </div>
          <div id="razorpay_box" class="card-body">
            <div class="row">                    
                <div class="col-lg-10">
                    <div class="form-group{{ $errors->has('razorpay_key') ? ' has-error' : '' }}">
                    <label for="razorpay_key">{{  __('adminWords.razorpay_key') }}<sup>*</sup></label>
                    {!! Form::text('razorpay_key', (isset($artistApi['razorpay_key']) && !empty($artistApi['razorpay_key']) ? $artistApi['razorpay_key'] : null), ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('razorpay_key') }}</small>
                    </div>                      
                    <div class="form-group{{ $errors->has('razorpay_secret') ? ' has-error' : '' }}">
                        <label for="razorpay_secret">{{  __('adminWords.razorpay_secret') }}<sup>*</sup></label>
                        <input type="password" name="razorpay_secret" value="{{(isset($artistApi['razorpay_secret']) ? $artistApi['razorpay_secret']:'')}}" id="razorsecret" class="form-control">
                        <span toggle="#razorsecret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                        <small class="text-danger">{{ $errors->first('razorpay_secret') }}</small>
                    </div>



                    <div class="form-group dd-flex">
                        <label for="razorpay" class="mb-0 mt-2">{{ __('adminWords.make_this_default') }}</label>
                        <div class="radio radio-primary ml-4">
                            {!! Form::radio('default_pay_gateway', 'razorpay', (isset($artistApi['default_pay_gateway']) && $artistApi['default_pay_gateway'] == 'razorpay' ? 'checked' :  ''), ['id'=>'razorpay','class'=>'selectArtistGateway']) !!}
                            <label for="razorpay"></label>
                        </div>                  
                    </div>
                </div>  
                <div class="col-lg-10"> 
                    <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm">{{ __('adminWords.save_setting_btn') }}</button>
                    <div class="clear-both"></div>
                </div>
            </div>
          </div>
        </div>
      {!! Form::close() !!}

      {!! Form::open(['method' => 'POST', 'route'=>['artist.api.update','paypal']]) !!}
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.paypal_setting') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group">
                  <div class="custom-switch checkbox mr-4">
                    {!! Form::checkbox('is_paypal', 1, isset($artistApi['is_paypal']) ? $artistApi['is_paypal'] : null, ['class' => ' updateSettingRecords','required-id'=>'#paypal_client_id, #paypal_secret, #paypal_mode', 'data-type'=>'is_paypal', 'data-id'=>"paypal_box", 'id'=>'paypal_check', 'data-name' => 'paypal']) !!}     
                    <label for="paypal_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>               
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="paypal_box" class="card-body">
            <div class="row">                    
                <div class="col-lg-10">
                    <div class="form-group">
                    <label for="paypal_client_id">{{  __('adminWords.paypal_id') }}<sup>*</sup></label>
                    {!! Form::text('paypal_client_id',isset($artistApi['paypal_client_id']) ? $artistApi['paypal_client_id'] : '', ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('paypal_client_id') }}</small>
                    </div>                      
                    <div class="form-group{{ $errors->has('paypal_secret') ? ' has-error' : '' }}">
                    <label for="paypal_secret">{{  __('adminWords.paypal_secret') }}<sup>*</sup></label>
                    <input type="password" name="paypal_secret" value="{{ (isset($artistApi['paypal_secret']) ? $artistApi['paypal_secret']:'')}}" id="paypalsecret" class="form-control">
                    <span toggle="#paypalsecret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                    <small class="text-danger">{{ $errors->first('paypal_secret') }}</small>
                    </div>
                    <div class="form-group{{ $errors->has('paypal_mode') ? ' has-error' : '' }}">
                    <label for="paypal_mode">{{  __('adminWords.paypal_mode') }}<sup>*</sup></label>
                    {!! Form::text('paypal_mode',isset($artistApi['paypal_mode']) ? $artistApi['paypal_mode'] : '', ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('paypal_mode') }}</small>
                    </div>
                    <div class="form-group dd-flex">
                        <label for="paypal" class="mb-0 mt-2">{{ __('adminWords.make_this_default') }}</label>
                        <div class="radio radio-primary ml-4">
                            {!! Form::radio('default_pay_gateway', 'paypal', (isset($artistApi['default_pay_gateway']) && $artistApi['default_pay_gateway'] == 'paypal' ? 'checked' :  ''), ['id'=>'paypal','class'=>'selectArtistGateway']) !!}
                            <label for="paypal"></label>
                        </div>                 
                    </div>
                </div> 
                <div class="col-lg-10"> 
                    <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm">{{ __('adminWords.save_setting_btn') }}</button>
                    <div class="clear-both"></div>
                </div>
            </div> 
          </div>
        </div>
      {!! Form::close() !!}

      {!! Form::open(['method' => 'POST', 'route'=>['artist.api.update','stripe']]) !!}
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.stripe_settings') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group">
                  <div class="custom-switch checkbox mr-4">
                    {!! Form::checkbox('is_stripe', 1, isset($artistApi['is_stripe']) ? $artistApi['is_stripe'] : null, ['class' => 'updateSettingRecords', 'data-id'=>"stripe_box", 'required-id'=>'#stripe_client_id, #stripe_secret', 'data-type'=>'is_stripe', 'id'=>'stripe_check', 'data-name' => 'stripe']) !!}
                    <label for="stripe_check">{{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                  </div>                  
                </div>
              </div>
            </div>
          </div>
          <div id="stripe_box" class="card-body">
            <div class="row">                    
                <div class="col-lg-10">
                    <div class="form-group{{ $errors->has('stripe_client_id') ? ' has-error' : '' }}">
                    <label for="stripe_client_id">{{  __('adminWords.stripe_id') }}<sup>*</sup></label>
                    {!! Form::text('stripe_client_id', (isset($artistApi['stripe_client_id']) ? $artistApi['stripe_client_id'] : null), ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('stripe_client_id') }}</small>
                    </div>                      
                    <div class="form-group{{ $errors->has('stripe_secret') ? ' has-error' : '' }}">
                    <label for="stripe_secret">{{  __('adminWords.stripe_secret') }}<sup>*</sup></label>
                    <input type="password" name="stripe_secret" value="{{(isset($artistApi['stripe_secret']) ? $artistApi['stripe_secret']:'')}}" id="stripe_secret" class="form-control">
                    <span toggle="#stripe_secret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                    <small class="text-danger">{{ $errors->first('stripe_secret') }}</small>
                    </div>
                    <div class="form-group dd-flex">
                        <label for="stripe" class="mb-0 mt-2">{{ __('adminWords.make_this_default') }}</label> 
                        <div class="radio radio-primary ml-4">
                            {!! Form::radio('default_pay_gateway', 'stripe', (isset($artistApi['default_pay_gateway']) && $artistApi['default_pay_gateway'] == 'stripe' ? 'checked' :  ''), ['id'=>'stripe','class'=>'selectArtistGateway']) !!}
                            <label for="stripe"></label>
                        </div>
                    </div>
                </div>   
                <div class="col-lg-10"> 
                    <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm">{{ __('adminWords.save_setting_btn') }}</button>
                    <div class="clear-both"></div>
                </div>
            </div>
          </div>
        </div>
      {!! Form::close() !!}
		
        {!! Form::open(['method' => 'POST', 'route'=>['artist.api.update','paystack']]) !!}
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.paystack_setting') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group">
                  <div class="custom-switch checkbox mr-4">
                    {!! Form::checkbox('is_paystack', 1, isset($artistApi['is_paystack']) ? $artistApi['is_paystack'] : null, ['class' => 'updateSettingRecords', 'data-id'=>"paystack_box", 'required-id'=>'#paystack_public_key, #paystack_secret_key, #paystack_payment_url, #paystack_merchant_email', 'data-type'=>'is_paystack', 'id'=>'paystack_check', 'data-name' => 'paystack']) !!}
                    <label class="custom-control-label" for="paystack_check">{{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                  </div>
                </div>
              </div>
            </div> 
          </div>
          <div id="paystack_box" class="card-body">
            <div class="row">                    
              <div class="col-lg-10">
                <div class="form-group{{ $errors->has('paystack_public_key') ? ' has-error' : '' }}">
                  <label for="paystack_public_key">{{  __('adminWords.paystack_public_key') }}<sup>*</sup></label>
                  {!! Form::text('paystack_public_key', (isset($artistApi['paystack_public_key']) ? $artistApi['paystack_public_key'] : null), ['class' => 'form-control','id' => 'paystack_public_key','placeholder' => __('adminWords.paystack_public_key') ]) !!}
                  <small class="text-danger">{{ $errors->first('paystack_public_key') }}</small>
                </div>                      
                <div class="form-group{{ $errors->has('paystack_secret_key') ? ' has-error' : '' }}">
                  <label for="paystack_secret_key">{{  __('adminWords.paystack_secret') }}<sup>*</sup></label>
                  <input type="password" name="paystack_secret_key" value="{{(isset($artistApi['paystack_secret_key']) ? $artistApi['paystack_secret_key']:'')}}" class="form-control" id="paystack_secret_key" placeholder="{{ __('adminWords.paystack_secret') }}">
                  <span toggle="#paystack_secret_key" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                  <small class="text-danger">{{ $errors->first('paystack_secret_key') }}</small>
                </div>
                <div class="form-group{{ $errors->has('paystack_payment_url') ? ' has-error' : '' }}">
                  <label for="paystack_payment_url">{{  __('adminWords.paystack_url') }}<sup>*</sup></label>
                  <input type="text" name="paystack_payment_url" value="{{(isset($artistApi['paystack_payment_url']) ? $artistApi['paystack_payment_url']:'')}}" class="form-control" data-valid="url" data-error="Invalid URL." id="paystack_payment_url" placeholder="{{ __('adminWords.paystack_url') }}">
                  <small class="text-danger">{{ $errors->first('paystack_payment_url') }}</small>
                </div>
                <div class="form-group{{ $errors->has('paystack_merchant_email') ? ' has-error' : '' }}">
                  <label for="paystack_merchant_email">{{  __('adminWords.merchant_email') }}<sup>*</sup></label>
                  <input type="text" name="paystack_merchant_email" value="{{(isset($artistApi['paystack_merchant_email']) ? $artistApi['paystack_merchant_email']:'')}}" class="form-control" data-valid="email" data-error="Invalid email." id="paystack_merchant_email" placeholder="{{ __('adminWords.paystack_url') }}">
                  <small class="text-danger">{{ $errors->first('merchant_email') }}</small>
                </div>
                <div class="form-group dd-flex">
                    <label for="paystack" class="mb-0 mt-2">{{ __('adminWords.make_this_default') }}</label> 
                    <div class="radio radio-primary ml-4">
                        {!! Form::radio('default_pay_gateway', 'paystack', (isset($artistApi['default_pay_gateway']) && $artistApi['default_pay_gateway'] == 'paystack' ? 'checked' :  ''), ['id'=>'paystack','class'=>'selectArtistGateway']) !!}
                        <label for="paystack"></label>
                        
                    </div>
                </div>
              </div>                  
              <div class="col-lg-10"> 
                <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm">{{ __('adminWords.save_setting_btn') }}</button>
                <div class="clear-both"></div>
              </div>
            </div>
          </div>
        </div>
        {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection 
@section('script')
  <script src="{{ asset('public/assets/plugins/switchery/switch.min.js') }}"></script>  
  <script src="{{asset('public/assets/js/artist-custom.js?'.time())}}"></script>
@endsection

