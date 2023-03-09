@extends('layouts.admin.main')
@section('title', __('adminWords.footer').' '.__('adminWords.setting'))
@section('content')                

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.footer').' '.__('adminWords.setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list socialLoginAccordation">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.footer').' '.__('adminWords.setting') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="contentbar">  
  <div class="row">    
    <div class="col-lg-12">
        {!! Form::open(['method' => 'POST', 'route' => ['saveCommonSetting','footer'] ]) !!}
          <div class="card m-b-30">
            <div class="card-header openMenusToggle menuAccordation">
              <div class="row">
                <div class="col-lg-8">
                  <h5>{{ __('adminWords.footer').' '.__('adminWords.setting') }}</h5>
                </div>
                <div class="col-lg-4 text-right">
                  <div class="form-group">
                    <div class="custom-switch checkbox mr-4">
                      {!! Form::checkbox('is_footer', 1, isset($settings['is_footer']) ? $settings['is_footer'] : null, ['class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"footer_box", 'required-id'=>'#section_1_heading, #section_1_description, #section_2_heading, #section_2_description, #section_3_heading, #section_3_description, #section_4_heading, #w_email, #w_phone, #w_address, #facebook_url, #linkedin_url, #twitter_url, #google_plus_url, #copyrightText', 'data-type'=>'paypal_donation', 'data-type' => 'is_footer', 'id'=>'footer_section_check']) !!}
                      <label for="footer_section_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                      <input type="hidden" value="{{route('updateStatus')}}" id="URL">
                    </div>
                  </div>                
                </div>
              </div>
            </div>
            <div id="footer_box" class="card-body">
            <div class="row">                   
                <div class="col-lg-10">
                    
                    <div class="divideSections">
                        <div class="col lg-12 col-md-12 col-sm-12 col-xs-12 pl-0">
                            <label class="main_label_heading">{{ __('adminWords.section').' 1 ' }}</label>
                        </div>
                        <div class="form-group">
                            <label for="section_1_heading">{{ __('adminWords.section').' 1 '.__('adminWords.heading') }}<sup>*</sup></label>
                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.section').' 1 '.__('adminWords.heading') }}" id="section_1_heading" name="section_1_heading" class="form-control require" value="{{ isset($settings['section_1_heading']) ? $settings['section_1_heading'] : '' }}" />
                        </div>
                        <div class="form-group">
                            <label for="section_1_description">{{ __('adminWords.section').' 1 '.__('adminWords.description') }}<sup>*</sup></label>
                            <textarea placeholder="{{ __('adminWords.enter').' '.__('adminWords.section').' 1 '.__('adminWords.description') }}" id="section_1_description" name="section_1_description" class="form-control require">{{ isset($settings['section_1_description']) ? $settings['section_1_description'] : '' }}</textarea>
                        </div>
                    </div>
                    <div class="divideSections">
                        <div class="col lg-12 col-md-12 col-sm-12 col-xs-12 pl-0">
                            <label class="main_label_heading">{{ __('adminWords.section').' 2 ' }}</label>
                        </div>
                        <div class="form-group">
                            <label for="section_2_heading">{{ __('adminWords.section').' 2 '.__('adminWords.heading') }}<sup>*</sup></label>
                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.section').' 2 '.__('adminWords.description') }}" id="section_2_heading" name="section_2_heading" class="form-control require" value="{{ isset($settings['section_2_heading']) ? $settings['section_2_heading'] : '' }}" />
                        </div>
                        <div class="form-group">
                            <label for="section_2_description">{{ __('adminWords.section').' 2 '.__('adminWords.description') }}<sup>*</sup></label>
                            <textarea placeholder="{{ __('adminWords.enter').' '.__('adminWords.section').' 2 '.__('adminWords.description') }}" name="section_2_description" id="section_2_description" class="form-control require" >{{ isset($settings['section_2_description']) ? $settings['section_2_description'] : '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="google_play_url">{{ __('adminWords.google_play_url') }}</label>
                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.google_play_url') }}" name="google_play_url" id="google_play_url" class="form-control" value="{{ isset($settings['google_play_url']) ? $settings['google_play_url'] : '' }}" />
                        </div>
                        <div class="form-group">
                            <label for="app_store_url">{{ __('adminWords.app_store_url') }}</label>
                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.app_store_url') }}" name="app_store_url" id="app_store_url" class="form-control" value="{{ isset($settings['app_store_url']) ? $settings['app_store_url'] : '' }}" />
                        </div>
                    </div>
                    <div class="divideSections">
                        <div class="col lg-12 col-md-12 col-sm-12 col-xs-12 pl-0">
                            <label class="main_label_heading">{{ __('adminWords.section').' 3 ' }}</label>
                        </div>
                        <div class="form-group">
                            <label for="section_3_heading">{{ __('adminWords.section').' 3 '.__('adminWords.heading') }}<sup>*</sup></label>
                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.section').' 3 '.__('adminWords.heading') }}" id="section_3_heading" name="section_3_heading" class="form-control require" value="{{ isset($settings['section_3_heading']) ? $settings['section_3_heading'] : '' }}" />
                        </div>
                        <div class="form-group">
                            <label for="section_3_description">{{ __('adminWords.section').' 3 '.__('adminWords.description') }}<sup>*</sup></label>
                            <textarea placeholder="{{ __('adminWords.enter').' '.__('adminWords.section').' 3 '.__('adminWords.description') }}" id="section_3_description" name="section_3_description" class="form-control require">{{ isset($settings['section_3_description']) ? $settings['section_3_description'] : '' }}</textarea>
                        </div>
                        <p>Note : You have to enable newsletter setting option to show this section in footer.</p>
                    </div>
                    <div class="divideSections">
                        <div class="col lg-12 col-md-12 col-sm-12 col-xs-12 pl-0">
                            <label class="main_label_heading">{{ __('adminWords.section').' 4 ' }}</label>
                        </div>
                        <div class="form-group">
                            <label for="section_4_heading">{{ __('adminWords.section').' 4 '.__('adminWords.heading') }}<sup>*</sup></label>
                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.section').' 4 '.__('adminWords.description') }}" id="section_4_heading" name="section_4_heading" class="form-control require" value="{{ isset($settings['section_4_heading']) ? $settings['section_4_heading'] : '' }}" />
                        </div>
                        <div class="form-group{{ $errors->has('w_email') ? ' has-error' : '' }}">
                            {!! Form::label('w_email', __('adminWords.contact_email')) !!}
                            {!! Form::email('w_email', isset($settings['w_email']) ? $settings['w_email'] : '', ['class' => 'form-control', 'placeholder' => __('adminWords.enter').' '.__('adminWords.contact_email')]) !!}
                            <small class="text-danger">{{ $errors->first('w_email') }}</small>
                        </div>
                        <p>Note : Use comma(,) to separate multiple emails.</p>
                        <div class="form-group{{ $errors->has('$settings[w_phone]') ? ' has-error' : '' }}">
                            {!! Form::label('w_phone', __('adminWords.contact_phone') ) !!}
                            {!! Form::text('w_phone', isset($settings['w_phone']) ? $settings['w_phone'] : '', ['class' => 'form-control' , 'data-valid'=>'mobile', 'placeholder' => __('adminWords.enter').' '.__('adminWords.contact_phone'), 'data-error' => __('adminWords.invalid').' '.__('adminWords.contact_number')]) !!}
                            <small class="text-danger">{{ $errors->first('w_phone') }}</small>
                        </div>
                        <p>Note : Use comma(,) to separate multiple contact number.</p>
                        <div class="form-group{{ $errors->has('w_address') ? ' has-error' : '' }}">
                            {!! Form::label('w_address', __('adminWords.contact_address') ) !!}
                            {!! Form::textarea('w_address', isset($settings['w_address']) ? $settings['w_address'] : '', ['class' => 'form-control', 'rows' => '3', 'placeholder' => __('adminWords.enter').' '.__('adminWords.contact_address')]) !!}
                            <small class="text-danger">{{ $errors->first('w_address') }}</small>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-facebook-f"></i></span>
                            </div>
                            <input type="text" class="form-control require" placeholder="{{ __('adminWords.facebook').' '.__('adminWords.url') }}" name="facebook_url" id="facebook_url" data-valid="facebook" data-error="{{ __('adminWords.invalid').' '.__('adminWords.facebook').' '.__('adminWords.url') }}" value="{{ isset($settings['facebook_url']) ? $settings['facebook_url'] : '' }}">
                        </div>
                       
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-linkedin"></i></span>
                            </div>
                            <input type="text" class="form-control require" placeholder="{{ __('adminWords.linkedin').' '.__('adminWords.url') }}" name="linkedin_url" id="linkedin_url" data-valid="linkedin" data-error="{{ __('adminWords.invalid').' '.__('adminWords.linkedin').' '.__('adminWords.url') }}" value="{{ isset($settings['linkedin_url']) ? $settings['linkedin_url'] : '' }}">
                        </div>
                        
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-twitter"></i></span>
                            </div>
                            <input type="text" class="form-control require" placeholder="{{ __('adminWords.twitter').' '.__('adminWords.url') }}" name="twitter_url" id="twitter_url" data-valid="twitter" data-error="{{ __('adminWords.invalid').' '.__('adminWords.twitter').' '.__('adminWords.url') }}" value="{{ isset($settings['twitter_url']) ? $settings['twitter_url'] : '' }}">
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-google-plus"></i></span>
                            </div>
                            <input type="text" class="form-control require" placeholder="{{ __('adminWords.google_plus').' '.__('adminWords.url') }}" name="google_plus_url" id="google_plus_url" data-valid="google_plus" data-error="{{ __('adminWords.invalid').' '.__('adminWords.google_plus').' '.__('adminWords.url') }}" value="{{ isset($settings['google_plus_url']) ? $settings['google_plus_url'] : '' }}">
                        </div>

                        <div class="form-group">
                            <label for="copyrightText">{{ __('adminWords.copyright_text') }}<sup>*</sup></label>
                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.copyright_text') }}" id="copyrightText" name="copyrightText" class="form-control require" value="{{ isset($settings['copyrightText']) ? $settings['copyrightText'] : '' }}" />
                            <p class="note_pera">{{ __('adminWords.copyright_note_1') }} @php echo htmlentities('"&copy;"') @endphp {{ __('adminWords.copyright_note_2') }}</p>
                        </div>
                    </div>           
                </div>                  
                <div class="col-lg-10"> 
                  <button type="button" data-action="submitThisForm" class="btn btn-primary">{{ __('adminWords.save_setting_btn') }}</button>
                  <div class="clear-both"></div>
                </div>
              </div>
            </div>
          </div>
        {!! Form::close() !!}

    </div>

    <div class="col-lg-12 paypalDonationAccordation">
      {!! Form::open(['method' => 'POST', 'route'=>'donation.save' ]) !!}
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.paypal_donation') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group">
                  <div class="custom-switch checkbox mr-4">
                    {!! Form::checkbox('paypal_donation', 1, isset($settings['paypal_donation']) ? $settings['paypal_donation'] : null, ['class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"paypal_donation_box", 'required-id'=>'#PAYPAL_DONATION_LINK', 'data-type'=>'paypal_donation', 'id'=>'paypal_donation_check']) !!}
                    <label for="paypal_donation_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
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
                  <label for="PAYPAL_DONATION_LINK">{{  __('adminWords.paypal_donation').' '.__('adminWords.link') }}<sup>*</sup></label>
                  {!! Form::text('PAYPAL_DONATION_LINK', (env('PAYPAL_DONATION_LINK') ? env('PAYPAL_DONATION_LINK') : null), ['class' => 'form-control require', 'data-valid' => 'url', 'data-error'=>'Invalid link.']) !!}
                  <small class="text-danger">{{ $errors->first('PAYPAL_DONATION_LINK') }}</small>
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

    <div class="col-lg-12 googleAdSetting">
        {!! Form::open(['method' => 'POST', 'route'=>['saveSocialLoginData', 'newsletter'] ]) !!}
          <div class="card m-b-30">
            <div class="card-header">
              <div class="row">
                <div class="col-lg-8">
                  <h5>{{ __('adminWords.newsletter').' '.__('adminWords.setting') }}</h5>
                </div>
                <div class="col-lg-4 text-right">
                  <div class="form-group">
                    <div class="custom-switch checkbox mr-4">
                      {!! Form::checkbox('is_newsletter', 1, isset($settings['is_newsletter']) ? $settings['is_newsletter'] : null, ['class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"newsltr_box", 'required-id'=>'#MAILCHIMP_APIKEY', 'data-type'=>'paypal_donation', 'data-type' => 'is_newsletter', 'id'=>'newsltr_check']) !!}
                      <label for="newsltr_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                      <input type="hidden" value="{{route('updateStatus')}}" id="URL">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="newsltr_box" class="card-body">
              <div class="row">                    
                <div class="col-lg-10">
                  <div class="form-group{{ $errors->has('MAILCHIMP_APIKEY') ? ' has-error' : '' }}">
                    <label for="MAILCHIMP_APIKEY">{{  __('adminWords.mailchimp_apikey') }}<sup>*</sup></label>
                    {!! Form::text('MAILCHIMP_APIKEY', env('MAILCHIMP_APIKEY'), ['class' => 'form-control require', 'placeholder' => __('adminWords.enter').' '.__('adminWords.mailchimp_apikey')]) !!}
                    <small class="text-danger">{{ $errors->first('MAILCHIMP_APIKEY') }}</small>
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
  <script src="{{asset('public/assets/js/musioo-custom.js?'.time())}}"></script>
@endsection

