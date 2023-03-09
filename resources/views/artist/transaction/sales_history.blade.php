@extends('layouts.artist.main')
@section('title', __('adminWords.sales_history'))
@section('style')
	<link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')      

<!-- Page Title Start -->
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper sales_histroy_title">
                <div class="page-title-box">
                    <h4 class="page-title bold">{{ __('adminWords.all').' '.__('adminWords.sales_history') }}</h4>
                </div>
                <div class="musioo-brdcrmb breadcrumb-list">
                    <ul>
                        <li class="breadcrumb-link">
                            <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                        </li>
                        <li class="breadcrumb-link active">{{ __('adminWords.sales_history') }}</li>
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
							<h5 class="card-title mb-0">{{ __('adminWords.all').' '.__('adminWords.sales_history') }}</h5>
						</div>
						<div class="col-6">
    						<a href="{{ route('artist.request_payment') }}" class="effect-btn btn btn-primary float-right">   
                                {{ __('adminWords.withdrawal_now') }}
                            </a>
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
                                    <button type="button" class="effect-btn btn btn-primary multiple_dt_filter" data-tabel="artistSalesHistoryDataTables" data-url="{{route('artist.sales_history_data')}}">Filter</button>
                                </div>
                            </div>
                            
                        </div>                
                    </div>  
                    <div class="table-responsive"> 
                        <form method="post" id="artistSalesHistoryForm">
                            <table data-method="post" id="datatable-buttons" class="table table-styled musiooDtToShowData artistSalesHistoryDataTables" data-url="{{route('artist.sales_history_data')}}">
                                <thead>
                                    <tr> 
                                        <th>#</th>
                                        <th>{{ __('adminWords.txn_id') }}</th>
                                        <th>{{ __('adminWords.customer_name') }}</th>
                                        <th>{{ __('adminWords.audio_name') }}</th>
                                        <th>{{ __('adminWords.audio').' '.__('adminWords.price') }}</th>
                                        <th>{{ __('adminWords.payment_method') }}</th>
                                        <th>{{ __('adminWords.admin').' '.__('adminWords.commission') }}</th>
                                        <th>{{ __('frontWords.tax_rate') }}</th>
                                        <th>{{ __('adminWords.total_tax') }}</th>
                                        <th>{{ __('adminWords.earning') }}</th>
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
    <script src="{{ asset('public/assets/plugins/select2/select2.min.js') }}"></script>    
    <script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  
@endsection
