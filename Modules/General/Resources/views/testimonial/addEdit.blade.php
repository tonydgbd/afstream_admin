@extends('layouts.admin.main')
@section('title', __('adminWords.testimonial'))
@section('style')
<link href="{{asset('public/assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('public/assets/css/star-rating.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('public/assets/plugins/switchery/switch.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('rightbar-content')
             
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">{{ __('adminWords.testimonial') }}</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('adminWords.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ __('adminWords.testimonial') }}</a></li>
                </ol>
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
                        <a class="btn btn-primary" href="{{ url('testimonial') }}">{{ __('adminWords.go_back') }}</a>
                    </div>                                
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title mb-0">{{ (isset($testimonials)) ? __('adminWords.update').' '.__('adminWords.testimonial') : __('adminWords.create').' '.__('adminWords.testimonial') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="admin-form"> 
                          @if(isset($testimonials))
                            {!! Form::model($testimonials, ['method' => 'POST', 'onsubmit'=>'return false', 'route' => ['addEditTestimonial', $testimonials->id], 'files' => true, 'data-redirect' => url('testimonial')]) !!}
                          @else 
                            {!! Form::open(['method' => 'POST', 'onsubmit'=>'return false', 'route' => ['addEditTestimonial', 'create'], 'files' => true, 'data-redirect' => url('testimonial')]) !!}
                          @endif
                          <div class="form-group{{ $errors->has('client_name') ? ' has-error' : '' }}">
                            <label for="client_name">{{ __('adminWords.client_name') }}<sup>*</sup></label>
                            {!! Form::text('client_name', null, ['class' => 'form-control require', 'required','placeholder'=> __('adminWords.enter').' '.__('adminWords.client_name')]) !!}
                            <small class="text-danger">{{ $errors->first('client_name') }}</small>
                          </div>

                          <div class="form-group{{ $errors->has('designation') ? ' has-error' : '' }}">
                            {!! Form::label('designation', __('adminWords.designation') )!!}
                            {!! Form::text('designation', null, ['class' => 'form-control', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.designation')]) !!}
                            <small class="text-danger">{{ $errors->first('designation') }}</small>
                          </div>  
                          <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} input-file-block"> 
                            {!! Form::label('image', __('adminWords.testimonial').' '.__('adminWords.image') ) !!}
                            {!! Form::file('image', ['class' => 'input-file hide basicImage', 'data-label'=>'testimonialImage', 'name'=>'image', 'data-ext'=>"['jpg','jpeg','png']", 'data-image-id'=>'test_image', 'data-image'=>__('adminWords.image_error')]) !!}
                            <label for="image" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Testimonial Image">
                              <i class="icon fa fa-check"></i>
                              <span class="js-fileName">{{ __('adminWords.choose_image') }}</span>
                            </label>
                            <input type="hidden" id="image_name" value="{{(!empty($testimonials) ? $testimonials->image:'')}}">
                            <span class="image_title" id="testimonialImage">{{(!empty($albumData) && $albumData->image != '' ? $albumData->image : __('adminWords.choose_image') )}}</span>
          
                            <small class="text-danger">{{ $errors->first('image') }}</small>
                            <input type="hidden" id="test_image" />
                            <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 50X50 px' }} </p>
                          </div>

                          <div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">
                            <label for="detail">{{ __('adminWords.description') }}<sup>*</sup></label>
                            {!! Form::textarea('detail', null, ['id' => 'summernote','class' => 'form-control require' ,'required']) !!}
                            <small class="text-danger">{{ $errors->first('detail') }}</small>
                          </div> 
                          <div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
                           {!! Form::label('rating') !!}
                            <div class="col-md-6">
                              <div class="rating" data-rating="{{ isset($testimonials) ? $testimonials->rating : 0 }}"></div>
                              <input type="hidden" value="" name="rating" class="live-rating" />
                            </div>
                          </div> 
                          <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }} switch-main-block">
                            <div class="row">
                              <div class="col-lg-3">
                                {!! Form::label('is_active', __('adminWords.status') ) !!}
                              </div>
                              <div class="col-lg-2">
                                {!! Form::checkbox('status', 1,isset($testimonials) ? $testimonials->status : 0, ['class' => 'js-switch-primary']) !!}     
                              </div>
                            </div>
                            <div class="col-xs-12">
                              <small class="text-danger">{{ $errors->first('status') }}</small>
                            </div>
                          </div>
                                                    
                          <div class="form-group">            
                          @if(isset($testimonials))
                            <button type="button" class="btn btn-primary" data-action="submitThisForm"> {{ __('adminWords.update') }}</button>  
                          @else
                            <button type="reset" class="btn btn-danger"> {{ __('adminWords.reset') }}</button>
                            <button type="button" class="btn btn-primary" data-action="submitThisForm"> {{ __('adminWords.create') }}</button>  
                          @endif
                          </div>
                          <div class="clear-both"></div>
                        {!! Form::close() !!}
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
@section('script')
<script src="{{asset('public/assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('public/assets/js/star-rating.js')}}"></script>
<script src="{{ asset('public/assets/plugins/switchery/switch.min.js') }}"></script> 
<script src="{{asset('public/assets/js/musioo-custom.js')}}"></script>
@endsection 