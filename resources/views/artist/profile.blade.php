@extends('layouts.artist.main')
@section('title', __('adminWords.my_profile')) 
@section('style')
<link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('public/assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')


@php //print_r($state); die('artistIntegration'); @endphp
<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.artist').' '.__('adminWords.my_profile') }}</h4> 
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">  
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{ route('artist.home') }}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.artist').' '.__('adminWords.my_profile') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="contentbar">                
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">                
                <div class="card-body">
                  <div class="admin-form">                     
                    {!! Form::model($user, ['method'=>'post', 'files'=>true, 'route'=>['artist.profile_update'], 'data-redirect' => route('artist.profile')]) !!}
                    <div class="row">
                        <div class="col-lg-6"> 
                        <div class="form-group{{$errors->has('name') ? 'has-error' : ''}}">
                          <label for="name">{{ __('adminWords.user').' '.__('adminWords.name') }}<sup>*</sup></label>
                          {!! Form::text('name', null, ['class' => 'form-control require', 'required', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.name') ]) !!}
                          <small class="text-danger">{{ $errors->first('name')}}</small>
                        </div>

                        <div class="form-group dd-flex">
                            <div class="radio radio-primary mr-4">
                              {!! Form::radio('gender', 0, (isset($user) && $user->gender == 0 ? 'checked' : (!isset($user) ? 'checked' : '')), ['id'=>'male']) !!}
                              {!! Form::label('male', null, ['class' => 'mb-0']) !!}
                            </div>
                            <div class="radio radio-primary mr-4">
                              {!! Form::radio('gender', 1, (isset($user) && $user->gender == 1 ? 'checked' : ''), ['id'=>'female']) !!}
                              {!! Form::label('female', null, ['class' => 'mb-0']) !!}
                            </div>                          
                        </div>
                        


                        <div class="form-group{{$errors->has('email') ? 'has-error' : ''}}">
                          <label for="email">{{ __('adminWords.user_email') }}<sup>*</sup></label>
                          {!! Form::email('email', null, ['class' => 'form-control require', 'required','readonly' => 'true', 'data-valid'=>'email', 'data-error'=>__('adminWords.invalid').' '.__('adminWords.email').'.', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.user_email') ]) !!}
                          <small class="text-danger">{{ $errors->first('email')}}</small>
                        </div>
                        <div class="form-group{{$errors->has('password') ? 'has-error' : ''}}">
                          <label for="password">{{ __('adminWords.user').' '.__('adminWords.password') }}</label>
                          {!! Form::password('password',['class' => 'form-control '.(isset($passwordReq) ? 'require' : ''), 'placeholder'=>__('adminWords.enter').' '.__('adminWords.user').' '.__('adminWords.password')]) !!}
                        </div>
                        <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                          <label for="mobile">{{ __('adminWords.mobile') }}<sup>*</sup></label>
                          {!! Form::text('mobile', null, ['class' => 'form-control require', 'max-length'=>'12', 'length'=>'10' , 'data-length-error'=>__('adminWords.invalid').' '.__('adminWords.mobile').' '.__('adminWords.number') , 'required', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.mobile').' '.__('adminWords.number')]) !!}
                          <small class="text-danger">{{ $errors->first('mobile') }}</small>
                        </div>                        

                        <div class="img-upload-preview">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group{{$errors->has('image') ? 'has-error' : ''}}">
                                      {!! Form::label('image', __('adminWords.user').' '.__('adminWords.image'), ['class'=>'col-lg-12']) !!}
                                      <label for="image" class="file-upload-wrapper js-labelFile" data-text="Select your file!" data-toggle="tooltip" data-original-title="User Image">
                                        <i class="icon fa fa-check"></i>
                                        {!! Form::file('image',['class' => 'form-control hide', 'name'=>'userProfileImage']) !!}
                                        <span class="js-fileName"></span>
                                      </label>
                                        <input type="hidden" id="image_name" value="{{(isset($user) ? $user->image:'')}}">
                                        <span class="image_title">{{(isset($user) && $user->image != '' ? $user->image : '' )}}</span>
                                      <small class="text-danger">{{ $errors->first('image')}}</small>
                                      <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 500X500 px' }} </p>
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                  <div class="image-block site_image_dv">
                                      @if(isset($user->image) && $user->image != null) 
                                          <img src="{{asset('public/images/user/'.$user->image)}}" class="img-responsive" alt="" height="100px" width="100px">
                                      @endif
                                  </div>
                              </div>
                            </div>
                        </div>

                      </div>

                      <div class="col-lg-6">
                        <div class="form-group{{$errors->has('dob') ? 'has-error' : ''}}">
                          {!! Form::label('dob', __('adminWords.dob')) !!}
                          <div class="input-group">
                            {!! Form::text('dob', (isset($user) && $user->dob !='' ? $user->dob : ''), ['class' =>'form-control autoclose-date', 'data-language'=>'en', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.dob')]) !!}
                            
                            <small class="text-danger">{{ $errors->first('dob') }}</small>
                           </div>
                        </div>
                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            {!! Form::label('address', __('adminWords.address')) !!}
                            {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => '3']) !!}
                            <small class="text-danger">{{ $errors->first('address') }}</small>
                          </div> 
                        <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
                          {!! Form::label('country_id', __('adminWords.select').' '.__('adminWords.country') ) !!}
                          
                          {!! Form::select('country_id', $country, (isset($user) ? $user->country_id : ''), ['class' => 'form-control select2WithSearch','placeholder' => __('adminWords.choose'), 'data-url'=>url('/fetch_states')]) !!}
                          <small class="text-danger">{{ $errors->first('country_id') }}</small>
                        </div> 
                        <div class="form-group{{ $errors->has('state_id') ? ' has-error' : '' }}">
                          {!! Form::label('state_id', __('adminWords.select').' '.__('adminWords.state')) !!}
                          {!! Form::select('state_id', $state, (isset($user) ? $user->state_id : ''), ['class' => 'form-control select2WithSearch','placeholder' => __('adminWords.choose'), 'data-url'=>url('/fetch_city')]) !!}
                          <small class="text-danger">{{ $errors->first('state_id') }}</small>
                        </div> 
                        <div class="form-group{{ $errors->has('city_id') ? ' has-error' : '' }}">
                          {!! Form::label('city_id', __('adminWords.select').' '.__('adminWords.city')) !!}
                          {!! Form::select('city_id', $city, (isset($user) ? $user->city_id : ''), ['class' => 'form-control select2WithSearch','placeholder' => __('adminWords.choose')]) !!}
                          <small class="text-danger">{{ $errors->first('city_id') }}</small>
                        </div> 
                        <div class="form-group{{ $errors->has('pincode') ? ' has-error' : '' }}">
                          {!! Form::label('pincode', __('adminWords.pincode')) !!}
                          {!! Form::number('pincode', null, ['class' => 'form-control', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.pincode')]) !!}
                          <small class="text-danger">{{ $errors->first('pincode') }}</small>
                        </div> 
                      </div>
                      <div class="col-lg-8">
                        <div class="form-group"> 
                          <button type="button" class="effect-btn btn btn-primary mt-2 mr-2" data-action="submitThisForm"> {{isset($user) ? __('adminWords.update') : __('adminWords.save') }}</button>  
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
  <script src="{{ asset('public/assets/js/artist-custom.js') }}"></script>  
@endsection
