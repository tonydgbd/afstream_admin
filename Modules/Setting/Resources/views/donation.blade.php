@extends('layouts.admin.main')
@section('title', __('adminWords.paypal_donation'))
@section('rightbar-content')
           
<div class="breadcrumbbar paypalDonationAccordation">
  <div class="row align-items-center">
    <div class="col-lg-8 col-lg-8">
      <h4 class="page-title">{{ __('adminWords.paypal_donation') }}</h4>
      <div class="breadcrumb-list">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('adminWords.home') }}</a></li>
          <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('adminWords.paypal_donation') }}</a></li>
        </ol>
      </div>
    </div>
  </div>          
</div>
<div class="contentbar">  
  <div class="row">
    <div class="col-lg-12">
      {!! Form::open(['method' => 'POST', 'route'=>'donation.save' ]) !!}
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.paypal_donation') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group">
                  <div class="custom-switch">
                    {!! Form::checkbox('paypal_donation', 1, isset($settings['paypal_donation']) ? $settings['paypal_donation'] : null, ['class' => 'custom-control-input updateSettingRecords', 'data-id'=>"paypal_donation_box", 'required-id'=>'#PAYPAL_DONATION_LINK', 'data-type'=>'paypal_donation', 'id'=>'paypal_donation_check']) !!}
                    <label class="custom-control-label" for="paypal_donation_check"></label>
                    <input type="hidden" value="{{route('updateStatus')}}" id="URL">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="paypal_donation_box" class="card-body">
            <div class="row">                    
              <div class="col-lg-10">
                <div class="form-group{{ $errors->has('PAYPAL_DONATION_LINK') ? ' has-error' : '' }}">
                  {!! Form::label('PAYPAL_DONATION_LINK', __('adminWords.paypal_donation').' '.__('adminWords.link')) !!}
                  {!! Form::text('PAYPAL_DONATION_LINK', (env('PAYPAL_DONATION_LINK') ? env('PAYPAL_DONATION_LINK') : null), ['class' => 'form-control require', 'data-valid' => 'url', 'data-error'=>'Invalid link.']) !!}
                  <small class="text-danger">{{ $errors->first('PAYPAL_DONATION_LINK') }}</small>
                </div>                      
               
              </div>                  
              <div class="col-lg-10"> 
                <button type="button" class="btn btn-success" data-action="submitThisForm">{{ __('adminWords.save_setting_btn') }}</button>
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
  <script src="{{asset('public/assets/js/musioo-custom.js?'.time())}}"></script>
@endsection

