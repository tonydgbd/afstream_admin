@extends('layouts.admin.main')
@section('title', __('adminWords.state'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')            

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.all').' '.__('adminWords.state') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.state') }}</li>
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
							<h5 class="card-title mb-0">{{ __('adminWords.all').' '.__('adminWords.state') }}</h5>
						</div>
						<div class="col-6">
	                        <div class="btn-right">
	                            <a class="effect-btn btn btn-primary mr-2 statePopupToggle" data-add="1" data-url="{{route('saveState','create')}}"><i class="feather icon-plus mr-2"></i>{{ __('adminWords.create').' '.__('adminWords.state') }}</a> 
	                        </div>
	                    </div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<form method="post" id="stateForm">
							<table data-method="post" id="datatable-buttons" class="table table-styled musiooDtToShowData" data-url="{{route('stateData')}}">
								<thead>
									<tr> 
										<th>{{ __('adminWords.id') }}</th>
										<th>{{ __('adminWords.state').' '.__('adminWords.name') }}</th>
										<th>{{ __('adminWords.country').' '.__('adminWords.name') }}</th>
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

<div class="modal fade" id="addEditState">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('adminWords.add').' '.__('adminWords.state') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="addstate" method="post" onsubmit="return false" action="{{route('saveState', 'create')}}" data-modal="1" table-reload="musiooDtToShowData">
        <div class="modal-body">
			<div class="form-group">
				<label for="country">{{ __('adminWords.state').' '.__('adminWords.name') }}<sup>*</sup></label>
				<input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.state').' '.__('adminWords.name') }}" name="state" class="form-control require" />
			</div>
			<div class="form-group">
				<label for="country">{{ __('adminWords.select').' '.__('adminWords.country') }}<sup>*</sup></label>
				{!! Form::select('country_id', isset($country) ? $country : [], '', ['class'=>'form-control select2WithSearch country_id require', 'required', 'placeholder'=>__('adminWords.select').' '.__('adminWords.country') ]) !!}
				<small class="text-danger">{{ $errors->first('role') }}</small>
			</div>  
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="effect-btn btn btn-danger" data-dismiss="modal">{{ __('adminWords.close') }}</button>
          <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm">{{ __('adminWords.save') }}</button>
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
	<script src="{{ asset('public/assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  
@endsection
