
@extends('layouts.admin.main')
@section('title', __('frontWords.playlist'))
@section('style')
<link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{asset('public/assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')           

<!-- Page Title Start -->
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper">
                <div class="page-title-box">
                    <h4 class="page-title bold">{{ isset($audioData) ? __('adminWords.update').' '.__('frontWords.playlist') : __('adminWords.create').' '.__('frontWords.playlist') }}</h4>
                </div>
                <div class="musioo-brdcrmb breadcrumb-list">
                    <ul>
                        <li class="breadcrumb-link">
                            <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                        </li>
                        <li class="breadcrumb-link active">{{ __('frontWords.playlist') }}</li>
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
                        <a class="effect-btn btn btn-primary" href="{{ url('admin/playlist') }}">{{ __('adminWords.go_back') }}</a>
                    </div>                             
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title mb-0">{{ isset($audioData) ? __('adminWords.update').' '.__('frontWords.playlist') : __('adminWords.create').' '.__('frontWords.playlist') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <div class="admin-form">
                    @if(isset($audioData))
                      {!! Form::model($audioData, ['method'=>'post', 'files'=>true, 'route'=>['addEditAdminPlaylist', $audioData->id], 'id'=>'updateAudio', 'onsubmit'=>'return false', 'data-redirect' => url('/admin/playlist')]) !!}
                    @else
                      {!! Form::open(['method' => 'POST', 'route'=>['addEditAdminPlaylist','add'], 'data-reset'=>1, 'files' => true, 'onsubmit'=>'return false', 'data-redirect' => url('/admin/playlist')]) !!} 
                    @endif
                    <div class="row">
                        <div class="col-lg-6"> 

                            <div class="form-group{{$errors->has('playlist_title') ? 'has-error' : ''}}">
                                <label for="playlist_title">{{  __('frontWords.playlist').' '.__('adminWords.title') }}<sup>*</sup></label>
                                {!! Form::text('playlist_title', null, ['class' => 'form-control require', 'required', 'placeholder'=> __('adminWords.enter').' '.__('frontWords.playlist').' '.__('adminWords.title') ]) !!}
                                <small class="text-danger">{{ $errors->first('playlist_title')}}</small>
                            </div>                           

                            <div class="form-group{{ $errors->has('audio_language') ? ' has-error' : '' }}">
                                <label for="audio_language">{{ __('adminWords.select').' '.__('adminWords.language') }}<sup>*</sup></label>
                                {!! Form::select('audio_language', $audioLanguage, (isset($audioData) ? $audioData->audio_language : ''), ['class' => 'form-control select2WithSearch getSelectedLanguage require','placeholder' => __('adminWords.choose') ]) !!}
                                <small class="text-danger">{{ $errors->first('audio_language') }}</small>
                            </div> 
                            

                            <div class="form-group{{ $errors->has('album_id') ? ' has-error' : '' }}">
                                <label for="album_id">{{ __('adminWords.select').' '.__('adminWords.album') }}</label>
                                <select name="album_id[]" class="form-control multipleSelectWithSearch" id="album_list_ids" data-placeholder="{{__('adminWords.choose')}}"  multiple="multiple">
                                    @foreach($album as $albums)  
                                        <option value="{{ $albums['id'] }}" data-language="{{ $albums['language_id'] }}" @if(!empty($audioData->album_id)) @foreach(json_decode($audioData->album_id) as $aid) {{ $aid == $albums['id'] ? "selected" : "" }} @endforeach @endif >{{ $albums['album_name'] }}</option>
                                    @endforeach
                                </select>

                              <small class="text-danger">{{ $errors->first('album_id') }}</small>
                            </div>

                            <div class="form-group{{ $errors->has('artist_id') ? ' has-error' : '' }}">
                                <label for="artist_id">{{ __('adminWords.select').' '.__('adminWords.artist') }}</label>
                                <select name="artist_id[]" id="artist_list_ids" class="form-control multipleSelectWithSearch" data-placeholder="{{__('adminWords.choose')}}"  multiple="multiple">
                                    @foreach($artist as $artists) 
                                        <option value="{{ $artists['id'] }}"  data-language="{{ $artists['audio_language_id'] }}" @if(!empty($audioData->artist_id)) @foreach(json_decode($audioData->artist_id) as $aid) {{ $aid == $artists['id'] ? "selected" : "" }} @endforeach @endif >{{ $artists['artist_name'] }}</option>
                                    @endforeach
                                </select>

                              <small class="text-danger">{{ $errors->first('artist_id') }}</small>
                            </div> 

                            <div class="form-group{{ $errors->has('audio_id') ? ' has-error' : '' }}">
                                <label for="audio_id">{{__('adminWords.select').' '.__('adminWords.song')}}</label> 
                                <select name="audio_id[]" id="audio_list_ids" class="form-control multipleSelectWithSearch" data-placeholder="{{__('adminWords.choose')}}"  multiple="multiple">
                                    @foreach($song_list as $song)
                                        <option value="{{$song->id}}" data-language="{{ $song['audio_language'] }}" @if(!empty($audioData->audio_id)) @foreach(json_decode($audioData->audio_id) as $sid) {{ $sid == $song->id ? "selected" : "" }} @endforeach @endif >{{ $song->audio_title }}</option> 
                                    @endforeach
                                </select>
                                   
                            </div>                            

                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} switch-main-block">
                                  
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('status', 1, (isset($audioData) &&   $audioData->status == 0 ? 0 : 1),['id'=>'status']) !!}
                                    {!! Form::label('status', __('adminWords.status')) !!}
                                    <small class="text-danger">{{ $errors->first('status') }}</small>
                                </div> 
                            </div>   
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
