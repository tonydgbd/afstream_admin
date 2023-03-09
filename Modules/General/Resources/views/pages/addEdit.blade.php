@extends('layouts.admin.main')
@section('title', __('adminWords.pages'))
@section('style')
<link href="{{asset('public/assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css"> 
@endsection
@section('content')                 

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.pages') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.pages') }}</li>
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
                    <div class="text-right">   
                        <a class="effect-btn  btn btn-primary" href="{{ url('pages') }}">{{ __('adminWords.go_back') }}</a>
                    </div>                               
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title mb-0">{{isset($pageData) ? __('adminWords.update').' '.__('adminWords.page') : __('adminWords.create').' '.__('adminWords.page')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="admin-form"> 
                      @if(isset($pageData))
                        {!! Form::model($pageData, ['method' => 'POST', 'onsubmit'=>'return false', 'route'=>['addEditPage', $pageData->id],'files' => true, 'data-redirect' => url('/pages')]) !!}
                      @else
                        {!! Form::open(['method' => 'POST','onsubmit'=>'return false',  'route'=>['addEditPage', 'create'],'files' => true, 'data-reset'=>1, 'data-redirect' => url('/pages')]) !!}
                      @endif
                          <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title">{{ __('adminWords.page').' '.__('adminWords.title') }}<sup>*</sup></label>
                            {!! Form::text('title', null, ['class' => 'form-control require', 'required','placeholder' => __('adminWords.enter').' '.__('adminWords.page').' '.__('adminWords.title')]) !!}
                            <small class="text-danger">{{ $errors->first('title') }}</small>
                          </div>  
                          <div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">
                            <label for="detail">{{ __('adminWords.description') }}<sup>*</sup></label>
                            {!! Form::textarea('detail', null, ['id' => 'summernote','class' => 'form-control require' ,'required']) !!}
                            <small class="text-danger">{{ $errors->first('detail') }}</small>
                          </div> 
                          
                            @if(isset($pageData) && $pageData->slug != 'terms-of-use' && $pageData->slug != 'privacy-policy')
                                <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }} switch-main-block">
                                    <div class="checkbox mr-4">
                                        {!! Form::checkbox('is_active', 1, (isset($pageData) &&   $pageData->is_active == 0 ? 0 : 1),['id'=>'is_active']) !!}
                                        {!! Form::label('is_active', __('adminWords.status') ) !!}
                                        <small class="text-danger">{{ $errors->first('is_active') }}</small>
                                    </div> 
                                </div>
                            @else    
                                {!! Form::checkbox('is_active', 1, 1,['id'=>'is_active','hidden'=>true]) !!}
                            @endif
                            
                          <div class="form-group">    
                          @if(isset($pageData))     
                            <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{ __('adminWords.update') }}</button>  
                          @else
                            <button type="reset" class="effect-btn btn btn-danger"> {{ __('adminWords.reset') }}</button>
                            <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{ __('adminWords.create') }}</button>  
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
<script src="{{asset('public/assets/js/musioo-custom.js')}}"></script>
@endsection 