@extends('layouts.admin.main')
@section('title', __('adminWords.plan'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')              

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{isset($planData) ? __('adminWords.update').' '.__('adminWords.plan') : __('adminWords.create').' '.__('adminWords.plan')}}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.plan') }}</li> 
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
                        <a class="effect-btn btn btn-primary" href="{{ url('plans') }}">{{ __('adminWords.go_back') }}</a>
                    </div>                                  
                    <div class="row align-items-center">
                        <div class="col-6">
                        <h5 class="card-title mb-0">{{isset($planData) ? __('adminWords.update').' '.__('adminWords.plan') : __('adminWords.create').' '.__('adminWords.plan')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                <div class="admin-form">
                    @if(isset($planData))
                      {!! Form::model($planData, ['method'=>'post', 'files'=>true, 'route'=>['addEditPlan', $planData->id], 'id'=>'updateArtist', 'onsubmit'=>'return false', 'data-redirect' => url('plans')]) !!}
                    @else
                        {!! Form::open(['method' => 'POST', 'route' => ['addEditPlan','create'], 'id'=>'addUpdatePlanForm', 'enctype'=>"multipart/form-data", 'data-reset'=>"1",'table-reload'=>"musiooDtToShowData", 'data-redirect' => url('plans') ]) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-6"> 
                            <div class="form-group{{ $errors->has('plan_name') ? ' has-error' : '' }}">
                                <label for="plan_name">{{ __('adminWords.plan_name') }}<sup>*</sup></label>
                                
                                {!! Form::text('plan_name', null, ['class' => 'form-control require', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.plan_name')]) !!}
                                <small class="text-danger">{{ $errors->first('plan_name') }}</small>
                            </div>    
                           
                            <div class="form-group{{$errors->has('plan_amount') ? 'has-error' : ''}} ">
                                <label for="plan_amount">{{ __('adminWords.plan').' '.__('adminWords.amount').' '.__('adminWords.apply_for_free') }}<sup>*</sup></label>
                                
                                {!! Form::number('plan_amount', (isset($planData) ? $planData->plan_amount : 0), ['class' => 'form-control require', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.plan').' '.__('adminWords.amount')]) !!}
                                <small class="text-danger">{{ $errors->first('plan_amount') }}</small>
                           </div>
                            <div class="form-group{{ $errors->has('validity') ? ' has-error' : '' }}">
                                @php @endphp
                                {!! Form::label('validity', (isset($planData) && $planData->plan_amount != 0) ? __('adminWords.validity_in_days') : __('adminWords.validity_in_days'), ['class'=>'validity_month_day']) !!}
                                {!! Form::number('validity', null, ['class' => 'form-control', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.validity') ]) !!}
                                <small class="text-danger">{{ $errors->first('validity') }}</small>
                                <input type="hidden" name="month_days" id="monthDays" value="0">
                            </div>  
                        </div>

                        <div class="col-lg-6">

                            <div class="form-group{{ $errors->has('is_download') ? ' has-error' : '' }} switch-main-block">
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_download', 1, (isset($planData) && $planData->is_download == 1 ? 1 : 0),['id'=>'download_text']) !!}
                                    {!! Form::label('download_text', __('adminWords.download_text') ) !!}
                                    <small class="text-danger">{{ $errors->first('is_download') }}</small>
                                </div> 
                            </div>
                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} switch-main-block">
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('show_advertisement', 1, (isset($planData) && $planData->show_advertisement == 0 ? 0 : 1),['id'=>'show_advertisement']) !!}
                                    {!! Form::label('show_advertisement', __('adminWords.show_advertisement') ) !!}
                                    <small class="text-danger">{{ $errors->first('show_advertisement') }}</small>
                                </div> 
                            </div>

                           
                            <div class="img-upload-preview">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group{{$errors->has('image') ? 'has-error' : ''}}">
                                            <label for="image" class="col-lg-12">{{ __('adminWords.plan').' '.__('adminWords.image') }}<sup>*</sup></label>
                                            <label for="image" class="file-upload-wrapper js-labelFile" data-text="Select your file!" data-toggle="tooltip" data-original-title="Plan Image">
                                                {!! Form::file('image',['class' => 'form-control hide basicImage', 'data-label'=>'atristImage', 'name'=>'image', 'data-ext'=>"['jpg','jpeg','png','svg']", 'data-image-id'=>'plan_image', 'id' => 'image', 'data-image'=>__('adminWords.image_error')]) !!}
                                            <span class="js-fileName"></span>
                                            </label>
                                            <input type="hidden" id="image_name" value="{{(isset($planData) ? $planData->image:'')}}">
                                            <span class="image_title" id="atristImage">{{(isset($planData) && $planData->image != '' ? $planData->image : '' )}}</span>
                                            <small class="text-danger">{{ $errors->first('image')}}</small>
                                            <input type="hidden" id="plan_image" />
                                            <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 500X500 px' }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="image-block site_image_dv">
                                            @if(isset($planData->image) && $planData->image != null)
                                                <img src="{{asset('public/images/plan/'.$planData->image)}}" class="img-responsive" alt="" height="200px" width="200px">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} switch-main-block">
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('status', 1, (isset($planData) && $planData->status == 0 ? 0 : 1),['id'=>'status']) !!}
                                    {!! Form::label('status', __('adminWords.status') ) !!}
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                </div> 
                            </div>
                            
                            <div class="form-group{{ $errors->has('in_app_purchase') ? ' has-error' : '' }} switch-main-block">
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('in_app_purchase', 1, (isset($planData) && $planData->in_app_purchase == 1 ? 1 : 0),['id'=>'in_app_purchase']) !!}
                                    {!! Form::label('in_app_purchase', __('adminWords.in_app_purchase') ) !!}
                                    <small class="text-danger">{{ $errors->first('in_app_purchase') }}</small>
                                </div> 
                            </div>
                            <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}" id="IosAppProductId">
                                <label for="product_id">{{ __('adminWords.plan_product_id') }}<sup>*</sup></label>
                                {!! Form::text('product_id', null, ['class' => 'form-control intAppProductId', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.plan_product_id')]) !!}
                                <small class="text-danger">{{ $errors->first('product_id') }}</small>
                            </div>
                            
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group"> 
                                @if(!isset($planData))
                                    <button type="reset" class="effect-btn btn btn-danger"> {{__('adminWords.reset')}}</button>
                                @endif  
                                <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{isset($planData) ? __('adminWords.update') : __('adminWords.add') }}</button>  
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
