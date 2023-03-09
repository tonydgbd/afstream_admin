@extends('layouts.admin.main')
@section('title', __('adminWords.google_ad').' '.__('adminWords.setting'))
@section('content')           

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.google_ad').' '.__('adminWords.setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list googleAdAccordation">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.google_ad') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="contentbar">  
  <div class="row">
    <div class="col-lg-12">      
      @php if(sizeof($google_ad) > 0){ @endphp
        {!! Form::model($google_ad[0], ['method'=>'post', 'route'=>['google_ad',$google_ad[0]->id] ]) !!}
        <input type="hidden" value="{{url('changeAdsenseStatus/'.$google_ad[0]->id)}}" id="URL">
      @php } 
      else{ @endphp
        {!! Form::open(['method' => 'POST', 'route'=>['google_ad','add'], 'data-redirect'=> url('google/ad') ]) !!}
        <input type="hidden" value="{{url('changeAdsenseStatus/add')}}" id="URL">
      @php } @endphp
        <div class="card m-b-30">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <h5>{{ __('adminWords.google_ad').' '.__('adminWords.setting') }}</h5>
              </div>
              <div class="col-lg-4 text-right">
                <div class="form-group">
                  <div class="custom-switch checkbox mr-4">
                  
                    {!! Form::checkbox('status', 1, (sizeof($google_ad) > 0 ? $google_ad[0]->status : null), ['id'=>'g_check', 'class' => 'updateSettingRecords js-switch-primary', 'data-id'=>"g_box", 'required-id'=>'#google_ad_script', 'data-type'=>'is_google_ad']) !!}
                    <label for="g_check"> {{ __('adminWords.active').' / '.__('adminWords.inactive') }}</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="g_box" class="card-body">
            <div class="row">                    
              <div class="col-lg-10">
                <div class="form-group{{ $errors->has('google_ad_script') ? ' has-error' : '' }}">
                    <label for="google_ad_script">{{   __('adminWords.google_ad').' '.__('adminWords.script') }}<sup>*</sup></label>
                    {!! Form::textarea('google_ad_script', env('google_ad_script'), ['class' => 'form-control require', 'rows' => '3', 'placeholder' => __('adminWords.enter').' '.__('adminWords.google_ad').' '.__('adminWords.script')]) !!}
                    <small class="text-danger">{{ $errors->first('google_ad_script') }}</small>
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
  <script src="{{ asset('public/assets/js/musioo-custom.js?'.time()) }}"></script>
@endsection