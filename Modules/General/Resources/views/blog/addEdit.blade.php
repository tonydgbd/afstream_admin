@extends('layouts.admin.main')
@section('title', __('adminWords.blog'))
@section('style')
<link href="{{asset('public/assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('public/assets/plugins/switchery/switch.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.blogs') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.blogs') }}</li>
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
                    <h4 class="has-btn">
                        {{ (isset($blogData)) ? __('adminWords.update').' '.__('adminWords.blog') : __('adminWords.create').' '.__('adminWords.blog') }}
                        <span>
                            <a class="effect-btn btn btn-primary" href="{{ url('blog') }}">{{ __('adminWords.go_back') }}</a>
                        </span>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="admin-form blog-create"> 
                            
                                    @if(isset($blogData))
                                         {!! Form::model($blogData, ['method'=>'post', 'files'=>true, 'id'=>'updateBlog', 'onsubmit'=>'return false', 'route'=>['addEditBlog', $blogData->id], 'data-redirect' => url('/blog')]) !!}
                                    @else
                                        {!! Form::open(['method' => 'POST','files' => true, 'onsubmit'=>'return false', 'route'=>['addEditBlog','create'], 'data-reset' => 1, 'data-redirect' => url('/blog')]) !!}
                                    @endif
                                    <div class="row">
                                        <div class="col-md-12">
                                          <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                            <label for="title">{{  __('adminWords.blog').' '.__('adminWords.title') }}<sup>*</sup></label>
                                            {!! Form::text('title', null, ['class' => 'form-control require', 'required', 'placeholder' => __('adminWords.enter').' '.__('adminWords.blog').' '.__('adminWords.title')]) !!}
                                            <small class="text-danger">{{ $errors->first('title') }}</small>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group{{ $errors->has('keywords') ? ' has-error' : '' }}">
                                            <label for="keywords">{{ __('adminWords.blog').' '.__('adminWords.web_keyword') }}</label>
                                            {!! Form::text('keywords', null, ['class' => 'form-control', 'placeholder' => __('adminWords.enter').' '.__('adminWords.blog').' '.__('adminWords.web_keyword')]) !!}
                                            <small class="text-danger">{{ $errors->first('keywords') }}</small>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group{{ $errors->has('blog_cat_id') ? ' has-error' : '' }}">
                                            <label for="blog_cat_id">{{ __('adminWords.select').' '.__('adminWords.blog').' '.__('adminWords.category') }}<sup>*</sup></label>
                                            {!! Form::select('blog_cat_id', $blog_category, (!empty($blogData) ? $blogData->blog_cat_id : ''), ['class' => 'form-control select2 require','required','placeholder'=>__('adminWords.select').' '.__('adminWords.blog').' '.__('adminWords.category')]) !!}
                                            <small class="text-danger">{{ $errors->first('blog_cat_id') }}</small>
                                          </div>
                                        </div>
                                        <div class="col-md-12">
                                          <div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">
                                            <label for="detail">{{ __('adminWords.description') }}<sup>*</sup></label>
                                            {!! Form::textarea('detail', null, ['id' => 'summernote','class' => 'form-control require' ,'required']) !!}
                                            <small class="text-danger">{{ $errors->first('detail') }}</small>
                                          </div> 
                                        </div>
                                        <div class="col-md-12">
                                            <div class="img-upload-preview">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} input-file-block">
                                                            <label for="image">{{ __('adminWords.image') }}<sup>*</sup></label>
                                                            {!! Form::file('image', ['class' => 'input-file hide basicImage file-upload-wrapper', 'data-text' =>"Select your file!", 'id'=>'image', 'data-label'=>'BlogLabel', 'data-ext'=>"['jpg','jpeg','png']", 'data-image-id'=>'blogImage']) !!}
                                                            <label for="image" class="js-labelFile" data-toggle="tooltip" data-original-title="Blog Image">                                  
                                                              <span class="js-fileName"></span>
                                                            </label>
                                                            <span class="info" id="BlogLabel">{{!empty($blogData) && $blogData->image != '' ? $blogData->image : '' }}</span>
                                                            <input type="hidden" id="blogImage" />
                                                            <small class="text-danger">{{ $errors->first('image') }}</small>
                                                            <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 1050X700 px' }} </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="image-block site_image_dv">
                                                            @if(isset($blogData->image) && $blogData->image != null) 
                                                                <img src="{{asset('public/images/blogs/'.$blogData->image)}}" class="img-responsive" alt="">
                                                            @else 
                                                                <img src="" id="showLogo" class="img-responsive" alt="">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                      </div>
                                    <div class="col-md-12">
                                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} switch-main-block">
                                                  
                                            <div class="checkbox mr-4">
                                                {!! Form::checkbox('is_active', 1, (isset($blogData) && $blogData->is_active == 0 ? 0 : 1),['id'=>'is_active']) !!}
                                                {!! Form::label('is_active', __('adminWords.status') ) !!}
                                                <small class="text-danger">{{ $errors->first('is_active') }}</small>
                                            </div> 
                                        </div> 
                                    </div> 
                                    <div class="col-md-12">
                                      <div class="form-group">            
                                        @if(isset($blogData))
                                          <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{ __('adminWords.update') }}</button>  
                                        @else
                                          <button type="reset" class="effect-btn btn btn-danger"> {{ __('adminWords.reset') }}</button>
                                          <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{ __('adminWords.create') }}</button>  
                                        @endif
                                      </div>
                                      </div> 
                                      <div class="clear-both"></div>
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
<script src="{{asset('public/assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('public/assets/js/musioo-custom.js')}}"></script>
@endsection 