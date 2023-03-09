@extends('layouts.admin.main')
@section('title', __('adminWords.subscription'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')      

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.all').__('adminWords.subscription') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.subscription') }}</li>
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
							<h5 class="card-title mb-0">{{ __('adminWords.all').' '.__('adminWords.subscription') }}</h5>
						</div>
					</div>
				</div>
				<div class="card-body">
				    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">                            
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"> 
                            <div class="data_tablefilter_option">
                                <div id="reportrange" class="reportrangefilter">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>                              
                                <div class="data_tablefilter_btn">
                                    <button type="button" class="effect-btn btn btn-primary multiple_dt_filter" data-tabel="subscriptionDataTables" data-url="{{route('subscriptionData')}}">Filter</button>
                                </div>
                            </div>
                        </div>                
                    </div>  
					<div class="table-responsive">
						<form method="post" id="subscriptionForm">
							<table data-method="post" id="datatable-buttons" class="table table-styled musiooDtToShowData subscriptionDataTables" data-url="{{route('subscriptionData')}}">
								<thead>
									<tr> 
                                        <th>#</th>
                                        <th>{{ __('adminWords.txn_id') }}</th>
                                        <th>{{ __('adminWords.customer_name') }}</th>
                                        <th>{{ __('adminWords.quantity') }}</th>
                                        <th>{{ __('adminWords.payment_method') }}</th>
                                        <th>{{ __('adminWords.total_price') }}</th>
                                        <th>{{ __('adminWords.txn_date') }}</th>
                                        <th>{{ __('adminWords.status') }}</th>
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
