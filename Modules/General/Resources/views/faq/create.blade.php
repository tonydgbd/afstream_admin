@extends('layouts.admin.main')
@section('title', __('adminWords.faq'))
@section('style')
  <link href="{{asset('public/assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
             
<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.faq') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.faq') }}</li>
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
                    <h4 class="has-btn">{{ (isset($faqs)) ?__('adminWords.update').' '.__('adminWords.faq') : __('adminWords.create').' '.__('adminWords.faq')}}  <span><a class="effect-btn btn btn-primary" href="{{ url('faq') }}">{{ __('adminWords.go_back') }}</a></span> </h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="admin-form"> 
                        @if(isset($faqs))
                          {!! Form::model($faqs, ['method'=>'post', 'files'=>true, 'id'=>'updateFaq', 'route' => ['faq.update', $faqs->id], 'data-redirect' => url('/faq')]) !!}
                        @else
                          {!! Form::open(['method' => 'POST','files' => true, 'route' => ['faq.update', 'addFaq'], 'data-reset'=>1, 'data-redirect' => url('/faq')]) !!}
                        @endif
                          
                          <div class="form-group{{ $errors->has('question') ? ' has-error' : '' }}">
                            <label for="question">{{  __('adminWords.question') }}<sup>*</sup></label>
                            {!! Form::text('question', null, ['class' => 'form-control require', 'required','placeholder'=>__('adminWords.enter').' '.__('adminWords.question')]) !!}
                            <small class="text-danger">{{ $errors->first('question') }}</small>
                          </div> 
                          
                          <div class="form-group{{ $errors->has('answer') ? ' has-error' : '' }}">
                            <label for="answer">{{  __('adminWords.answer') }}<sup>*</sup></label>
                            {!! Form::textarea('answer', null, ['class' => 'form-control require' ,'required', 'rows' => 3]) !!}
                            <small class="text-danger">{{ $errors->first('answer') }}</small>
                          </div> 
                         
                            
                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} switch-main-block">
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('status', 1, (isset($faqs) &&   $faqs->status == 0 ? 0 : 1),['id'=>'Status']) !!}
                                    {!! Form::label('Status', __('adminWords.status')) !!}
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                </div> 
                            </div>

                          <div class="form-group">      
                              @if(!isset($faqs))
                                <button type="reset" class="effect-btn btn btn-danger"> {{__('adminWords.reset')}}</button>
                              @endif  
                              <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{isset($faqs) ? __('adminWords.update') : __('adminWords.add')}}</button>        
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