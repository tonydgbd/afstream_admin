@extends('layouts.admin.main')
@section('title', __('adminWords.seo').' '.__('adminWords.setting') )
@section('content')     

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.seo').' '.__('adminWords.setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.seo').' '.__('adminWords.setting') }}</li>
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
                        <h5 class="card-title mb-0">{{ __('adminWords.create').' '.__('adminWords.seo').' '.__('adminWords.setting') }}</h5>
                        </div>
                    </div>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['method' => 'POST', 'route'=>'seo.update']) !!}
                    <div class="row">
                        
                        <div class="col-lg-12">
                            <div class="form-group{{ $errors->has('author_name') ? ' has-error' : '' }}">
                              <label for="author_name">{{ __('adminWords.author_name') }}<sup>*</sup></label>
                              {!! Form::text('author_name', isset($settings['author_name']) ? $settings['author_name'] : null, ['class' => 'form-control require', 'placeholder' => __('adminWords.enter').' '.__('adminWords.author_name') ]) !!}
                              <small class="text-danger">{{ $errors->first('author_name') }}</small>
                            </div> 
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group{{ $errors->has('keywords') ? ' has-error' : '' }}">
                              <label for="keywords">{{ __('adminWords.web_keyword') }}<sup>*</sup></label>
                              {!! Form::textarea('keywords', isset($settings['keywords']) ? $settings['keywords'] : null, ['class' => 'form-control require', 'rows' => 3, 'placeholder' => __('adminWords.enter').' '.__('adminWords.web_keyword') ]) !!}
                              <small class="text-danger">{{ $errors->first('keywords') }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group{{ $errors->has('meta_desc') ? ' has-error' : '' }}">
                              <label for="meta_desc">{{ __('adminWords.metadata_desc') }}<sup>*</sup></label>
                              {!! Form::textarea('meta_desc', isset($settings['meta_desc']) ? $settings['meta_desc'] : null, ['class' => 'form-control require', 'rows' => 3, 'placeholder' =>  __('adminWords.enter').' '.__('adminWords.metadata_desc') ]) !!}
                              <small class="text-danger">{{ $errors->first('meta_desc') }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group{{ $errors->has('google_analysis') ? ' has-error' : '' }}">
                              <label for="google_analysis">{{ __('adminWords.google_analysis').' '.__('adminWords.measurement_id') }}</label>
                              {!! Form::textarea('google_analysis', isset($settings['google_analysis']) ? $settings['google_analysis'] : null, ['class' => 'form-control', 'rows' => 3,'placeholder' => __('adminWords.enter').' '.__('adminWords.google_analysis').' '.__('adminWords.measurement_id').' ex. G-Q8RFKJV1B2' ]) !!}
                              <small class="text-danger">{{ $errors->first('google_analysis') }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group{{ $errors->has('fb_pixel') ? ' has-error' : '' }}">
                              <label for="fb_pixel">{{ __('adminWords.fb_pixel_id') }}</label>
                              {!! Form::textarea('fb_pixel', isset($settings['fb_pixel']) ? $settings['fb_pixel'] : null, ['class' => 'form-control', 'rows' => 3 ,'placeholder' => __('adminWords.enter').' '.__('adminWords.fb_pixel_id').' ex. 344245654646721' ]) !!}
                              <small class="text-danger">{{ $errors->first('fb_pixel') }}</small>
                            </div> 
                         </div>
                         <div class="col-lg-12">
                            <button type="button" data-action="submitThisForm" class="effect-btn btn btn-primary">{{ __('adminWords.save_setting_btn') }}</button>
                        </div>
                         <div class="clear-both"></div> 
                    </div>
                     {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 