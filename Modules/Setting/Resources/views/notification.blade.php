@extends('layouts.admin.main')
@section('title', __('adminWords.notification'))
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
                <h4 class="page-title bold">{{ __('adminWords.all').' '.__('adminWords.notification') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.notification') }}</li>
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
							<h5 class="card-title mb-0">{{ __('adminWords.all').' '.__('adminWords.notification') }}</h5>
						</div>
						<div class="col-6">
                            <div class="btn-right">
                                <a class="effect-btn btn btn-primary mr-2 notificationPopupToggle" data-add="1" data-url="{{route('notification.add')}}"><i class="feather icon-plus mr-2"></i>{{ __('adminWords.create').' '.__('adminWords.notification') }}</a>
								<button type="button" class="effect-btn btn btn-danger" id="bulkDelete" data-type="notification" data-msg="{{ __('adminWords.atleast').' '.__('adminWords.notification').' '.__('adminWords.must_selected') }}" data-url="{{route('bulkDeleteNotification')}}"><i class="far fa-trash-alt mr-2 "></i> {{ __('adminWords.delete_selected') }}</button>
                            </div>
                        </div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<form method="post" id="notificationForm">
							<table data-method="post" id="datatable-buttons" class="table table-styled musiooDtToShowData" data-url="{{route('notificationData')}}">
								<thead>
									<tr> 
										<th class="select-checkbox"> 
											<div class="checkbox danger-check">
												<input id="checkboxAll" type="checkbox" class="selectAllUser" onchange="checkAll(this, 'CheckBoxes')">
												<label for="checkboxAll">{{ __('adminWords.select').' '.__('adminWords.all') }}</label>
											</div>
										</th>
										<th>{{ __('adminWords.user_name') }}</th>
										<th>{{ __('adminWords.message') }}</th>
										<th>{{ __('adminWords.created_at') }}</th>
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

<div class="modal fade" id="addNotification">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">{{ __('adminWords.add').' '.__('adminWords.notification') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
     
        {!! Form::open(['method' => 'POST', 'route' =>['notification.add'], 'data-reset'=>'1', 'data-modal'=>1, 'table-reload'=>'musiooDtToShowData' ]) !!}
            <div class="modal-body">
                
                <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
					<label for="user_id">{{  __('adminWords.select').' '.__('adminWords.users') }}<sup>*</sup></label>
                    {!! Form::select('user_id[]', $userData, '', ['class' => 'form-control multipleSelectWithSearch selectUsers require', 'multiple', 'data-placeholder' => __('adminWords.choose')]) !!}
                    <small class="text-danger">{{ $errors->first('user_id') }}</small>
                </div> 
                        
                <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
					<label for="message">{{ __('adminWords.notification').' '.__('adminWords.message') }}<sup>*</sup></label>
                    {!! Form::textarea('message', '', ['class' => 'form-control require', 'rows'=>3]) !!}
                    <small class="text-danger">{{ $errors->first('message') }}</small>
                </div> 
                <div class="modal-footer justify-content-between">
                    <button type="button" class="effect-btn btn btn-light" data-dismiss="modal"> {{ __('adminWords.close') }}</button>
                    <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm">{{ __('adminWords.save') }}</button>
                </div>
            </div>
        {!! Form::close() !!}
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
