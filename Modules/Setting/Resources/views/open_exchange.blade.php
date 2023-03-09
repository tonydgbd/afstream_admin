@extends('layouts.admin.main')
@section('title', __('adminWords.open_exchange').' '.__('adminWords.setting') )
@section('content')               

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.open_exchange').' '.__('adminWords.setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.open_exchange').' '.__('adminWords.setting') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="contentbar">  
  <div class="row">
    <div class="col-lg-12">
        <div class="card m-b-30 add-form hide-block">
            <div class="card m-b-30">
                <div class="card-header">                                
                    <div class="row align-items-center">
                        <div class="col-6">
                        <h5 class="card-title mb-0">
                            <svg height="22" width="23" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 469.333 469.333" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M248.533,192c-17.6-49.707-64.853-85.333-120.533-85.333c-70.72,0-128,57.28-128,128s57.28,128,128,128    c55.68,0,102.933-35.627,120.533-85.333h92.8v85.333h85.333v-85.333h42.667V192H248.533z M128,277.333    c-23.573,0-42.667-19.093-42.667-42.667S104.427,192,128,192c23.573,0,42.667,19.093,42.667,42.667S151.573,277.333,128,277.333z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g></g></svg>
                            {{ __('adminWords.open_exchange').' '.__('adminWords.setting') }}
                        </h5>
                        </div>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                        {!! Form::open(['method' => 'POST', 'route'=>'open_exchange.save']) !!}
                            <div class="open_exchange_key form-group{{ $errors->has('OPEN_EXCHANGE_KEY') ? ' has-error' : '' }}">
                              <label for="OPEN_EXCHANGE_KEY">{{ __('adminWords.open_exchange') }}<sup>*</sup></label>
                              <a class="float-right" href="https://openexchangerates.org/signup/free" target="_blank">
                                <svg height="22" width="23" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 469.333 469.333" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M248.533,192c-17.6-49.707-64.853-85.333-120.533-85.333c-70.72,0-128,57.28-128,128s57.28,128,128,128    c55.68,0,102.933-35.627,120.533-85.333h92.8v85.333h85.333v-85.333h42.667V192H248.533z M128,277.333    c-23.573,0-42.667-19.093-42.667-42.667S104.427,192,128,192c23.573,0,42.667,19.093,42.667,42.667S151.573,277.333,128,277.333z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g></g></svg> 
                                 Click here to Get your currency converter key
                              </a>
                              {!! Form::text('OPEN_EXCHANGE_KEY', (env('OPEN_EXCHANGE_KEY') ? env('OPEN_EXCHANGE_KEY') : null), ['class' => 'form-control require', 'placeholder' => __('adminWords.enter').' '.__('adminWords.open_exchange') ]) !!}
                              <small class="text-danger">{{ $errors->first('OPEN_EXCHANGE_KEY') }}</small>
                            </div>  
                            
                            <button type="button" data-action="submitThisForm" class="effect-btn btn btn-primary mt-2 mr-2">{{ __('adminWords.save_setting_btn') }}</button>
                            <div class="clear-both"></div>
                        {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 