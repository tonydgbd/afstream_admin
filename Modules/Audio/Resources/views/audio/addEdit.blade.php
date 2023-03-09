
@extends('layouts.admin.main')
@section('title', __('adminWords.audio'))
@section('style')
<link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{asset('public/assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@inject('userModel', 'App\User')

@section('content')               


<!-- Page Title Start -->
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper">
                <div class="page-title-box">
                    <h4 class="page-title bold">
                        {{ isset($audioData) ? __('adminWords.update').' '.__('adminWords.audio') : __('adminWords.create').' '.__('adminWords.audio') }}
                    </h4>
                </div>
                <div class="musioo-brdcrmb breadcrumb-list">
                    <ul>
                        <li class="breadcrumb-link">
                            <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                        </li>
                        <li class="breadcrumb-link active">{{ __('adminWords.audio') }}</li>
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
                        <a class="effect-btn btn btn-primary" href="{{ url('audio') }}">{{ __('adminWords.go_back') }}</a>
                    </div>                             
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title mb-0">{{ isset($audioData) ? __('adminWords.update').' '.__('adminWords.audio') : __('adminWords.create').' '.__('adminWords.audio') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <div class="admin-form">
                    @if(isset($audioData))
                      {!! Form::model($audioData, ['method'=>'post', 'files'=>true, 'route'=>['addEditAudio', $audioData->id], 'id'=>'updateAudio', 'onsubmit'=>'return false', 'data-redirect' => url('/audio')]) !!}
                    @else
                      {!! Form::open(['method' => 'POST', 'route'=>['addEditAudio','add'], 'data-reset'=>1, 'files' => true, 'onsubmit'=>'return false', 'data-redirect' => url('/audio')]) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-6"> 
                            <div class="form-group{{$errors->has('audio_title') ? 'has-error' : ''}}">
                              <label for="audio_title">{{  __('adminWords.audio').' '.__('adminWords.title') }}<sup>*</sup></label>
                              {!! Form::text('audio_title', null, ['class' => 'form-control require', 'required', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.audio').' '.__('adminWords.title') ]) !!}
                              <small class="text-danger">{{ $errors->first('audio_title')}}</small>
                            </div>
                            <div class="form-group{{ $errors->has('audio_genre_id') ? ' has-error' : '' }}">
                                <label for="audio_genre_id">{{ __('adminWords.select').' '.__('adminWords.audio_genre') }}<sup>*</sup></label>
                                {!! Form::select('audio_genre_id', $audioGenre, (isset($audioData) ? $audioData->audio_genre_id : ''), ['class' => 'form-control select2WithSearch require','placeholder' => __('adminWords.choose') ]) !!}
                                <small class="text-danger">{{ $errors->first('audio_genre_id') }}</small>
                            </div> 
                            <div class="form-group{{ $errors->has('audio_language') ? ' has-error' : '' }}">
                                <label for="audio_language">{{ __('adminWords.select').' '.__('adminWords.language') }}<sup>*</sup></label>
                                {!! Form::select('audio_language', $audioLanguage, (isset($audioData) ? $audioData->audio_language : ''), ['class' => 'form-control select2WithSearch require artistAudioLanguageId','placeholder' => __('adminWords.choose')]) !!}
                                <small class="text-danger">{{ $errors->first('audio_language') }}</small>
                            </div> 
                            <div class="form-group{{ $errors->has('artist_id') ? ' has-error' : '' }}">
                                <label for="artist_id">{{ __('adminWords.select').' '.__('adminWords.artist') }}<sup>*</sup></label>
                                <select name="artist_id[]" class="form-control multipleSelectWithSearch require" data-placeholder="{{__('adminWords.choose')}}"  multiple="multiple" id="audio_artist_list">
                                    @foreach($artist as $key=>$artists) 
                                        <option value="{{$key}}" @if(isset($audioData)) @foreach(json_decode($audioData->artist_id) as $aid) {{ $aid == $key ? "selected" : "" }} @endforeach @endif >{{ $artists }}</option>
                                    @endforeach
                                </select>

                              <small class="text-danger">{{ $errors->first('artist_id') }}</small>
                          </div> 
                          <div class="form-group{{ $errors->has('copyright') ? ' has-error' : '' }}">
                              {!! Form::label('copyright', __('adminWords.copyright') ) !!}
                              {!! Form::text('copyright', null, ['class' => 'form-control', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.copyright'), 'rows'=>'3']) !!}
                              <small class="text-danger">{{ $errors->first('copyright') }}</small>
                          </div>

                            <div class="form-group{{ $errors->has('release_date') ? ' has-error' : '' }}">
                              {!! Form::label('release_date', 'Release Date' ) !!}
                              {!! Form::text('release_date', null, ['class' => 'form-control date-calender', 'placeholder'=>__('adminWords.enter').' Release Date', 'rows'=>'3']) !!}
                              <small class="text-danger">{{ $errors->first('release_date') }}</small>
                            </div> 

                            <div class="img-upload-preview">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group{{$errors->has('image') ? 'has-error' : ''}}">
                                            {!! Form::label('image', __('adminWords.audio').' '.__('adminWords.image'), ['class'=>'col-lg-12']) !!}
                                            <label for="image" class="file-upload-wrapper js-labelFile" data-text="Select your file!" data-toggle="tooltip" data-original-title="Audio Image">
                                              <i class="icon fa fa-check"></i>
                                              {!! Form::file('image',['class' => 'basicImage form-control hide', 'name'=>'image', 'data-ext'=>"['jpg','jpeg','png']", 'data-image-id'=>'audioImage', 'data-label'=>'audio_imagee', 'data-image' => __('adminWords.image_error')]) !!}
                                              <span class="js-fileName"></span>
                                            </label>
                                              <input type="hidden" id="image_name" value="{{(isset($audioData) ? $audioData->image:'')}}">
                                              <span class="image_title" id="audio_imagee">{{(isset($audioData) && $audioData->image != '' ? $audioData->image : '' )}}</span>
                                              <small class="text-danger">{{ $errors->first('image')}}</small>
                                              <input type="hidden" id="audioImage" />
                                              <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 500X500 px' }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="image-block site_image_dv">
                                            @if(isset($audioData->image) && $audioData->image != null) 
                                                <img src="{{asset('public/images/audio/thumb/'.$audioData->image)}}" class="img-responsive" alt="" height="200px" width="200px">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>                    
                            
                            <div class="img-upload-preview">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group{{$errors->has('audio') ? 'has-error' : ''}}">
                                            {!! Form::label('audio', __('adminWords.audio'), ['class'=>'col-lg-6']) !!}
                                            <label for="audio" class="js-labelFile file-upload-wrapper" data-text="Select your file!" -toggle="tooltip" data-original-title="Audio">
                                              <i class="icon fa fa-check"></i>
                                              {!! Form::file('audio',['class' => 'basicImage form-control hide', 'name'=>'audio', 'data-ext'=>"['mp3','wav']", 'data-audio-id'=>'audio', 'data-label'=>'audio_url']) !!}
                                              <span class="js-fileName"></span>
                                            </label>
                                              <input type="hidden" id="audio_name" value="{{(isset($audioData) ? $audioData->audio:'')}}">
                                              <span class="image_title" id="audio_url">{{(isset($audioData) && $audioData->audio != '' ? $audioData->audio : '' )}}</span>
                                            <small class="text-danger">{{ $errors->first('audio')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                      </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-0 dd-flex">
                                <div class="checkbox mr-4">                                          
                                    {!! Form::checkbox('status', 1, (isset($audioData) &&   $audioData->status == 0 ? 0 : 1),['id'=>'status']) !!}                                       
                                    {!! Form::label('status', __('adminWords.status')) !!}    
                                    <small class="text-danger">{{ $errors->first('status') }}</small>                      
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_featured', 1, (isset($audioData) &&   $audioData->is_featured == 0 ? 0 : 1),['id'=>'is_featured']) !!}
                                    {!! Form::label('is_featured', __('adminWords.featured') ) !!}           
                                    <small class="text-danger">{{ $errors->first('is_featured') }}</small>                 
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_trending', 1, (isset($audioData) && $audioData->is_trending == 0 ? 0 : 1),['id'=>'is_trending']) !!}
                                    {!! Form::label('is_trending', __('adminWords.trending') ) !!}   
                                    <small class="text-danger">{{ $errors->first('is_trending') }}</small>                         
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_recommended', 1, (isset($audioData) &&   $audioData->is_recommended == 0 ? 0 : 1),['id'=>'is_recommended']) !!}
                                    {!! Form::label('is_recommended', __('adminWords.recommended') ) !!}   
                                    <small class="text-danger">{{ $errors->first('is_recommended') }}</small>                        
                                </div>           
                            </div>
                            
                            <div class="form-group{{ $errors->has('lyrics') ? ' has-error' : '' }}">  
                                {!! Form::label('lyrics', __('adminWords.lyrics') ) !!}
                                {!! Form::textarea('lyrics', null, ['id' => 'summernote', 'class' => 'form-control', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.lyrics'), 'rows'=>'3']) !!}
                                <small class="text-danger">{{ $errors->first('adminWords.lyrics') }}</small>
                            </div>
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                {!! Form::label('description', __('adminWords.description')) !!}
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.description'), 'rows'=>'3']) !!}
                                <small class="text-danger">{{ $errors->first('description') }}</small>
                            </div> 
                            
                            @if(isset($audioData) && !empty($audioData) && !empty($audioData->user_id))
                                @php
                                    $userData = $userModel->select('role')->where('id',$audioData->user_id)->first();
                                @endphp
                                @if(isset($userData) && !empty($userData) && $userData->role == 1)
                                    <div class="form-group{{ $errors->has('download_price') ? ' has-error' : ''}}">
                                        <label for="download_price">
                                            {{  __('adminWords.download').' '.__('adminWords.price') }}
                                        </label>
                                        {!! Form::number('download_price', null, ['class' => 'form-control', 'id' => 'download_price' ,'placeholder'=>__('adminWords.enter').' '.__('adminWords.download').' '.__('adminWords.price'), 'rows'=>'3']) !!}
                                        <small class="text-danger">{{ $errors->first('download_price') }}</small>
                                    </div>
                                @endif
                            @else
                                <div class="form-group{{ $errors->has('download_price') ? ' has-error' : ''}}">
                                    <label for="download_price">
                                        {{  __('adminWords.download').' '.__('adminWords.price') }}
                                    </label>
                                    {!! Form::number('download_price', null, ['class' => 'form-control', 'id' => 'download_price' ,'placeholder'=>__('adminWords.enter').' '.__('adminWords.download').' '.__('adminWords.price'), 'rows'=>'3']) !!}
                                    <small class="text-danger">{{ $errors->first('download_price') }}</small>
                                </div>
                            @endif
                            
                            
                          
                            @if(isset($is_s3) && !empty($is_s3) && $is_s3 == 1)
                                <div class="form-group mb-0 dd-flex">
                                    <div class="checkbox mr-4">  
                                        {!! Form::checkbox('aws_upload', 1, (isset($audioData) && $audioData->aws_upload == 0 ? 0 : 1),['id'=>'aws_upload']) !!}
                                        {!! Form::label('aws_upload', __('adminWords.aws_upload')) !!}
                                    </div>
                                        <small class="text-danger">{{ $errors->first('aws_upload') }}</small>
                                </div>                            
                            @endif

                        </div>

                      <div class="col-lg-8">
                        <div class="form-group"> 
                          @if(!isset($audioData))
                            <button type="reset" class="effect-btn btn btn-danger"> {{ __('adminWords.reset') }}</button>
                          @endif  
                          <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{isset($audioData) ? __('adminWords.update') : __('adminWords.add') }}</button>  
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
<script src="{{asset('public/assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{ asset('public/assets/plugins/datepicker/i18n/datepicker.en.js') }}"></script> 
<script src="{{ asset('public/assets/plugins/select2/select2.min.js') }}"></script> 
<script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  
<script type="text/javascript">
    if ($('.date-calender').length > 0) {
        $('.date-calender').datepicker({
            format: 'dd-mm-yyyy',
            multidate: false,
            todayHighlight: true,
            language: 'en'
        });
    }
</script>

@endsection
