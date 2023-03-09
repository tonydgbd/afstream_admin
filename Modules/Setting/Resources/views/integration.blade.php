@extends('layouts.admin.main')
@section('title', __('adminWords.integration').' '.__('adminWords.setting'))
@section('style')
  <link href="{{ asset('public/assets/plugins/switchery/switch.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')           

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.integration').' '.__('adminWords.setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list integrationAccordation"> 
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.integration').' '.__('adminWords.setting') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="contentbar">  
  <div class="row"> 
    <div class="col-lg-12">        

        {!! Form::open(['method' => 'POST', 'files' => true, 'route'=>['saveIntegrationData', 'aws_s3']]) !!}
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                        <h5>{{ __('adminWords.aws_s3').' '.__('adminWords.setting') }}</h5>
                        </div>

                        <div class="col-lg-4 text-right">
                            <div class="form-group">
                                <div class="custom-switch checkbox mr-4">
                                    {!! Form::checkbox('is_s3', 1, isset($settings['is_s3']) ? $settings['is_s3'] : null, ['id'=>'s3_check', 'class' => 'updateSettingRecords', 'data-id'=>"s3_box", 'required-id'=>'#AWS_ACCESS_KEY_ID, #AWS_SECRET_ACCESS_KEY, #AWS_DEFAULT_REGION, #AWS_BUCKET', 'data-type'=>'is_s3','data-name' => 'is_s3']) !!}
                                    <label for="s3_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                                    <input type="hidden" value="{{route('integration.changeStatus')}}" id="URL">
                                </div> 
                            </div>
                        </div> 

                    </div> 
                </div>

                <div id="s3_box" class="card-body">
                    <div class="row">                    
                        <div class="col-lg-10">
                            <div class="form-group{{ $errors->has('AWS_ACCESS_KEY_ID') ? ' has-error' : '' }}">
                                <label for="AWS_ACCESS_KEY_ID">{{ __('adminWords.aws').' '.__('adminWords.client_id') }}<sup>*</sup></label>
                                {!! Form::text('AWS_ACCESS_KEY_ID', env('AWS_ACCESS_KEY_ID'), ['class' => 'form-control']) !!}
                                <small class="text-danger">{{ $errors->first('AWS_ACCESS_KEY_ID') }}</small>
                            </div>                      
                            <div class="form-group{{ $errors->has('AWS_SECRET_ACCESS_KEY') ? ' has-error' : '' }}">
                                <label for="AWS_SECRET_ACCESS_KEY">{{ __('adminWords.aws').' '.__('adminWords.client_secret') }}<sup>*</sup></label>
                                <input type="password" name="AWS_SECRET_ACCESS_KEY" value="{{ env('AWS_SECRET_ACCESS_KEY') }}" id="s3_setting" class="form-control">
                                <span toggle="#s3_setting" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                                <small class="text-danger">{{ $errors->first('AWS_SECRET_ACCESS_KEY') }}</small>
                            </div>
                            <div class="form-group{{ $errors->has('AWS_DEFAULT_REGION') ? ' has-error' : '' }}">
                                <label for="AWS_DEFAULT_REGION">{{ __('adminWords.aws_region') }}<sup>*</sup></label> 
                                {!! Form::text('AWS_DEFAULT_REGION', env('AWS_DEFAULT_REGION'), ['class' => 'form-control','placeholder' => 'eu-west-1']) !!}
                                <small class="text-danger">{{ $errors->first('AWS_DEFAULT_REGION') }}</small>
                            </div>  
                            <div class="form-group{{ $errors->has('AWS_BUCKET') ? ' has-error' : '' }}">
                                <label for="AWS_BUCKET">{{ __('adminWords.aws_bucket_name') }}<sup>*</sup></label> 
                                {!! Form::text('AWS_BUCKET', env('AWS_BUCKET'), ['class' => 'form-control']) !!}
                                <small class="text-danger">{{ $errors->first('AWS_BUCKET') }}</small>
                            </div>

                            <div class="form-group{{ $errors->has('AWS_DIRECTORY') ? ' has-error' : '' }}">
                                <label for="AWS_DIRECTORY">{{ __('adminWords.bucket_upload_directory') }}</label> 
                                {!! Form::text('AWS_DIRECTORY', env('AWS_DIRECTORY'), ['class' => 'form-control','placeholder' => 'exp. audio/hip-hop']) !!}
                                <small class="text-danger">{{ $errors->first('AWS_DIRECTORY') }}</small>
                            </div>  

                            <div class="form-group">
                                <label for="artist_upload_on_s3">{{ __('adminWords.artist_s3_upload') }}</label>
                                <div class="dd-flex mb-3">
                                    <div class="radio radio-primary mr-4">
                                        {!! Form::radio('artist_upload_on_s3', '0', (isset($settings['artist_upload_on_s3']) && $settings['artist_upload_on_s3'] == '0' ? 'checked' : (!isset($settings['artist_upload_on_s3']) ? 'checked' : '')), ['id'=>'no']) !!}
                                        {!! Form::label('no', null, ['class' => 'mb-0']) !!}
                                    </div>    
                                                            
                                    <div class="radio radio-primary mr-4">
                                        {!! Form::radio('artist_upload_on_s3', '1', (isset($settings['artist_upload_on_s3']) && $settings['artist_upload_on_s3'] == '1' ? 'checked' :  ''), ['id'=>'yes']) !!}
                                        {!! Form::label('yes', null, ['class' => 'mb-0']) !!}
                                    </div>
                                </div>
                                <div class="artist_upload_note">{{ __('adminWords.artist_upload_on_s3_note') }}</div>
                            </div>              
                        </div>   
                        <div class="col-lg-10"> 
                            <button type="button" data-action="submitThisForm" class="effect-btn btn btn-primary">{{ __('adminWords.save_setting_btn') }}</button>
                            <div class="clear-both"></div>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        
    </div>

    <div class="col-lg-12">        

      {!! Form::open(['method' => 'POST', 'files' => true, 'route'=>['saveIntegrationData', 'youtube']]) !!} 
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.youtube_channel').' '.__('adminWords.setting') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group mb-0">  
                    <div class="custom-switch checkbox mb-0"> 
                        {!! Form::checkbox('is_youtube', 1, isset($settings['is_youtube']) ? $settings['is_youtube'] : null, ['id'=>'youtube_check', 'class' => 'updateSettingRecords', 'data-id'=>"youtube_box", 'required-id'=>'#YOUTUBE_API_KEY','data-type'=>'is_youtube']) !!}
                        <label for="youtube_check">{{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                        <input type="hidden" value="{{route('integration.changeStatus')}}" id="URL"> 
                    </div>
                </div>
              </div> 
            </div> 
          </div>
          <div id="youtube_box" class="card-body">
            <div class="row">                    
                
                <div class="col-lg-10">
                    <div class="form-group{{ $errors->has('YOUTUBE_API_KEY') ? ' has-error' : '' }}">
                    <label for="YOUTUBE_API_KEY">{{ __('adminWords.google_api_key') }}<sup>*</sup></label>
                    <a class="float-right" href="https://console.cloud.google.com/apis/credentials/key" target="_blank">
                        <svg height="22" width="23" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 469.333 469.333" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M248.533,192c-17.6-49.707-64.853-85.333-120.533-85.333c-70.72,0-128,57.28-128,128s57.28,128,128,128    c55.68,0,102.933-35.627,120.533-85.333h92.8v85.333h85.333v-85.333h42.667V192H248.533z M128,277.333    c-23.573,0-42.667-19.093-42.667-42.667S104.427,192,128,192c23.573,0,42.667,19.093,42.667,42.667S151.573,277.333,128,277.333z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g></g></svg> 
                         Click here to Get your Google Api Key
                    </a>
                    {!! Form::text('YOUTUBE_API_KEY', env('YOUTUBE_API_KEY'), ['class' => 'form-control','placeholder' => 'exp. AIzaUsfedfvgJjf4aG-DzebG-L8WYewA32_zFo']) !!}
                    <small class="text-danger">{{ $errors->first('YOUTUBE_API_KEY') }}</small> 
                    </div>           
                </div>          
 
                <div class="col-lg-10">
                    <div class="form-group{{ $errors->has('YOUTUBE_CHANNEL_KEY') ? ' has-error' : '' }}">
                      <label for="YOUTUBE_CHANNEL_KEY">{{ __('adminWords.youtube_channel_key') }}</label>
                      <a class="float-right" href="https://support.google.com/youtube/answer/3250431?hl=en" target="_blank">
                          <svg height="22" width="23" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 469.333 469.333" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M248.533,192c-17.6-49.707-64.853-85.333-120.533-85.333c-70.72,0-128,57.28-128,128s57.28,128,128,128    c55.68,0,102.933-35.627,120.533-85.333h92.8v85.333h85.333v-85.333h42.667V192H248.533z M128,277.333    c-23.573,0-42.667-19.093-42.667-42.667S104.427,192,128,192c23.573,0,42.667,19.093,42.667,42.667S151.573,277.333,128,277.333z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g></g></svg> 
                           Click here to Get your Youtube Channel Key
                      </a>
                      {!! Form::text('YOUTUBE_CHANNEL_KEY', env('YOUTUBE_CHANNEL_KEY'), ['class' => 'form-control','placeholder' => 'exp. UC5gtyhjthtfhshm1gpIpiBa']) !!}
                      <small class="text-danger">{{ $errors->first('YOUTUBE_CHANNEL_KEY') }}</small>
                    </div>           
                </div>  

                <div class="col-lg-10">
                  <div class="form-group">
                    <label for="yt_country">{{ __('adminWords.select').' '.__('adminWords.country') }}</label>
                    {!! Form::select('YT_COUNTRY_CODE',$country ,env('YT_COUNTRY_CODE'), ['class'=>'form-control select2WithSearch country_id', 'placeholder'=>__('adminWords.select').' '.__('adminWords.country'), 'id' => 'yt_country' ]) !!}                     
                  </div>  
                </div>

              <div class="col-lg-10"> 
                <button type="button" data-action="submitThisForm" class="effect-btn btn btn-primary">{{ __('adminWords.save_setting_btn') }}</button>
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
<script src="{{ asset('public/assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{asset('public/assets/js/musioo-custom.js?'.time())}}"></script> 

@endsection

