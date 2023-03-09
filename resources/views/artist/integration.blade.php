@extends('layouts.artist.main')
@section('title', __('adminWords.artist').' '.__('adminWords.integration').' '.__('adminWords.setting')) 
@section('style')
  <link href="{{ asset('public/assets/plugins/switchery/switch.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')           

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.integration').' '.__('adminWords.setting') }}</h4> 
            </div>
            <div class="musioo-brdcrmb breadcrumb-list artistIntegrationAccordation">  
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{ route('artist.home') }}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
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

      {!! Form::open(['method' => 'POST', 'files' => true, 'route'=>['save.artist.integrations', 'artist_youtube']]) !!} 
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.youtube_channel').' '.__('adminWords.setting') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group">  
                    <div class="custom-switch checkbox mr-4">  
                        {!! Form::checkbox('youtube_status', 1, isset($artistIntegration->youtube_status) ? $artistIntegration->youtube_status : null, ['id'=>'artistyoutube_check', 'class' => 'updateSettingRecords', 'data-id'=>"artistyoutube_box", 'required-id'=>'#google_api_key, #youtube_channel_key', 'data-type'=>'youtube_status','data-name'=>'youtube_status']) !!}
                        <label for="artistyoutube_check">{{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                        <input type="hidden" value="{{route('artist.integration.changeStatus')}}" id="URL"> 
                    </div>
                </div>
              </div> 
            </div> 
          </div>
          <div id="artistyoutube_box" class="card-body">
            <div class="row">                    
                
                <div class="col-lg-10">
                    <div class="form-group{{ $errors->has('google_api_key') ? ' has-error' : '' }}">
                    <label for="google_api_key">{{ __('adminWords.google_api_key') }}<sup>*</sup></label>
                    <a class="float-right" href="https://console.cloud.google.com/apis/credentials/key" target="_blank">
                        <svg height="22" width="23" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 469.333 469.333" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M248.533,192c-17.6-49.707-64.853-85.333-120.533-85.333c-70.72,0-128,57.28-128,128s57.28,128,128,128    c55.68,0,102.933-35.627,120.533-85.333h92.8v85.333h85.333v-85.333h42.667V192H248.533z M128,277.333    c-23.573,0-42.667-19.093-42.667-42.667S104.427,192,128,192c23.573,0,42.667,19.093,42.667,42.667S151.573,277.333,128,277.333z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g></g></svg> 
                         Click here to Get your Google Api Key
                    </a>
                    {!! Form::text('google_api_key', (isset($artistIntegration) ? $artistIntegration->google_api_key : ''), ['class' => 'form-control','placeholder' => 'exp. AIzaUsfedfvgJjf4aG-DzebG-L8WYewA32_zFo']) !!}
                    <small class="text-danger">{{ $errors->first('google_api_key') }}</small>
                    </div>           
                </div>          

                <div class="col-lg-10"> 
                    <div class="form-group{{ $errors->has('youtube_channel_key') ? ' has-error' : '' }}">
                      <label for="youtube_channel_key">{{ __('adminWords.youtube_channel_key') }}<sup>*</sup></label>
                      <a class="float-right" href="https://support.google.com/youtube/answer/3250431?hl=en" target="_blank">
                          <svg height="22" width="23" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 469.333 469.333" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M248.533,192c-17.6-49.707-64.853-85.333-120.533-85.333c-70.72,0-128,57.28-128,128s57.28,128,128,128    c55.68,0,102.933-35.627,120.533-85.333h92.8v85.333h85.333v-85.333h42.667V192H248.533z M128,277.333    c-23.573,0-42.667-19.093-42.667-42.667S104.427,192,128,192c23.573,0,42.667,19.093,42.667,42.667S151.573,277.333,128,277.333z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g></g></svg> 
                           Click here to Get your Youtube Channel Key
                      </a>
                      {!! Form::text('youtube_channel_key', (isset($artistIntegration) ? $artistIntegration->youtube_channel_key : ''), ['class' => 'form-control','placeholder' => 'exp. UC5gtyhjthtfhshm1gpIpiBa']) !!}
                      <small class="text-danger">{{ $errors->first('youtube_channel_key') }}</small>
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
<script src="{{asset('public/assets/js/artist-custom.js?'.time())}}"></script>

@endsection

