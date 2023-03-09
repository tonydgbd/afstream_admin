@extends('layouts.admin.main')
@section('title', __('adminWords.audio_genres'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />    
@endsection
@section('content')
           

<!-- Page Title Start -->
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper">
                <div class="page-title-box">
                    <h4 class="page-title bold">{{ __('adminWords.audio_genres') }}</h4>
                </div>
                <div class="musioo-brdcrmb breadcrumb-list">
                    <ul>
                        <li class="breadcrumb-link">
                            <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                        </li>
                        <li class="breadcrumb-link active">{{ __('adminWords.audio_genres') }}</li>
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
					<div class="row align-items-center">
						<div class="col-6">
							<h5 class="card-title mb-0">{{ __('adminWords.audio_genres') }}</h5>
						</div>
                        <div class="col-6">
                            <div class="btn-right">
                                <a class="effect-btn btn btn-primary mr-2 audioGenrePopupToggle" data-add="1" data-url="{{route('genres','create')}}"><i class="feather icon-plus mr-2"></i>{{ __('adminWords.create').' '.__('adminWords.audio_genres') }}</a>
                                <button type="button" class="effect-btn btn btn-danger" id="bulkDelete" data-msg="{{ __('adminWords.atleast').' '.__('adminWords.audio').' '.__('adminWords.must_selected') }} " data-url="{{route('bulkDeleteAudioGenre')}}"><i class="far fa-trash-alt mr-2 "></i> {{ __('adminWords.delete_selected') }}</button>  
                            </div>
                        </div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<form method="post" id="audioGenreForm"> 
							<table data-method="post" id="datatable-buttons" class="table table-styled musiooDtToShowData" data-url="{{route('showAudioGenreData')}}" >
								<thead>
									<tr> 
										<th class="select-checkbox"> 
											<div class="checkbox danger-check">
												<input id="checkboxAll" type="checkbox" class="selectAllUser" onchange="checkAll(this, 'CheckBoxes')">
												<label for="checkboxAll" class="custom-control-label">{{ __('adminWords.all') }}</label>
											</div>
										</th>
										<th>{{ __('adminWords.image') }}</th>
										<th>{{ __('adminWords.genre_name') }}</th>
										<th>{{ __('adminWords.created_at') }}</th>
										<th>{{ __('adminWords.status') }}</th>
										<th>{{ __('adminWords.action') }}</th>
									</tr>
								</thead>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addUpdateAudioGenre">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
		<form method="post" id="addUpdateGenreForm" action="{{route('genres','create')}}" enctype="multipart/form-data" data-reset="1" data-modal='1' table-reload="musiooDtToShowData">
			{{csrf_field()}}
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="genreName">{{ __('adminWords.genre_name') }}<sup>*</sup></label>
                            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.genre_name') }}" id="genreName" name="genre_name" class="form-control require" />
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group {{$errors->has('image') ? 'has-error' : ''}}">
                            <label for="image" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">{{ __('adminWords.genre').' '.__('adminWords.image') }}<sup>*</sup></label>
                            <label for="image" class="js-labelFile file-upload-wrapper" data-text="Select your file!" data-toggle="tooltip" data-original-title="{{ __('adminWords.genre').' '.__('adminWords.image') }}">
                            {!! Form::file('image',['class' => 'form-control hide basicImage', 'data-label'=>'genreImage', 'id'=>'image', 'name'=>'image',  'data-ext'=>"['jpg','jpeg','png']", 'data-image-id'=>'genre_image', 'data-image'=>__('adminWords.image_error')]) !!}
                            
                            <span class="js-fileName"></span>
                            </label>
                            <input type="hidden" id="image_name" value=""></br>
                            <span class="image_title" id="genreImage"></span>
                            <small class="text-danger">{{ $errors->first('image') }}</small>
                            <input type="hidden" id="genre_image" />
                            <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size : 500X500 px' }} </p>
                        </div>
                    </div>

                    <div class="form-group mb-0 dd-flex">
                        <div class="checkbox mr-4">                                          
                            <input id="status_switch" name="status" type="checkbox" checked>                                         
                            {!! Form::label('status_switch', __('adminWords.status') ) !!}                            
                        </div>
                        <div class="checkbox mr-4">
                            <input id="fetaured_switch" name="is_featured" type="checkbox" checked>
                            {!! Form::label('fetaured_switch', __('adminWords.featured') ) !!}                            
                        </div>
                        <div class="checkbox mr-4">
                            <input id="trending_switch" name="is_trending" type="checkbox" checked>
                            {!! Form::label('trending_switch', __('adminWords.trending') ) !!}                            
                        </div>
                        <div class="checkbox mr-4">
                            <input id="recommended_switch" name="is_recommended" type="checkbox" checked>
                            {!! Form::label('recommended_switch', __('adminWords.recommended') ) !!}                           
                        </div>           
                    </div>   

                    
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="effect-btn btn btn-danger" data-dismiss="modal">{{ __('adminWords.close') }}</button>
                <button type="button" class="effect-btn btn btn-primary" id="audioGenreBtn" data-action="submitThisForm">{{ __('adminWords.add') }}</button>
            </div>
        </form>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script src="{{ asset('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
	<script src="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('public/assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>      
	<script src="{{ asset('public/assets/plugins/datepicker/datepicker.min.js') }}"></script> 
	<script src="{{ asset('public/assets/plugins/datepicker/i18n/datepicker.en.js') }}"></script> 
    <script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  
@endsection
