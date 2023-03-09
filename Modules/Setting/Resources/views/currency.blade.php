@extends('layouts.admin.main')
@section('title', __('adminWords.currency'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.all').' '.__('adminWords.currency')}}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.currency') }}</li>
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
							<h5 class="card-title mb-0">{{ __('adminWords.all').' '.__('adminWords.currency') }}</h5>
						</div>
						<div class="col-6">
                            <div class="btn-right">
                                <a class="effect-btn btn btn-primary mr-2 currencyPopupToggle" data-add="1" data-url="{{route('currency.save')}}"><i class="feather icon-plus mr-2"></i>{{ __('adminWords.add').' '.__('adminWords.currency') }}</a>
								<a class="effect-btn btn btn-primary mr-2 updateRateSetting"><i class="fa fa-refresh"></i> {{ __('adminWords.update_rates') }}</a>
                            </div>
                        </div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<form method="post" id="currencyForm">
							<table data-method="post" id="datatable-buttons" class="table table-styled musiooDtToShowData" data-url="{{route('currencyData')}}">
								<thead>
									<tr> 
										<th>{{ __('adminwords.id') }}</th>
										<th>{{ __('adminWords.currency') }}</th>
										<th>{{ __('adminWords.rate') }}</th>
										<th>{{ __('adminWords.currency_symbol') }}</th>
										<th>{{ __('adminWords.default').' '.__('adminWords.currency') }}</th>
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


<div class="modal fade" id="addEditCurrency">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">{{ __('adminWords.add').' '.__('adminWords.currency') }}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="addCurrency" method="post" onsubmit="return false" action="{{route('currency.save')}}" data-modal="1" table-reload="musiooDtToShowData" data-reset="1">
				<div class="modal-body">
					<div class="form-group">
						<label for="currency_code">{{ __('adminWords.currency').' '.__('adminWords.code') }}<sup>*</sup></label>
						<input type="text" placeholder="{{ __('adminWords.eg').' USD' }}" name="currency_code" class="form-control require" />
							<small>Currency code must be a valid ISO-3 code. Find your currency ISO3 code <a target="_blank" href="https://www1.oanda.com/currency/help/currency-iso-code-country">here</a></small>
				</div>
				<div class="form-group">
					<label for="additional_charges">{{ __('adminWords.additional_fee') }}</label>
							<input type="number" placeholder="{{ __('adminWords.eg').' 0.50' }}" name="additional_fee" class="form-control" />
						</div>  
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="effect-btn btn btn-danger mt-2 mr-2" data-dismiss="modal">{{ __('adminWords.close') }}</button>
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
    <script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  
@endsection
