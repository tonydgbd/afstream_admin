@extends('layouts.admin.main')
@section('title', __('adminWords.payment').' '.__('adminWords.setting'))
@section('style')
  <link href="{{ asset('public/public/assets/plugins/switchery/switch.min.css') }}" rel="stylesheet" type="text/css">
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
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.payment').' '.__('adminWords.setting') }}</li>
                </ul>
                <input type="hidden" value="{{route('updateStatus')}}" id="URL">
            </div>
        </div>
    </div>
</div>

<div class="contentbar">  
  <div class="row">
    <div class="col-lg-12">
      {!! Form::open(['method' => 'POST', 'route'=>['api.update','razor']]) !!}
        <div class="card m-b-30">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8">
                        <h5>{{ __('adminWords.razorpay_setting') }}</h5>
                    </div>
                    <div class="col-lg-4 text-right">
                        <div class="form-group">
                            <div class="custom-switch checkbox mr-4">
                                {!! Form::checkbox('is_razorpay', 1, isset($settings['is_razorpay']) ? $settings['is_razorpay'] : null, ['class' => ' updateSettingRecords', 'data-id'=>"razorpay_box", 'required-id'=>'#RAZORPAY_KEY, #RAZORPAY_SECRET', 'data-type'=>'is_razorpay', 'id'=>'razorpay_check', 'data-name' => 'razorpay']) !!}
                                <label for="razorpay_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                            </div> 
                        </div>
                    </div>                
                </div>
            </div>
          <div id="razorpay_box" class="card-body">
            <div class="row">                    
              <div class="col-lg-10">
                <div class="form-group{{ $errors->has('RAZORPAY_KEY') ? ' has-error' : '' }}">
                  <label for="RAZORPAY_KEY">{{  __('adminWords.razorpay_key') }}<sup>*</sup></label>
                  {!! Form::text('RAZORPAY_KEY', (env('RAZORPAY_KEY') ? env('RAZORPAY_KEY') : null), ['class' => 'form-control']) !!}
                  <small class="text-danger">{{ $errors->first('RAZORPAY_KEY') }}</small>
                </div>                      
                <div class="form-group{{ $errors->has('RAZORPAY_SECRET') ? ' has-error' : '' }}">
                  <label for="RAZORPAY_SECRET">{{  __('adminWords.razorpay_secret') }}<sup>*</sup></label>
                  <input type="password" name="RAZORPAY_SECRET" value="{{ env('RAZORPAY_SECRET') }}" id="razorsecret" class="form-control">
                  <span toggle="#razorsecret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                  <small class="text-danger">{{ $errors->first('RAZORPAY_SECRET') }}</small>
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
      
      {!! Form::open(['method' => 'POST', 'route'=>['api.update','paypal']]) !!}
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.paypal_setting') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group">
                  <div class="custom-switch checkbox mr-4">
                    {!! Form::checkbox('is_paypal', 1, isset($settings['is_paypal']) ? $settings['is_paypal'] : null, ['class' => ' updateSettingRecords','required-id'=>'#PAYPAL_CLIENT_ID, #paypalsecret, #PAYPAL_MODE', 'data-type'=>'is_paypal', 'data-id'=>"paypal_box", 'id'=>'paypal_check', 'data-name' => 'paypal']) !!}     
                    <label for="paypal_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>               
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="paypal_box" class="card-body">
            <div class="row">                    
              <div class="col-lg-10">
                <div class="form-group{{ $errors->has('PAYPAL_CLIENT_ID') ? ' has-error' : '' }}">
                  <label for="PAYPAL_CLIENT_ID">{{  __('adminWords.paypal_id') }}<sup>*</sup></label>
                  {!! Form::text('PAYPAL_CLIENT_ID', env('PAYPAL_CLIENT_ID'), ['class' => 'form-control']) !!}
                  <small class="text-danger">{{ $errors->first('PAYPAL_CLIENT_ID') }}</small>
                </div>                      
                <div class="form-group{{ $errors->has('PAYPAL_SECRET') ? ' has-error' : '' }}">
                  <label for="PAYPAL_SECRET">{{  __('adminWords.paypal_secret') }}<sup>*</sup></label>
                  <input type="password" name="PAYPAL_SECRET" value="{{ env('PAYPAL_SECRET') }}" id="paypalsecret" class="form-control">
                  <span toggle="#paypalsecret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                  <small class="text-danger">{{ $errors->first('PAYPAL_SECRET') }}</small>
                </div>
                <div class="form-group{{ $errors->has('PAYPAL_MODE') ? ' has-error' : '' }}">
                  <label for="PAYPAL_MODE">{{  __('adminWords.paypal_mode') }}<sup>*</sup></label>
                  {!! Form::text('PAYPAL_MODE', env('PAYPAL_MODE'), ['class' => 'form-control']) !!}
                  <small class="text-danger">{{ $errors->first('PAYPAL_MODE') }}</small>
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

      {!! Form::open(['method' => 'POST', 'route'=>['api.update','stripe']]) !!}
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.stripe_settings') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group">
                  <div class="custom-switch checkbox mr-4">
                    {!! Form::checkbox('is_stripe', 1, isset($settings['is_stripe']) ? $settings['is_stripe'] : null, ['class' => 'updateSettingRecords', 'data-id'=>"stripe_box", 'required-id'=>'#STRIPE_CLIENT_ID, #STRIPE_SECRET', 'data-type'=>'is_stripe', 'id'=>'stripe_check', 'data-name' => 'stripe']) !!}
                    <label for="stripe_check">{{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                  </div>                  
                </div>
              </div>
            </div>
          </div>
          <div id="stripe_box" class="card-body">
            <div class="row">                    
              <div class="col-lg-10">
                <div class="form-group{{ $errors->has('STRIPE_CLIENT_ID') ? ' has-error' : '' }}">
                  <label for="STRIPE_CLIENT_ID">{{  __('adminWords.stripe_id') }}<sup>*</sup></label>
                  {!! Form::text('STRIPE_CLIENT_ID', (env('STRIPE_CLIENT_ID') ? env('STRIPE_CLIENT_ID') : null), ['class' => 'form-control','placeholder'=> __('adminWords.stripe_id')]) !!}
                  <small class="text-danger">{{ $errors->first('STRIPE_CLIENT_ID') }}</small>
                </div>                      
                <div class="form-group{{ $errors->has('STRIPE_SECRET') ? ' has-error' : '' }}">
                  <label for="STRIPE_SECRET">{{  __('adminWords.stripe_secret') }}<sup>*</sup></label>
                  <input type="password" name="STRIPE_SECRET" value="{{ env('STRIPE_SECRET') }}" id="stripe_secret" class="form-control" placeholder="{{ __('adminWords.stripe_secret') }}">
                  <span toggle="#stripe_secret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                  <small class="text-danger">{{ $errors->first('STRIPE_SECRET') }}</small>
                </div>

                <div class="form-group{{ $errors->has('STRIPE_MERCHANT_DISPLAY_NAME') ? ' has-error' : '' }}">
                  <label for="STRIPE_MERCHANT_DISPLAY_NAME">{{  __('adminWords.merchant_display_name') }}<sup>*</sup></label> 
                  {!! Form::text('STRIPE_MERCHANT_DISPLAY_NAME', (env('STRIPE_MERCHANT_DISPLAY_NAME') ? env('STRIPE_MERCHANT_DISPLAY_NAME') : null), ['class' => 'form-control','id'=>'MERCHANT_DISPLAY_NAME','placeholder'=> __('adminWords.merchant_display_name') ]) !!}
                  <small class="text-danger">{{ $errors->first('STRIPE_MERCHANT_DISPLAY_NAME') }}</small>
                </div> 

                <div class="form-group{{ $errors->has('STRIPE_MERCHANT_COUNTRY_CODE') ? ' has-error' : '' }}">
                  <label for="STRIPE_MERCHANT_COUNTRY_CODE">{{  __('adminWords.merchant_country_code') }}<sup>*</sup></label>
                  <input type="text" name="STRIPE_MERCHANT_COUNTRY_CODE" value="{{ env('STRIPE_MERCHANT_COUNTRY_CODE') }}" id="merchant_country_code" class="form-control" placeholder="{{ __('adminWords.merchant_country_code') }}">
                  <small class="text-danger">{{ $errors->first('STRIPE_MERCHANT_COUNTRY_CODE') }}</small>
                </div>

                <div class="form-group{{ $errors->has('STRIPE_MERCHANT_IDENTIFIER') ? ' has-error' : '' }}">
                  <label for="STRIPE_MERCHANT_IDENTIFIER">{{  __('adminWords.merchant_identifier') }}<sup>*</sup></label>
                  {!! Form::text('STRIPE_MERCHANT_IDENTIFIER', (env('STRIPE_MERCHANT_IDENTIFIER') ? env('STRIPE_MERCHANT_IDENTIFIER') : null), ['class' => 'form-control','id'=>'STRIPE_MERCHANT_IDENTIFIER','placeholder'=>__('adminWords.merchant_identifier')]) !!}
                  <small class="text-danger">{{ $errors->first('STRIPE_MERCHANT_IDENTIFIER') }}</small>
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

      {!! Form::open(['method' => 'POST', 'route'=>['api.update','paystack']]) !!}
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.paystack_setting') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group">
                  <div class="custom-switch checkbox mr-4">
                    {!! Form::checkbox('is_paystack', 1, isset($settings['is_paystack']) ? $settings['is_paystack'] : null, ['class' => 'updateSettingRecords', 'data-id'=>"paystack_box", 'required-id'=>'#PAYSTACK_PUBLIC_KEY, #PAYSTACK_SECRET_KEY, #PAYSTACK_PAYMENT_URL, #MERCHANT_EMAIL', 'data-type'=>'is_paystack', 'id'=>'paystack_check', 'data-name' => 'paystack']) !!}
                    <label class="custom-control-label" for="paystack_check">{{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="paystack_box" class="card-body">
            <div class="row">                    
              <div class="col-lg-10">
                <div class="form-group{{ $errors->has('PAYSTACK_PUBLIC_KEY') ? ' has-error' : '' }}">
                  <label for="PAYSTACK_PUBLIC_KEY">{{  __('adminWords.paystack_public_key') }}<sup>*</sup></label>
                  {!! Form::text('PAYSTACK_PUBLIC_KEY', (env('PAYSTACK_PUBLIC_KEY') ? env('PAYSTACK_PUBLIC_KEY') : null), ['class' => 'form-control']) !!}
                  <small class="text-danger">{{ $errors->first('PAYSTACK_PUBLIC_KEY') }}</small>
                </div>                      
                <div class="form-group{{ $errors->has('PAYSTACK_SECRET_KEY') ? ' has-error' : '' }}">
                  <label for="PAYSTACK_SECRET_KEY">{{  __('adminWords.paystack_secret') }}<sup>*</sup></label>
                  <input type="password" name="PAYSTACK_SECRET_KEY" value="{{ env('PAYSTACK_SECRET_KEY') }}" class="form-control" id="PAYSTACK_SECRET_KEY">
                  <span toggle="#PAYSTACK_SECRET_KEY" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                  <small class="text-danger">{{ $errors->first('PAYSTACK_SECRET_KEY') }}</small>
                </div>
                <div class="form-group{{ $errors->has('PAYSTACK_PAYMENT_URL') ? ' has-error' : '' }}">
                  <label for="PAYSTACK_PAYMENT_URL">{{  __('adminWords.paystack_url') }}<sup>*</sup></label>
                  <input type="text" name="PAYSTACK_PAYMENT_URL" value="{{ env('PAYSTACK_PAYMENT_URL') }}" class="form-control" data-valid="url" data-error="Invalid URL.">
                  <small class="text-danger">{{ $errors->first('PAYSTACK_PAYMENT_URL') }}</small>
                </div>
                <div class="form-group{{ $errors->has('MERCHANT_EMAIL') ? ' has-error' : '' }}">
                  <label for="MERCHANT_EMAIL">{{  __('adminWords.merchant_email') }}<sup>*</sup></label>
                  <input type="text" name="MERCHANT_EMAIL" value="{{ env('MERCHANT_EMAIL') }}" class="form-control" data-valid="email" data-error="Invalid email.">
                  <small class="text-danger">{{ $errors->first('MERCHANT_EMAIL') }}</small>
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
      
      <!--{!! Form::open(['method' => 'POST', 'route'=>['api.update','manual_pay']]) !!}-->
      <!--  <div class="card m-b-30">-->
      <!--    <div class="card-header">-->
      <!--      <div class="row">-->
      <!--        <div class="col-lg-8">-->
      <!--          <h5>{{ __('adminWords.manual_pay').' '.__('adminWords.setting') }}</h5>-->
      <!--        </div>-->
      <!--        <div class="col-lg-4 text-right">-->
      <!--          <div class="form-group">-->
                  
      <!--            <div class="custom-switch checkbox mr-4">-->
      <!--              {!! Form::checkbox('is_manual_pay', 1, isset($settings['is_manual_pay']) ? $settings['is_manual_pay'] : null, ['class' => 'updateSettingRecords', 'data-id'=>"manual_box", 'required-id'=>'#BANK_NAME, #BRANCH_NAME, #IFSC_CODE, #SWIFT_CODE, #ACCOUNT_NUMBER, #ACCOUNT_NAME', 'data-type'=>'is_manual', 'id'=>'manual_check', 'data-name' => 'stripe']) !!}-->
      <!--              <label for="manual_check">{{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>-->
      <!--            </div> -->
      <!--          </div>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--    </div>-->
      <!--    <div id="manual_box" class="card-body">-->
      <!--      <div class="row">                    -->
      <!--        <div class="col-lg-10">-->
      <!--            <div id="bank_transfer_field">-->
      <!--              <div class="form-group{{ $errors->has('BANK_NAME') ? ' has-error' : '' }}">-->
      <!--                <label for="BANK_NAME">{{ __('adminWords.bank_name') }}<sup>*</sup></label>-->
      <!--                {!! Form::text('BANK_NAME', isset($settings['BANK_NAME']) ? $settings['BANK_NAME'] : null, ['class' => 'form-control require']) !!}-->
      <!--                <small class="text-danger">{{ $errors->first('BANK_NAME') }}</small>-->
      <!--              </div>                      -->
      <!--              <div class="form-group{{ $errors->has('BRANCH_NAME') ? ' has-error' : '' }}">-->
      <!--                <label for="BRANCH_NAME">{{ __('adminWords.branch_name') }}<sup>*</sup></label>-->
      <!--                {!! Form::text('BRANCH_NAME', isset($settings['BRANCH_NAME']) ? $settings['BRANCH_NAME'] : null, ['class' => 'form-control require']) !!}-->
      <!--                <small class="text-danger">{{ $errors->first('BRANCH_NAME') }}</small>-->
      <!--              </div>                      -->
      <!--              <div class="form-group{{ $errors->has('IFSC_CODE') ? ' has-error' : '' }}">-->
      <!--                <label for="IFSC_CODE">{{ __('adminWords.ifsc_code') }}</label>-->
      <!--                <input type="text" name="IFSC_CODE" value="{{ isset($settings['IFSC_CODE']) ? $settings['IFSC_CODE'] : null }}" class="form-control" id="IFSC_CODE">-->
      <!--                <small class="text-danger">{{ $errors->first('IFSC_CODE') }}</small>-->
      <!--              </div>-->
      <!--              <div class="form-group{{ $errors->has('SWIFT_CODE') ? ' has-error' : '' }}">-->
      <!--                <label for="SWIFT_CODE">{{ __('adminWords.swift_code') }}</label>-->
      <!--                <input type="text" name="SWIFT_CODE" value="{{ isset($settings['SWIFT_CODE']) ? $settings['SWIFT_CODE'] : null }}" class="form-control" id="SWIFT_CODE">-->
      <!--                <small class="text-danger">{{ $errors->first('SWIFT_CODE') }}</small>-->
      <!--              </div>-->
      <!--              <div class="form-group{{ $errors->has('ACCOUNT_NUMBER') ? ' has-error' : '' }}">-->
      <!--                <label for="ACCOUNT_NUMBER">{{ __('adminWords.acc_no') }}<sup>*</sup></label>-->
      <!--                <input type="text" name="ACCOUNT_NUMBER" value="{{ isset($settings['ACCOUNT_NUMBER']) ? $settings['ACCOUNT_NUMBER'] : null }}" class="form-control require" id="ACCOUNT_NUMBER">-->
      <!--                <small class="text-danger">{{ $errors->first('ACCOUNT_NUMBER') }}</small>-->
      <!--              </div>-->
      <!--              <div class="form-group{{ $errors->has('ACCOUNT_NAME') ? ' has-error' : '' }}">-->
      <!--                <label for="ACCOUNT_NAME">{{ __('adminWords.acc_name') }}<sup>*</sup></label>-->
      <!--                <input type="text" name="ACCOUNT_NAME" value="{{ isset($settings['ACCOUNT_NAME']) ? $settings['ACCOUNT_NAME'] : null }}" class="form-control require" id="ACCOUNT_NAME">-->
      <!--                <small class="text-danger">{{ $errors->first('ACCOUNT_NAME') }}</small>-->
      <!--              </div>-->
      <!--            </div>-->
      <!--          </div>                  -->
      <!--          <div class="col-lg-10"> -->
      <!--            <button type="button" class="btn btn-success" data-action="submitThisForm">{{ __('adminWords.save_setting_btn') }}</button>-->
      <!--            <div class="clear-both"></div>-->
      <!--          </div>-->
      <!--      </div>-->
      <!--    </div>-->
      <!--  </div>-->
      <!--{!! Form::close() !!}-->
      
    </div>
  </div>
</div>
@endsection 
@section('script')
  <script src="{{ asset('public/assets/plugins/switchery/switch.min.js') }}"></script> 
  <script src="{{asset('public/assets/js/musioo-custom.js?'.time())}}"></script>
@endsection

