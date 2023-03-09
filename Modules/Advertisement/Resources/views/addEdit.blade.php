@extends('layouts.admin.main')
@section('title', isset($advData) ? __('adminWords.update').' '.__('adminWords.adv') : __('adminWords.create').' '.__('adminWords.adv') )
@section('style')
    <link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')                

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{isset($advData) ? __('adminWords.update').' '.__('adminWords.adv') : __('adminWords.create').' '.__('adminWords.adv') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.adv') }}</li>
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
                    <h4 class="has-btn"> {{isset($advData) ? __('adminWords.update').' '.__('adminWords.adv') : __('adminWords.create').' '.__('adminWords.adv') }}
                        <span>
                            <a class="effect-btn btn btn-primary" href="{{ url('advertisement') }}">{{ __('adminWords.go_back') }}</a>
                        </span>
                    </h4>
                </div>
                <div class="card-body">
                <div class="admin-form">
                    
                    @if(isset($advData))
                      {!! Form::model($advData, ['method'=>'post', 'files'=>true, 'route'=>['addEditAdv', $advData->id], 'id'=>'updateUser', 'onsubmit'=>'return false', 'data-redirect' => url('/advertisement')]) !!}
                    @else
                        {!! Form::open(['method' => 'POST', 'route' => ['addEditAdv','create'], 'id'=>'addUpdateAlbumForm', 'enctype'=>"multipart/form-data", 'data-reset'=>"1", 'data-modal'=>'1', 'table-reload'=>"musiooDtToShowData", 'data-redirect' => url('/advertisement') ]) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-8"> 
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title">{{ __('adminWords.title') }}<sup>*</sup></label> 
                                {!! Form::text('title', null, ['class' => 'form-control require', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.title')]) !!}
                                <small class="text-danger">{{ $errors->first('adminWords.title') }}</small>
                            </div> 
                            <div class="form-group{{ $errors->has('google_adsense_script') ? ' has-error' : '' }}">
                                <label for="google_adsense_script">{{ __('adminWords.google_adsense_script') }}<sup>*</sup></label> 
                                {!! Form::textarea('google_adsense_script', null, ['class' => 'form-control require', 'rows' => '5', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.google_adsense_script')]) !!}
                                <small class="text-danger">{{ $errors->first('adminWords.google_adsense_script') }}</small>
                            </div>  
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group"> 
                                @if(!isset($advData))
                                    <button type="reset" class="effect-btn btn btn-danger"> {{__('adminWords.reset')}}</button>
                                @endif  
                                <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{isset($advData) ? __('adminWords.update') : __('adminWords.add') }}</button>  
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
@endsection 
@section('script')
    <script src="{{ asset('public/assets/plugins/datepicker/datepicker.min.js') }}"></script> 
    <script src="{{ asset('public/assets/plugins/select2/select2.min.js') }}"></script> 
    <script src="{{ asset('public/assets/plugins/datepicker/i18n/datepicker.en.js') }}"></script>
    <script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  
@endsection
