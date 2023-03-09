@extends('layouts.admin.main')
@section('title', __('adminWords.artist'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')      

   
   <!-- Page Title Start -->
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper">
                <div class="page-title-box">
                    <h4 class="page-title bold">{{isset($artistData) ? __('adminWords.update').' '.__('adminWords.artist') : __('adminWords.create').' '.__('adminWords.artist')}}</h4>
                </div>
                <div class="breadcrumb-list">
                    <ul>
                        <li class="breadcrumb-link">
                            <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                        </li>
                        <li class="breadcrumb-link active">{{ __('adminWords.artist') }}</li>
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
                        <a class="effect-btn btn btn-primary" href="{{ url('artist') }}">{{ __('adminWords.go_back') }}</a>
                    </div>                                 
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title mb-0">{{isset($artistData) ? __('adminWords.update').' '.__('adminWords.artist') : __('adminWords.create').' '.__('adminWords.artist')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                <div class="admin-form">
                    
                    @if(isset($artistData))
                      {!! Form::model($artistData, ['method'=>'post', 'files'=>true, 'route'=>['addEditArtist', $artistData->id], 'id'=>'updateArtist', 'onsubmit'=>'return false', 'data-redirect' => url('/artist')]) !!}
                    @else
                        {!! Form::open(['method' => 'POST', 'route' => ['addEditArtist','create'], 'id'=>'addUpdateArtistForm', 'enctype'=>"multipart/form-data", 'data-reset'=>"1", 'data-modal'=>'1', 'table-reload'=>"musiooDtToShowData", 'data-redirect' => url('/artist') ]) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-6"> 
                            <div class="form-group{{ $errors->has('artist_name') ? ' has-error' : '' }}">
                                <label for="artist_name">{{ __('adminWords.artist_name') }}<sup>*</sup></label> 
                                {!! Form::text('artist_name', null, ['class' => 'form-control require', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.artist_name')]) !!}
                                <small class="text-danger">{{ $errors->first('artist_name') }}</small>
                            </div>    
                            
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email">{{ __('adminWords.email') }}<sup>*</sup></label> 
                                {!! Form::text('email', null, ['class' => 'form-control require', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.email')]) !!}
                                <small class="text-danger">{{ $errors->first('email') }}</small>
                            </div>
                            @php
                                if(isset($artistData) && empty($artistData->password))
                                    $passRequire = 'require';
                                else
                                    $passRequire = '';                                
                            @endphp

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password">{{ __('adminWords.password') }}@if(isset($artistData) && empty($artistData->password))  <sup>*</sup> @endif</label> 
                                <input type="password" name="password" class="form-control {{ $passRequire }}" placeholder="{{ __('adminWords.enter').' '.__('adminWords.password') }}"> 
                                <small class="text-danger">{{ $errors->first('password') }}</small>
                            </div>

                            <div class="form-group{{ $errors->has('audio_language_id') ? ' has-error' : '' }}">
                                <label for="audio_language_id">{{ __('adminWords.select').' '.__('adminWords.audio').' '.__('adminWords.language') }}<sup>*</sup></label>
                                <select name="audio_language_id[]" class="form-control multipleSelectWithSearch require" data-placeholder="{{__('adminWords.choose')}}"  multiple="multiple">
                                    @if(!empty($audioLanguage))
                                        @foreach($audioLanguage as $key => $language) 
                                            <option value="{{$key}}" 
                                            @if(isset($artistData->audio_language_id)) @foreach(json_decode($artistData->audio_language_id) as $aid) {{ $aid == $key ? "selected" : "" }} @endforeach @endif >{{ $language }}</option>
                                        @endforeach         
                                    @else            
                                        <option>No Audio Language Found</option>
                                    @endif
                                </select>

                              <small class="text-danger">{{ $errors->first('audio_language_id') }}</small>
                            </div>                           

                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                {!! Form::label('description', __('adminWords.description') ) !!}
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '6', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.description') ]) !!}
                                <small class="text-danger">{{ $errors->first('description') }}</small>
                            </div>  

                        </div>
                        
                        <div class="col-lg-6">

                            <div class="form-group mb-0 dd-flex">
                                <div class="checkbox mr-4">                                          
                                    {!! Form::checkbox('status', 1, (isset($artistData) && $artistData->status == 0 ? 0 : 1),['id'=>'status']) !!}                                           
                                    {!! Form::label('status', __('adminWords.status') ) !!}
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_featured', 1, (isset($artistData) &&   $artistData->is_featured == 0 ? 0 : 1),['id'=>'is_featured']) !!}
                                    {!! Form::label('is_featured', __('adminWords.featured')) !!}
                                    <small class="text-danger">{{ $errors->first('is_featured') }}</small>
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_trending', 1, (isset($artistData) &&   $artistData->is_trending == 0 ? 0 : 1),['id'=>'is_trending']) !!}
                                    {!! Form::label('is_trending', __('adminWords.trending')) !!}
                                    <small class="text-danger">{{ $errors->first('is_trending') }}</small>
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_recommended', 1, (isset($artistData) &&   $artistData->is_recommended == 0 ? 0 : 1),['id'=>'is_recommended']) !!}
                                    {!! Form::label('is_recommended', __('adminWords.recommended') ) !!}
                                    <small class="text-danger">{{ $errors->first('is_recommended') }}</small>
                                </div>
                   
                            </div>
                            

                            <div class="img-upload-preview">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="image" class="col-lg-12">{{ __('adminWords.artist').' '.__('adminWords.image') }}<sup>*</sup></label> 
                                        <div class="form-group{{$errors->has('image') ? 'has-error' : ''}}">                                            
                                            <label for="image" class="js-labelFile file-upload-wrapper" data-text="Select your file!" data-toggle="tooltip" data-original-title="Artist Image">
                                            
                                                {!! Form::file('image',['class' => 'form-control hide basicImage', 'data-label'=>'atristImage', 'id'=>'image', 'name'=>'image', 'data-ext'=>"['jpg','jpeg','png']", 'data-image-id'=>'artist_image', 'data-image'=>__('adminWords.image_error')]) !!}
                                                <span class="js-fileName"></span>
                                            </label>
                                            <input type="hidden" id="image_name" value="{{(isset($artistData) ? $artistData->image:'')}}"/></br>
                                            <span class="image_title" id="atristImage">{{(isset($artistData) && $artistData->image != '' ? $artistData->image : __('adminWords.choose_image') )}}</span>
                                            <small class="text-danger">{{ $errors->first('image')}}</small>
                                            <input type="hidden" id="artist_image" />
                                            <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 500X500 px' }} </p>
                                        </div>    
                                    </div>
                                    <div class="col-md-6">
                                        <div class="image-block site_image_dv">
                                            @if(isset($artistData->image) && $artistData->image != null) 
                                                <img src="{{asset('public/images/artist/'.$artistData->image)}}" class="img-responsive" alt="" height="200px" width="200px">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('artist_genre') ? ' has-error' : '' }}">
                                <label for="artist_genre">{{ __('adminWords.select').' '.__('adminWords.artist_genres') }}<sup>*</sup></label> 
                                {!! Form::select('artist_genre_id', $artistGenre, (isset($artistData) && $artistData->artist_genre_id ? $artistData->artist_genre_id : ''), ['class' => 'form-control select2WithSearch require','placeholder' => __('adminWords.choose'), 'name'=>'artist_genre']) !!}
                                <small class="text-danger">{{ $errors->first('artist_genre') }}</small>
                            </div>

                            <div class="form-group{{ $errors->has('artist_verify_status') ? ' has-error' : '' }}">
                                <label for="artist_verify_status">{{ __('adminWords.select').' '.__('adminWords.verify_status') }}<sup>*</sup></label>
                                <select name="artist_verify_status" class="form-control select2WithSearch require" data-placeholder="{{__('adminWords.choose')}}">
                                    <option value="">--{{ __('adminWords.select').' '.__('adminWords.verify_status') }}--</option>
                                    <option value="P" @if(isset($artistData)) {{ ($artistData->artist_verify_status) == 'P' ? 'selected' : '' }} @endif>{{ __('adminWords.pending') }}</option>
                                    <option value="A" @if(isset($artistData)){{ ($artistData->artist_verify_status) == 'A' ? 'selected' : '' }}@endif>{{ __('adminWords.approved') }}</option> 
                                    <option value="R" @if(isset($artistData)){{ ($artistData->artist_verify_status) == 'R' ? 'selected' : '' }}@endif>{{ __('adminWords.reject') }}</option>
                                </select> 
                                <small class="text-danger">{{ $errors->first('artist_verify_status') }}</small>
                            </div>  
                            

                        </div>
                        <div class="col-lg-8">
                            <div class="form-group"> 
                                @if(!isset($artistData))
                                    <button type="reset" class="effect-btn btn btn-danger"> {{__('adminWords.reset')}}</button>
                                @endif  
                                <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{isset($artistData) ? __('adminWords.update') : __('adminWords.add') }}</button>  
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
    <script src="{{ asset('public/assets/plugins/select2/select2.min.js') }}"></script> 
    <script src="{{ asset('public/assets/plugins/datepicker/i18n/datepicker.en.js') }}"></script>  
    <script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  
@endsection
