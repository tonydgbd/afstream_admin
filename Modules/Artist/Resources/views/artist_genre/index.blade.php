@extends('layouts.admin.main')
@section('title', __('adminWords.artist_genres'))
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
                        <h4 class="page-title bold">{{ __('adminWords.artist_genres') }}</h4>
                    </div>
                    <div class="breadcrumb-list">
                        <ul>
                            <li class="breadcrumb-link">
                                <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                            </li>
                            <li class="breadcrumb-link active">{{ __('adminWords.artist_genres') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
 
<div class="card">           
		<div class="card-header">                                
			<div class="row align-items-center">
				<div class="col-6">
					<h5 class="card-title mb-0">{{ __('adminWords.artist_genres') }}</h5>
				</div>
				<div class="col-6">
					<div class="btn-right">
						<a class="effect-btn btn btn-primary mr-2 artistGenrePopupToggle" data-url="{{route('addEditArtistGenre', 'create')}}" data-method="post" data-add="1"><i class="feather icon-plus mr-2"></i>{{ __('adminWords.create').' '.__('adminWords.artist_genres') }}</a>
						<button type="button" class="effect-btn btn btn-danger" id="bulkDelete" data-msg="{{ __('adminWords.atleast').' '.__('adminWords.artist').' '.__('adminWords.must_selected') }}" data-url="{{route('artist.genre.bulk_delete')}}"><i class="far fa-trash-alt mr-2 "></i> 
							{{ __('adminWords.delete_selected') }}
						</button>  
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<form method="post" id="artistGenreForm">
					<table data-method="post" id="datatable-buttons" class="table table-styled musiooDtToShowData" data-url="{{route('artistGenreData')}}">
						<thead>
							<tr> 
								<th class="select-checkbox"> 
									<div class="checkbox danger-check">
										<input id="checkboxAll" type="checkbox" class="selectAllUser" onchange="checkAll(this, 'CheckBoxes')">
										<label for="checkboxAll" class="custom-control-label">{{ __('adminWords.all') }}</label>
									</div> 
								</th>
								<th>{{ __('adminWords.genre_name') }}</th>
								<th>{{ __('adminWords.created_at') }}</th>
								<th>{{ __('adminWords.action') }}</th>
							</tr>
						</thead>
					</table>
				</form>
			</div>
		</div>
			</div>

	<div class="modal fade" id="addEditArtistGenre">
	  <div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h4 class="modal-title">{{ __('adminWords.add').' '.__('adminWords.artist_genres') }}</h4>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <form id="addArtistGenre" method="post" onsubmit="return false" action="{{route('addEditArtistGenre', 'create')}}" data-modal="1" table-reload="musiooDtToShowData">
	        <div class="modal-body">
	          <div class="form-group">
	            <label for="country">{{ __('adminWords.artist').' '.__('adminWords.genre_name')}}<sup>*</sup></label>
	            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.artist').' '.__('adminWords.genre_name')}}" name="genre_name" class="form-control require"/>
	          </div>
	        </div>
	        <div class="modal-footer justify-content-between">
	          <button type="button" class="effect-btn btn btn-danger" data-dismiss="modal">{{ __('adminWords.close') }}</button> 
	          <button type="button" class="effect-btn btn btn-primary" id="genreBtn" data-action="submitThisForm">{{ __('adminWords.add') }}</button>
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
    <script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  
@endsection
