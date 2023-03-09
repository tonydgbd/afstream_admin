@extends('layouts.admin.main')
@section('title', __('adminWords.tax').' '.__('adminWords.setting') )
@section('content')
              
<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.tax').' '.__('adminWords.setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list taxCommissionAccordation">
                <ul> 
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.tax').' '.__('adminWords.setting') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="contentbar">  
    <div class="row">
        <div class="col-lg-12">
        {!! Form::open(['method' => 'POST', 'route'=>'tax.save' ]) !!}
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <h5>{{ __('adminWords.set_tax') }}</h5>
                        </div>
                        <div class="col-lg-4 text-right">
                               
                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} switch-main-block">
                                <div class="custom-switch checkbox mr-4">
                                    {!! Form::checkbox('set_tax', 1, !empty($settings) && isset($settings['set_tax']) ? $settings['set_tax'] : null, ['class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"tax_box", 'data-type'=>'set_tax', 'required-id'=>'#tax', 'id'=>'tax_check']) !!}
                                    <label for="tax_check">{{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                                    <input type="hidden" value="{{route('updateStatus')}}" id="URL">
                                </div> 
                            </div>

                        </div>
                    </div>
                </div>
                <div id="tax_box" class="card-body">
                    <div class="row">                    
                        <div class="col-lg-10">
                            <div class="form-group{{ $errors->has('tax') ? ' has-error' : '' }}">
                            <label for="tax">{{ __('adminWords.enter').' '.__('adminWords.tax').' %' }}<sup>*</sup></label>
                            {!! Form::text('tax', !empty($settings) && isset($settings['set_tax']) && $settings['set_tax'] == 1 ? $settings['tax'] : '', ['class'=>'form-control', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.tax').' % ' ]) !!}
                            <small class="text-danger">{{ $errors->first('tax') }}</small>
                            <p>{{ __('adminWords.tax_note') }}</p>
                            </div>                      
                        
                        </div>                  
                        <div class="col-lg-10">  
                            <button type="button" class="effect-btn btn btn-primary mr-2" data-action="submitThisForm">{{ __('adminWords.save_setting_btn') }}</button>
                            <div class="clear-both"></div>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
        {!! Form::open(['method' => 'POST', 'route'=>'commission.save' ]) !!}
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <h5>{{ __('adminWords.set_commision') }}</h5> 
                        </div>
                        <div class="col-lg-4 text-right">
                               
                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} switch-main-block">
                                <div class="custom-switch checkbox mr-4">
                                    {!! Form::checkbox('is_commission', 1, !empty($settings) && isset($settings['is_commission']) ? $settings['is_commission'] : null, ['class' => 'updateSettingRecords js-switch-primary', 'data-id'=>'commission_box', 'data-type'=>'is_commission', 'required-id'=>'#commission', 'id'=>'artist_commission_check']) !!}
                                    <label for="artist_commission_check">{{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                                    <input type="hidden" value="{{route('updateStatus')}}" id="URL">
                                </div> 
                            </div>

                        </div> 
                    </div>
                </div>

                <div id="commission_box" class="card-body">

                    <div class="form-group dd-flex">
                        <div class="radio radio-primary mr-4">
                          {!! Form::radio('commission_type', 'percent', (isset($settings['commission_type']) && $settings['commission_type'] == 'percent' ? 'checked' :  ''), ['id'=>'percent','data-id'=>'commission_percent','class'=>'artistCommissionType']) !!}
                          {!! Form::label('percent', null, ['class' => 'mb-0']) !!}
                        </div>
                        <div class="radio radio-primary mr-4">
                          {!! Form::radio('commission_type', 'flat', (isset($settings['commission_type']) && $settings['commission_type'] == 'flat' ? 'checked' : ''), ['id'=>'flat','data-id'=>'commission_flat','class'=>'artistCommissionType']) !!}
                          {!! Form::label('flat', null, ['class' => 'mb-0']) !!}
                        </div>                          
                    </div>

                    <div class="row">                    
                        <div class="col-lg-10">                            
                            <div class="form-group commission_field" id="commission_percent" style="<?php if(isset($settings['commission_type']) && $settings['commission_type'] != 'percent'){ echo 'display:none';  } ?>">
                                <label for="commission">{{ __('adminWords.enter').' '.__('adminWords.commission').' %' }}<sup>*</sup> &</label>                                
                                {{ __('adminWords.commission_note') }}
                            </div> 
                            <div class="form-group commission_field" id="commission_flat" style="<?php if(isset($settings['commission_type']) && $settings['commission_type'] != 'flat'){ echo 'display:none';  } ?>">
                                <label for="commission">{{ __('adminWords.commission_flat') }}<sup>*</sup> &</label>                               
                                {{ __('adminWords.commission_flat_note') }}
                            </div> 
                            <div class="form-group">
                                {!! Form::text('commission_val', !empty($settings) && isset($settings['commission_val']) && !empty($settings['commission_val']) ? $settings['commission_val'] : '', ['class'=>'form-control', 'placeholder'=> __('adminWords.commission_value')]) !!}                                                                                         
                            </div>  
                        </div>

                        <div class="col-lg-10">  
                            <button type="button" class="effect-btn btn btn-primary mr-2" data-action="submitThisForm">{{ __('adminWords.save_setting_btn') }}</button>
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
<script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>
@endsection