@extends('layouts.admin.main')
@section('title', __('adminWords.coupon'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')  

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ isset($couponData) ? __('adminWords.update').' '.__('adminWords.coupon') : __('adminWords.create').' '.__('adminWords.coupon') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.coupon') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="contentbar">                
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header">   
                    <div class="text-right">   
                        <a class="effect-btn btn btn-primary" href="{{ url('coupon_management') }}">{{ __('adminWords.go_back') }}</a>
                    </div>                              
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title mb-0">{{ isset($couponData) ? __('adminWords.update').' '.__('adminWords.coupon') : __('adminWords.create').' '.__('adminWords.coupon') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <div class="admin-form">
                    @if(isset($couponData))
                      {!! Form::model($couponData, ['method'=>'post', 'files'=>true, 'route'=>['addEditCoupon', $couponData->id], 'id'=>'updateAudio', 'onsubmit'=>'return false', 'data-redirect' => url('/coupon_management') ]) !!}
                    @else
                      {!! Form::open(['method' => 'post', 'route'=>['addEditCoupon','add'], 'data-reset'=>1, 'files' => true, 'onsubmit'=>'return false', 'data-redirect' => url('/coupon_management')]) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-6"> 
                            <div class="form-group{{$errors->has('coupon_code') ? 'has-error' : ''}}">
                              <label for="coupon_code">{{ __('adminWords.coupon_code') }}<sup>*</sup></label>
                              {!! Form::text('coupon_code', null, ['class' => 'form-control require', 'required', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.coupon_code') ]) !!}
                              <small class="text-danger">{{ $errors->first('coupon_code')}}</small>
                            </div>
                            
                            <div class="form-group{{ $errors->has('discount_type') ? ' has-error' : '' }}">
                                <label for="discount_type">{{ __('adminWords.select').' '.__('adminWords.discount_type') }}<sup>*</sup></label>
                                {!! Form::select('discount_type', [1 => 'Fix price', 2 => 'Percentage (%)' ], (isset($couponData) ? $couponData->audio_genre_id : ''), ['class' => 'form-control select2WithSearch require','placeholder' => __('adminWords.choose') ]) !!}
                                <small class="text-danger">{{ $errors->first('discount_type') }}</small>
                            </div> 
                            <div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }}">
                                <label for="discount">{{ __('adminWords.discount') }}<sup>*</sup></label>
                                {!! Form::number('discount', null, ['class' => 'form-control checkDiscountLimit require', 'required', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.discount'), 'max' => '100' ]) !!}
                                <small class="text-danger">{{ $errors->first('discount')}}</small>
                            </div> 
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                {!! Form::label('description', __('adminWords.description') ) !!}
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.description'), 'rows'=>'3']) !!}
                                <small class="text-danger">{{ $errors->first('description') }}</small>
                            </div>

                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} switch-main-block">                                  
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('status', 1, (isset($couponData) &&   $couponData->status == 0 ? 0 : 1),['id'=>'status']) !!}
                                    {!! Form::label('status', __('adminWords.status')) !!}
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                </div> 
                            </div>  
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group{{ $errors->has('coupon_used_count') ? ' has-error' : '' }}">
                                <label for="coupon_used_count">{{ __('adminWords.coupon_used_count') }}<sup>*</sup></label>
                                {!! Form::number('coupon_used_count', null, ['class' => 'form-control require', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.coupon_count')]) !!}
                                <small class="text-danger">{{ $errors->first('coupon_used_count') }}</small>
                            </div> 
                            <div class="form-group{{$errors->has('starting_date') ? 'has-error' : ''}}">
                                <label for="starting_date">{{ __('adminWords.starting_date') }}<sup>*</sup></label>
                                <div class="input-group">
                                    {!! Form::text('starting_date', (isset($couponData) && $couponData->starting_date !='' ? $couponData->starting_date : ''), ['class' =>'form-control autoclose-date require', 'data-language'=>'en', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.starting_date')]) !!}
                                    <div class="input-group-append">
                                    
                                    </div>
                                    <small class="text-danger">{{ $errors->first('starting_date') }}</small>
                                </div>
                            </div>
                            <div class="form-group{{$errors->has('expiry_date') ? 'has-error' : ''}}">
                                <label for="expiry_date">{{ __('adminWords.expiry_date') }}<sup>*</sup></label>
                                <div class="input-group">
                                    {!! Form::text('expiry_date', (isset($couponData) && $couponData->expiry_date !='' ? $couponData->expiry_date : ''), ['class' =>'form-control autoclose-date require', 'data-language'=>'en', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.expiry_date')]) !!}
                                    <div class="input-group-append">
                                    
                                    </div>
                                    <small class="text-danger">{{ $errors->first('expiry_date') }}</small>
                                </div>
                            </div>
                        

                            <div class="form-group dd-flex">
                                <label for="applicable_on">{{ __('adminWords.applicable_on') }}<sup>*</sup></label>
                                <div class="radio radio-primary mr-4 ml-4">
                                  {!! Form::radio('applicable_on', 0, (isset($couponData) && $couponData->applicable_on == 0 ? 'checked' : (!isset($couponData) ? 'checked' : '')), ['id'=>__('adminWords.all_plans')]) !!}
                                  {!! Form::label(__('adminWords.all_plans'), null, ['class' => 'mb-0']) !!}
                                </div>
                                <div class="radio radio-primary mr-4">
                                  {!! Form::radio('applicable_on', 1, (isset($couponData) && $couponData->applicable_on == 1 ? 'checked' : ''), ['id'=>__('adminWords.particular_plan')]) !!}
                                  {!! Form::label(__('adminWords.particular_plan'), null, ['class' => 'mb-0']) !!}
                                </div>                          
                            </div>


                            @if(isset($couponData) && $couponData->applicable_on == 1 && !empty($couponData->plan_id && $couponData->plan_id != '[]'))
                                @php 
                                    $plan_id = json_decode($couponData->plan_id);
                                    $addedPlan = \Modules\Plan\Entities\Plan::whereIn('id',$plan_id)->get()->toArray();
                                @endphp
                            <div class="form-group{{ $errors->has('plan_id') ? ' has-error' : '' }} applicable_plan">
                                {!! Form::label('plan_id', __('adminWords.select').' '.__('adminWords.plan') ) !!}
                                <select name="plan_id[]" id="planId" class="form-control multipleSelectWithSearch" placeholder="{{ __('adminWords.choose') }}" multiple >
                                    @php
                                        if(sizeof($plan) > 0){
                                            foreach($plan as $key=>$value){
                                    @endphp
                                        <option value="{{ $key }}" @if(!empty($addedPlan)) @foreach($addedPlan as $plans) {{ $key == $plans["id"] ? "selected" : "" }} @endforeach @endif>{{ $value }}</option>';
                                    @php            
                                            }
                                        }
                                    @endphp

                                </select>
                                <small class="text-danger">{{ $errors->first('plan_id') }}</small>
                                
                            </div> 
                            @else
                            <div class="form-group{{ $errors->has('plan_id') ? ' has-error' : '' }} d-none applicable_plan">
                                {!! Form::label('plan_id', __('adminWords.select').' '.__('adminWords.plan') ) !!}
                                <select name="plan_id[]" id="planId" class="form-control multipleSelectWithSearch" placeholder="{{ __('adminWords.choose') }}" multiple >
                                    @php
                                        if(sizeof($plan) > 0){
                                            foreach($plan as $key=>$value){
                                                echo '<option value="'.$key.'">'.$value.'</option>';
                                            }
                                        }
                                    @endphp

                                </select>
                                <small class="text-danger">{{ $errors->first('plan_id') }}</small>
                                
                            </div> 
                            @endif
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group"> 
                            @if(!isset($couponData))
                                <button type="reset" class="effect-btn btn btn-danger"> {{ __('adminWords.reset') }}</button>
                            @endif  
                            <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{isset($couponData) ? __('adminWords.update') : __('adminWords.add') }}</button>  
                            </div>
                            <div class="clear-both"></div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
@section('script')
<script src="{{ asset('public/assets/plugins/datepicker/datepicker.min.js') }}"></script> 
<script src="{{ asset('public/assets/plugins/datepicker/i18n/datepicker.en.js') }}"></script> 
<script src="{{ asset('public/assets/plugins/select2/select2.min.js') }}"></script>  
<script src="{{ asset('public/assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script> 
<script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  

@endsection
