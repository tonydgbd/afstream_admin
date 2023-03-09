@extends('layouts.admin.main')
@section('title', __('adminWords.social_login_settings'))
@section('content')           
 
<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.social_login_settings') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list socialLoginAccordation">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.social_login_settings') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="contentbar">  
  <div class="row">
    <div class="col-lg-12">
        {!! Form::open(['method' => 'POST', 'files' => true, 'route'=>['saveSocialLoginData', 'google']]) !!}
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-lg-8">
                  <h5>Google Login Settings</h5>
                </div>
                <div class="col-lg-4 text-right">
                  <div class="form-group">
                    <div class="custom-switch checkbox mr-4">
                      {!! Form::checkbox('is_google', 1, isset($settings['is_google']) ? $settings['is_google'] : null, ['id'=>'g_check', 'class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"g_box", 'required-id'=>'#GOOGLE_CLIENT_ID, #gsecret, #GOOGLE_REDIRECT', 'data-type'=>'is_google']) !!}
                      <label for="g_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                      <input type="hidden" value="{{route('updateStatus')}}" id="URL">
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div id="g_box" class="card-body">
              <div class="row">                    
                <div class="col-lg-10">
                  <div class="form-group{{ $errors->has('GOOGLE_CLIENT_ID') ? ' has-error' : '' }}">
                    <label for="GOOGLE_CLIENT_ID">{{ __('adminWords.google').' '.__('adminWords.client_id') }}<sup>*</sup></label>
                    {!! Form::text('GOOGLE_CLIENT_ID', env('GOOGLE_CLIENT_ID'), ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('GOOGLE_CLIENT_ID') }}</small>
                  </div>                      
                  <div class="form-group{{ $errors->has('GOOGLE_CLIENT_SECRET') ? ' has-error' : '' }}">
                    <label for="GOOGLE_CLIENT_SECRET">{{ __('adminWords.google').' '.__('adminWords.client_secret') }}<sup>*</sup></label>
                    <input type="password" name="GOOGLE_CLIENT_SECRET" value="{{ env('GOOGLE_CLIENT_SECRET') }}" id="gsecret" class="form-control">
                    <span toggle="#gsecret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                    <small class="text-danger">{{ $errors->first('GOOGLE_CLIENT_SECRET') }}</small>
                  </div>
                  <div class="form-group{{ $errors->has('GOOGLE_REDIRECT_URL') ? ' has-error' : '' }}">
                    <label for="GOOGLE_REDIRECT_URL">{{ __('adminWords.google').' '. __('adminWords.callback_url') }}<sup>*</sup></label>
                    {!! Form::text('GOOGLE_REDIRECT_URL', env('GOOGLE_REDIRECT_URL'), ['class' => 'form-control require','placeholder' => 'https://yoursite.com/login/public/google/callback', 'readonly', 'data-valid'=>'url', 'data-error'=>__('adminWords.invalid').' '.__('adminWords.url')]) !!}
                    <small class="text-danger">{{ $errors->first('GOOGLE_REDIRECT_URL') }}</small>
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

        {!! Form::open(['method' => 'POST', 'files' => true, 'route'=>['saveSocialLoginData', 'fb']]) !!}
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-lg-8">
                  <h5>{{ __('adminWords.facebook_setting') }}</h5>
                </div>
                <div class="col-lg-4 text-right">
                  <div class="form-group">
                    <div class="custom-switch checkbox mr-4">
                      {!! Form::checkbox('is_fb', 1, isset($settings['is_fb']) ? $settings['is_fb'] : null, ['id'=>'fb_check', 'class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"fb_box", 'required-id'=>'#FACEBOOK_APP_ID, #fbsecret, #FACEBOOK_REDIRECT', 'data-type'=>'is_fb']) !!}
                      <label for="fb_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="fb_box" class="card-body">
              <div class="row">                    
                <div class="col-lg-10">
                  <div class="form-group{{ $errors->has('FACEBOOK_APP_ID') ? ' has-error' : '' }}">
                    <label for="FACEBOOK_APP_ID">{{ __('adminWords.fb_client_id') }}<sup>*</sup></label>
                    {!! Form::text('FACEBOOK_APP_ID', env('FACEBOOK_APP_ID'), ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('FACEBOOK_APP_ID') }}</small>
                  </div>                      
                  <div class="form-group{{ $errors->has('FACEBOOK_APP_SECRET') ? ' has-error' : '' }}">
                    <label for="FACEBOOK_APP_SECRET">{{ __('adminWords.fb_secret_key') }}<sup>*</sup></label>
                    <input type="password" name="FACEBOOK_APP_SECRET" value="{{ env('FACEBOOK_APP_SECRET') }}" id="fbsecret" class="form-control">
                    <span toggle="#fbsecret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                    <small class="text-danger">{{ $errors->first('FACEBOOK_APP_SECRET') }}</small>
                  </div>
                  <div class="form-group{{ $errors->has('FACEBOOK_REDIRECT_URL') ? ' has-error' : '' }}">
                    <label for="FACEBOOK_REDIRECT_URL">{{ __('adminWords.callback_url') }}<sup>*</sup></label>
                    {!! Form::text('FACEBOOK_REDIRECT_URL', env('FACEBOOK_REDIRECT_URL'), ['class' => 'form-control','placeholder' => 'https://yoursite.com/public/login/facebook/callback', 'readonly']) !!}
                    <small class="text-danger">{{ $errors->first('FACEBOOK_REDIRECT_URL') }}</small>
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

        {!! Form::open(['method' => 'POST', 'files' => true, 'route'=>['saveSocialLoginData', 'git']]) !!}
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-lg-8">
                  <h5>{{ __('adminWords.github_setting') }}</h5>
                </div>
                <div class="col-lg-4 text-right">
                  <div class="form-group">
                    <div class="custom-switch checkbox mr-4">
                      {!! Form::checkbox('is_github', 1, isset($settings['is_github']) ? $settings['is_github'] : null, ['id'=>'git_check', 'class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"git_box", 'required-id'=>'#GITHUB_CLIENT_ID, #gitsecret, #GITHUB_CALLBACK_URL', 'data-type'=>'is_github']) !!}
                      <label for="git_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="git_box" class="card-body">
              <div class="row">                    
                <div class="col-lg-10">
                  <div class="form-group{{ $errors->has('GITHUB_CLIENT_ID') ? ' has-error' : '' }}">
                    <label for="GITHUB_CLIENT_ID">{{ __('adminWords.github_client_id') }}<sup>*</sup></label>
                    {!! Form::text('GITHUB_CLIENT_ID', env('GITHUB_CLIENT_ID'), ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('GITHUB_CLIENT_ID') }}</small>
                  </div>                      
                  <div class="form-group{{ $errors->has('GITHUB_CLIENT_SECRET') ? ' has-error' : '' }}">
                    <label for="GITHUB_CLIENT_SECRET">{{ __('adminWords.github_secret') }}<sup>*</sup></label>
                    <input type="password" name="GITHUB_CLIENT_SECRET" value="{{ env('GITHUB_CLIENT_SECRET') }}" id="gitsecret" class="form-control">
                    <span toggle="#gitsecret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                    <small class="text-danger">{{ $errors->first('GITHUB_CLIENT_SECRET') }}</small>
                  </div>
                  <div class="form-group{{ $errors->has('GITHUB_REDIRECT_URL') ? ' has-error' : '' }}">
                    <label for="GITHUB_REDIRECT_URL">{{ __('adminWords.github_redirect') }}<sup>*</sup></label>
                    {!! Form::text('GITHUB_REDIRECT_URL', env('GITHUB_REDIRECT_URL'), ['class' => 'form-control','placeholder' => 'https://yoursite.com/login/public/github/callback', 'readonly']) !!}
                    <small class="text-danger">{{ $errors->first('GITHUB_REDIRECT_URL') }}</small>
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

        {!! Form::open(['method' => 'POST', 'files' => true, 'route'=>['saveSocialLoginData', 'twitter']]) !!}
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-lg-8">
                  <h5>{{ __('adminWords.twitter').' '.__('adminWords.setting') }}</h5>
                </div>
                <div class="col-lg-4 text-right">
                  <div class="form-group">
                    <div class="custom-switch checkbox mr-4">
                      {!! Form::checkbox('is_twitter', 1, isset($settings['is_twitter']) ? $settings['is_twitter'] : null, ['id'=>'twitter_check', 'class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"twitter_box", 'required-id'=>'#TWITTER_CLIENT_ID, #twittersecret, #TWITTER_REDIRECT_URL', 'data-type'=>'is_twitter']) !!}
                      <label for="twitter_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="twitter_box" class="card-body">
              <div class="row">                    
                <div class="col-lg-10">
                  <div class="form-group{{ $errors->has('TWITTER_CLIENT_ID') ? ' has-error' : '' }}">
                    <label for="TWITTER_CLIENT_ID">{{ __('adminWords.twitter').' '.__('adminWords.client_id') }}<sup>*</sup></label>
                    {!! Form::text('TWITTER_CLIENT_ID', env('TWITTER_CLIENT_ID'), ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('TWITTER_CLIENT_ID') }}</small>
                  </div>                      
                  <div class="form-group{{ $errors->has('TWITTER_CLIENT_SECRET') ? ' has-error' : '' }}">
                    <label for="TWITTER_CLIENT_SECRET">{{ __('adminWords.twitter').' '.__('adminWords.client_secret') }}<sup>*</sup></label>
                    <input type="password" name="TWITTER_CLIENT_SECRET" value="{{ env('TWITTER_CLIENT_SECRET') }}" id="twittersecret" class="form-control">
                    <span toggle="#twittersecret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                    <small class="text-danger">{{ $errors->first('TWITTER_CLIENT_SECRET') }}</small>
                  </div>
                  <div class="form-group{{ $errors->has('TWITTER_REDIRECT_URL') ? ' has-error' : '' }}">
                    <label for="TWITTER_REDIRECT_URL">{{ __('adminWords.twitter').' '.__('adminWords.callback_url') }}<sup>*</sup></label>
                    {!! Form::text('TWITTER_REDIRECT_URL', env('TWITTER_REDIRECT_URL'), ['class' => 'form-control','placeholder' => 'https://yoursite.com/login/public/twitter/callback','readonly']) !!}
                    <small class="text-danger">{{ $errors->first('TWITTER_REDIRECT_URL') }}</small>
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

        {!! Form::open(['method' => 'POST', 'files' => true, 'route'=>['saveSocialLoginData', 'amazon']]) !!}
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-lg-8">
                  <h5>{{ __('adminWords.amazon').' '.__('adminWords.setting') }}</h5>
                </div>
                <div class="col-lg-4 text-right">
                  <div class="form-group">
                    <div class="custom-switch checkbox mr-4">
                      {!! Form::checkbox('is_amazon', 1, isset($settings['is_amazon']) ? $settings['is_amazon'] : null, ['id'=>'amazon_check', 'class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"amazon_box", 'required-id'=>'#AMAZON_CLIENT_ID, #amazonsecret, #AMAZON_REDIRECT_URL', 'data-type'=>'is_amazon']) !!}
                      <label for="amazon_check">{{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="amazon_box" class="card-body">
              <div class="row">                    
                <div class="col-lg-10">
                  <div class="form-group{{ $errors->has('AMAZON_CLIENT_ID') ? ' has-error' : '' }}">
                    <label for="AMAZON_CLIENT_ID">{{ __('adminWords.amazon').' '.__('adminWords.client_id') }}<sup>*</sup></label>
                    {!! Form::text('AMAZON_CLIENT_ID', env('AMAZON_CLIENT_ID'), ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('AMAZON_CLIENT_ID') }}</small>
                  </div>                      
                  <div class="form-group{{ $errors->has('AMAZON_CLIENT_SECRET') ? ' has-error' : '' }}">
                    <label for="AMAZON_CLIENT_SECRET">{{ __('adminWords.amazon').' '.__('adminWords.client_secret') }}<sup>*</sup></label>
                    <input type="password" name="AMAZON_CLIENT_SECRET" value="{{ env('AMAZON_CLIENT_SECRET') }}" id="amazonsecret" class="form-control">
                    <span toggle="#amazonsecret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                    <small class="text-danger">{{ $errors->first('AMAZON_CLIENT_SECRET') }}</small>
                  </div>
                  <div class="form-group{{ $errors->has('AMAZON_REDIRECT_URL') ? ' has-error' : '' }}">
                    <label for="AMAZON_REDIRECT_URL">{{ __('adminWords.amazon').' '.__('adminWords.callback_url') }}<sup>*</sup></label>
                    {!! Form::text('AMAZON_REDIRECT_URL', env('AMAZON_REDIRECT_URL'), ['class' => 'form-control','placeholder' => 'https://yoursite.com/login/public/amazon/callback', 'readonly']) !!}
                    <small class="text-danger">{{ $errors->first('AMAZON_REDIRECT_URL') }}</small>
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

        {!! Form::open(['method' => 'POST', 'files' => true, 'route'=>['saveSocialLoginData', 'linkedin']]) !!}
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-lg-8">
                  <h5>{{ __('adminWords.linkedin').' '.__('adminWords.setting') }}</h5>
                </div>
                <div class="col-lg-4 text-right">
                  <div class="form-group">
                    <div class="custom-switch checkbox mr-4">
                      {!! Form::checkbox('is_linkedin', 1, isset($settings['is_linkedin']) ? $settings['is_linkedin'] : null, ['id'=>'linkedin_check', 'class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"linkedin_box", 'required-id'=>'#LINKEDIN_CLIENT_ID, #linkedinsecret, #LINKEDIN_REDIRECT_URL', 'data-type'=>'is_linkedin']) !!}
                      <label for="linkedin_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="linkedin_box" class="card-body">
              <div class="row">                    
                <div class="col-lg-10">
                  <div class="form-group{{ $errors->has('LINKEDIN_CLIENT_ID') ? ' has-error' : '' }}">
                    <label for="LINKEDIN_CLIENT_ID">{{ __('adminWords.linkedin').' '.__('adminWords.client_id') }}<sup>*</sup></label>
                    {!! Form::text('LINKEDIN_CLIENT_ID', env('LINKEDIN_CLIENT_ID'), ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('LINKEDIN_CLIENT_ID') }}</small>
                  </div>                      
                  <div class="form-group{{ $errors->has('LINKEDIN_CLIENT_SECRET') ? ' has-error' : '' }}">
                    <label for="LINKEDIN_CLIENT_SECRET">{{ __('adminWords.linkedin').' '.__('adminWords.client_secret') }}<sup>*</sup></label>
                    <input type="password" name="LINKEDIN_CLIENT_SECRET" value="{{ env('LINKEDIN_CLIENT_SECRET') }}" id="linkedinsecret" class="form-control">
                    <span toggle="#linkedinsecret" class="fa fa-fw fa-eye-slash field-icon toggle-view-password"></span>
                    <small class="text-danger">{{ $errors->first('LINKEDIN_CLIENT_SECRET') }}</small>
                  </div>
                  <div class="form-group{{ $errors->has('LINKEDIN_REDIRECT_URL') ? ' has-error' : '' }}">
                    <label for="LINKEDIN_REDIRECT_URL">{{ __('adminWords.linkedin').' '.__('adminWords.callback_url') }}<sup>*</sup></label>
                    {!! Form::text('LINKEDIN_REDIRECT_URL', env('LINKEDIN_REDIRECT_URL'), ['class' => 'form-control','placeholder' => 'https://yoursite.com/login/public/linkedin/callback', 'readonly']) !!}
                    <small class="text-danger">{{ $errors->first('LINKEDIN_REDIRECT_URL') }}</small>
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

<script src="{{asset('public/assets/js/musioo-custom.js?'.time())}}"></script>

@endsection

