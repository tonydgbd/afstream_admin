@extends('layouts.admin.main')
@section('title', __('adminWords.menu'))
@section('style')
  <link href="{{asset('public/assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css"/>
  <link href="{{ asset('public/assets/plugins/switchery/switch.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.menu') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.menu') }}</li>
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
                        <a class="effect-btn btn btn-primary mt-2 mr-2" href="{{ url('menusetting') }}">{{ __('adminWords.go_back') }}</a>
                    </div>                                
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title mb-0">{{ (isset($menuData)) ?__('adminWords.update').' '.__('adminWords.menu') : __('adminWords.create').' '.__('adminWords.menu')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="admin-form"> 
                      @if(isset($menuData))
                        {!! Form::model($menuData, ['method'=>'post', 'files'=>true, 'id'=>'updateMenu', 'route' => ['menu.save', $menuData->id], 'data-redirect' => url('/menusetting')]) !!} 
                      @else
                        {!! Form::open(['method' => 'POST','files' => true, 'route' => ['menu.save', 'add'], 'data-reset'=>1, 'data-redirect' => url('/menusetting')]) !!}
                      @endif
                          
                          <div class="form-group{{ $errors->has('menu_heading') ? ' has-error' : '' }}">
                            <label for="menu_heading">{{ __('adminWords.menu_heading') }}<sup>*</sup></label>
                            {!! Form::text('menu_heading', null, ['class' => 'form-control require', 'required','placeholder'=>__('adminWords.enter').' '.__('adminWords.menu_heading')]) !!}
                            <small class="text-danger">{{ $errors->first('menu_heading') }}</small>
                          </div> 
                          
                          <div class="form-group{{ $errors->has('page_id') ? ' has-error' : '' }}">
                            <label for="page_id">{{  __('adminWords.select').' '.__('adminWords.page') }}<sup>*</sup></label>
                            {!! Form::select('page_id', $pages, (!empty($menuData) ? $menuData->page_id : ''), ['class' => 'form-control select2 require','required','placeholder'=>__('adminWords.select').' '.__('adminWords.page') ]) !!}
                            <small class="text-danger">{{ $errors->first('page_id') }}</small>
                          </div>

                        

                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} switch-main-block">
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('status', 1, (isset($menuData) &&   $menuData->status == 0 ? 0 : 1),['id'=>'Status','class' => 'js-switch-primary']) !!}
                                    {!! Form::label('Status', __('adminWords.status')) !!}
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                </div> 
                            </div>

                         
                          <div class="form-group">      
                              @if(!isset($menuData))
                                <button type="reset" class="effect-btn btn btn-danger"> {{__('adminWords.reset')}}</button>
                              @endif  
                              <button type="button" class="effect-btn btn btn-primary mr-2" data-action="submitThisForm"> {{isset($menuData) ? __('adminWords.update') : __('adminWords.add')}}</button>        
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