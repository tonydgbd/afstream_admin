@extends('layouts.admin.main')
@section('title', __('adminWords.album'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/plugins/switchery/switch.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')               

<!-- Page Title Start -->
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper">
                <div class="page-title-box">
                    <h4 class="page-title bold">{{!empty($albumData) ? __('adminWords.update').' '.__('adminWords.album') : __('adminWords.create').' '.__('adminWords.album') }}</h4>
                </div>
                <div class="musioo-brdcrmb breadcrumb-list">
                    <ul>
                        <li class="breadcrumb-link">
                            <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                        </li>
                        <li class="breadcrumb-link active">{{ __('adminWords.album') }}</li>
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
                        <a class="effect-btn btn btn-primary" href="{{ url('album') }}">{{ __('adminWords.go_back') }}</a>
                    </div>                               
                    <div class="row align-items-center">
                        <div class="col-6">
                        <h5 class="card-title mb-0"> {{!empty($albumData) ? __('adminWords.update').' '.__('adminWords.album') : __('adminWords.create').' '.__('adminWords.album') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                <div class="admin-form">
                    
                    @if(!empty($albumData))
                      {!! Form::model($albumData, ['method'=>'post', 'files'=>true, 'route'=>['addEditAlbum', $albumData->id], 'id'=>'updateUser', 'onsubmit'=>'return false', 'data-redirect' => url('/album')]) !!}
                    @else
                        {!! Form::open(['method' => 'POST', 'route' => ['addEditAlbum','create'], 'id'=>'addUpdateAlbumForm', 'enctype'=>"multipart/form-data", 'data-reset'=>"1", 'data-redirect' => url('/album') ]) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-6"> 
                            <div class="form-group{{ $errors->has('album_name') ? ' has-error' : '' }}">
                                <label for="album_name">{{ __('adminWords.album').' '.__('adminWords.name') }}<sup>*</sup></label> 
                                {!! Form::text('album_name', null, ['class' => 'form-control require', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.album').' '.__('adminWords.name')]) !!}
                                <small class="text-danger">{{ $errors->first('album_name') }}</small>
                            </div>    
                                    
                            <div class="form-group{{ $errors->has('language_id') ? ' has-error' : '' }}">
                                <label for="language">{{ __('adminWords.select').' '.__('adminWords.language') }}<sup>*</sup></label> 
                                {!! Form::select('language_id', $audioLanguage, (!empty($albumData) ? $albumData->language_id : ''), ['class' => 'form-control select2WithSearch albumAudioLanguageId require','placeholder' => __('adminWords.choose')]) !!}
                                <small class="text-danger">{{ $errors->first('language_id') }}</small>
                            </div> 
                        
                            <div class="form-group{{ $errors->has('song_list') ? ' has-error' : '' }}">
                                <label for="song_list">{{__('adminWords.select').' '.__('adminWords.song')}}<sup>*</sup></label> 
                                <select name="song_list[]" id="album_audio_list" class="form-control multipleSelectWithSearch require" data-placeholder="{{__('adminWords.choose')}}"  multiple="multiple">
                                    @foreach($song_list as $song)
                                        <option value="{{$song->id}}" @if(isset($albumData) && !empty($albumData)) @foreach(json_decode($albumData->song_list) as $sid) {{ $sid == $song->id ? "selected" : "" }} @endforeach @endif >{{ $song->audio_title }}</option> 
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                {!! Form::label('description', __('description')) !!}
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.description')]) !!}
                                <small class="text-danger">{{ $errors->first('adminWords.description') }}</small>
                            </div>  
                            
                            <div class="form-group{{ $errors->has('copyright') ? ' has-error' : '' }}">
                                {!! Form::label('copyright', __('adminWords.copyright')) !!}
                                {!! Form::text('copyright', null, ['class' => 'form-control', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.copyright')]) !!}
                                <small class="text-danger">{{ $errors->first('copyright') }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-0 dd-flex">
                                <div class="checkbox mr-4">                                          
                                    {!! Form::checkbox('status', 1, (!empty($albumData) &&   $albumData->status == 0 ? 0 : 1),['id'=>'status']) !!}                                           
                                    {!! Form::label('status', __('adminWords.status')) !!}
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_featured', 1, (!empty($albumData) &&   $albumData->is_featured == 0 ? 0 : 1),['id'=>'is_featured']) !!}
                                    {!! Form::label('is_featured', __('adminWords.featured')) !!}
                                    <small class="text-danger">{{ $errors->first('is_featured') }}</small>
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_trending', 1, (!empty($albumData) &&   $albumData->is_trending == 0 ? 0 : 1),['id'=>'is_trending']) !!}
                                    {!! Form::label('is_trending', __('adminWords.trending')) !!}
                                    <small class="text-danger">{{ $errors->first('is_trending') }}</small>
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_recommended', 1, (!empty($albumData) &&   $albumData->is_recommended == 0 ? 0 : 1),['id'=>'is_recommended']) !!}
                                    {!! Form::label('is_recommended', __('adminWords.recommended') ) !!}
                                    <small class="text-danger">{{ $errors->first('is_recommended') }}</small>
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_verified', 1, (!empty($albumData) &&   $albumData->is_verified == 0 ? 0 : 1),['id'=>'is_verified']) !!}
                                    {!! Form::label('is_verified', __('adminWords.verified')) !!}
                                    <small class="text-danger">{{ $errors->first('is_verified') }}</small>
                                </div>
                   
                            </div>
                           

                            <div class="img-upload-preview">
                                <div class="row">
                                    <div class="col-md-6">  
                                        <div class="form-group{{$errors->has('image') ? 'has-error' : ''}}">
                                            <label for="image" class="col-lg-12">{{ __('adminWords.album').' '.__('adminWords.image') }}<sup>*</sup></label> 
                                            <label for="image" class="file-upload-wrapper js-labelFile" data-text="Select your file!" data-toggle="tooltip" data-original-title="Album Image">                                            
                                            {!! Form::file('image',['class' => 'form-control hide basicImage', 'data-label'=>'albumImage', 'name'=>'image', 'data-ext'=>"['jpg','jpeg','png']", 'data-image-id'=>'album_image', 'id'=>'image']) !!}
                                            <span class="js-fileName"></span>
                                            </label>
                                            <input type="hidden" id="image_name" value="{{(!empty($albumData) ? $albumData->image:'')}}">
                                            <span class="image_title" id="albumImage">{{(!empty($albumData) && $albumData->image != '' ? $albumData->image : '' )}}</span>
                                            <small class="text-danger">{{ $errors->first('image')}}</small>
                                            <input type="hidden" id="album_image" />
                                            <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 500X500 px' }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="image-block site_image_dv">
                                            @if(isset($albumData->image) && $albumData->image != null) 
                                                <img src="{{asset('public/images/album/'.$albumData->image)}}" class="img-responsive" alt="" height="100px" width="100px">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group dd-flex">
                                <label for="is_album_movie">{{  __('adminWords.album').'/'.__('adminWords.movie') }}<sup>*</sup></label> 
                                <div class="radio radio-primary mr-4 ml-4">
                                    {!! Form::radio('is_album_movie', 1, (!empty($albumData) && $albumData->is_album_movie == 1 || empty($albumData) ? 'checked' : ''), ['id'=>'album']) !!}
                                    {!! Form::label('album', null) !!}
                                </div>
                                <div class="radio radio-primary mr-4">
                                  {!! Form::radio('is_album_movie', 0, (!empty($albumData) && $albumData->is_album_movie == 0 ? 'checked' : (!empty($albumData) ? 'checked' : '')), ['id'=>'movie']) !!}
                                    {!! Form::label('movie', null) !!}
                                </div>                          
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group"> 
                                @if(empty($albumData))
                                    <button type="reset" class="effect-btn btn btn-danger"> {{__('adminWords.reset')}}</button>
                                @endif  
                                <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{!empty($albumData) ? __('adminWords.update') : __('adminWords.add') }}</button>  
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
