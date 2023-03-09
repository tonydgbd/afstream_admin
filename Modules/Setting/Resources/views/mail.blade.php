@extends('layouts.admin.main')
@section('title', __('adminWords.mail').' '.__('adminWords.setting'))
@section('content')

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.mail').' '.__('adminWords.setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list emailAccordation">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.mail').' '.__('adminWords.setting') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="contentbar">  
    <div class="row">
        <div class="col-lg-12">
                
            {!! Form::open(['method' => 'POST', 'route' => 'mail.update']) !!}
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="row">
                      <div class="col-lg-8">
                        <h5 class="card-title mb-0">{{ __('adminWords.mail').' '.__('adminWords.setting') }}</h5></br>
                        <small>                                
                            <a target="_blank" href="https://www.gmass.co/blog/gmail-smtp">Click here to check how to configuration smtp setup</a>
                        </small>
                      </div>
                      <div class="col-lg-4 text-right">
                        <div class="form-group">
                          <div class="custom-switch checkbox mr-4">                              
                            {!! Form::checkbox('is_smtp', 1, isset($settings['is_smtp']) ? $settings['is_smtp'] : null, ['class' => ' updateSettingRecords', 'data-id'=>"smtp_box", 'required-id'=>'#MAIL_FROM_NAME, #MAIL_DRIVER, #MAIL_HOST, #MAIL_PORT, #MAIL_USERNAME #MAIL_FROM_ADDRESS #MAIL_PASSWORD ', 'data-type'=>'is_smtp', 'id'=>'smtp_check', 'data-name' => 'smtp']) !!}
                            <label for="smtp_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                            <input type="hidden" value="{{route('integration.changeStatus')}}" id="URL">
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div id="smtp_box" class="card-body">
                    <div class="row">                    
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('MAIL_FROM_NAME') ? ' has-error' : '' }}">
                            <label for="MAIL_FROM_NAME">{{  __('adminWords.sender_name') }}<sup>*</sup></label>
                            {!! Form::text('MAIL_FROM_NAME', env('MAIL_FROM_NAME'), ['class' => 'form-control require', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.sender_name') ]) !!}
                            <small class="text-danger">{{ $errors->first('MAIL_FROM_NAME') }}</small>
                        </div>    
                    </div>                         
                    <div class="col-md-6">                   
                        <div class="form-group{{ $errors->has('MAIL_DRIVER') ? ' has-error' : '' }}">
                            <label for="MAIL_DRIVER">{{  __('adminWords.mail_driver') }}<sup>*</sup></label>
                            {!! Form::text('MAIL_DRIVER', env('MAIL_DRIVER'), ['class' => 'form-control require', 'placeholder'=>__('adminWords.mail_driver_plchldr') ]) !!}
                            <small class="text-danger">{{ $errors->first('MAIL_DRIVER') }}</small>
                        </div>
                    </div>                         
                    <div class="col-md-6">  
                        <div class="form-group{{ $errors->has('MAIL_HOST') ? ' has-error' : '' }}">
                            <label for="MAIL_HOST">{{  __('adminWords.mail_host') }}<sup>*</sup></label>
                            {!! Form::text('MAIL_HOST', env('MAIL_HOST'), ['class' => 'form-control require', 'placeholder'=>__('adminWords.mail_host_plchldr')]) !!}
                            <small class="text-danger">{{ $errors->first('MAIL_HOST') }}</small>
                        </div>
                    </div>                         
                    <div class="col-md-6">  
                        <div class="form-group{{ $errors->has('MAIL_PORT') ? ' has-error' : '' }}">
                            <label for="MAIL_PORT">{{  __('adminWords.mail_port') }}<sup>*</sup></label>
                            {!! Form::text('MAIL_PORT', env('MAIL_PORT'), ['class' => 'form-control require', 'placeholder'=>__('adminWords.mail_port_plchldr')]) !!}
                            <small class="text-danger">{{ $errors->first('MAIL_PORT') }}</small>
                        </div>  
                    </div>                         
                    <div class="col-md-6">                                        
                        <div class="form-group{{ $errors->has('MAIL_USERNAME') ? ' has-error' : '' }}">
                            <label for="MAIL_USERNAME">{{  __('adminWords.mail_user') }}<sup>*</sup></label>
                            {!! Form::text('MAIL_USERNAME', env('MAIL_USERNAME'), ['class' => 'form-control require', 'placeholder'=> __('adminWords.mail_name_plchldr')]) !!}
                            <small class="text-danger">{{ $errors->first('MAIL_USERNAME') }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">                                        
                        <div class="form-group{{ $errors->has('MAIL_USERNAME') ? ' has-error' : '' }}">
                            <label for="MAIL_USERNAME">{{  __('adminWords.mail_from_address') }}<sup>*</sup></label>
                            {!! Form::text('MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS'), ['class' => 'form-control require', 'placeholder'=> __('adminWords.mail_address_plchldr')]) !!} 
                            <small class="text-danger">{{ $errors->first('MAIL_FROM_ADDRESS') }}</small>
                        </div>
                    </div>   
                                               
                    <div class="col-md-6">                     
                        <div class="form-group{{ $errors->has('MAIL_PASSWORD') ? ' has-error' : '' }}">
                            <label for="MAIL_PASSWORD">{{  __('adminWords.mail_pass') }}<sup>*</sup></label>
                            {!! Form::text('MAIL_PASSWORD', env('MAIL_PASSWORD'), ['class' => 'form-control require', 'placeholder'=>__('adminWords.mail_pass')]) !!}
                            <small class="text-danger">{{ $errors->first('MAIL_PASSWORD') }}</small>
                        </div> 
                    </div>                         
                    <div class="col-md-6">                     
                        <div class="form-group{{ $errors->has('MAIL_ENCRYPTION') ? ' has-error' : '' }}">
                            <label for="MAIL_ENCRYPTION">{{  __('adminWords.mail_enc') }}</label>
                            {!! Form::text('MAIL_ENCRYPTION', env('MAIL_ENCRYPTION'), ['class' => 'form-control', 'placeholder'=>__('adminWords.mail_enc_plchldr')]) !!}
                            <small class="text-danger">{{ $errors->first('MAIL_ENCRYPTION') }}</small>
                        </div> 
                    </div>
                    <div class="col-md-6">                     
                        <div class="checkbox mr-4">
                            {!! Form::checkbox('wel_mail', 1, (isset($settings['wel_mail']) && $settings['wel_mail'] == 0 ? 0 : 1),['id'=>'wel_mail']) !!}
                            <label for="wel_mail">{{ __('adminWords.welcome_mail').' ('.__('adminWords.new_registerd_user').')' }}</label>
                           
                            <small class="text-danger">{{ $errors->first('wel_mail') }}</small>
                        </div> 
                    </div>
                    <div class="col-md-12"> 
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
  <script src="{{asset('public/assets/js/musioo-custom.js?'.time())}}"></script>
@endsection