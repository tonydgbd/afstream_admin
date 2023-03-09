@extends('layouts.artist.main')
@section('title', __('adminWords.payment_history'))
@section('style')
	<link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')      

@php 
    if(!empty($defaultCurrency->symbol)){
        $curr = $defaultCurrency->symbol; 
    }else{
        $curr = session()->get('currency')['symbol'];
    }
@endphp

<!-- Page Title Start -->
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper">
                <div class="page-title-box">
                    <h4 class="page-title bold">{{ __('adminWords.all').' '.__('adminWords.payment_history') }}</h4>
                </div>
                <div class="musioo-brdcrmb breadcrumb-list">
                    <ul>
                        <li class="breadcrumb-link">
                            <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                        </li>
                        <li class="breadcrumb-link active">{{ __('adminWords.payment_history') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
   
<div class="contentbar">  
	<div class="row">
		<div class="col-lg-12"> 
		    
		    <div class="pay_histry_wrapper">
            	<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            	  <li class="nav-item">
            		<a class="nav-link active" id="pills-request-tab" data-toggle="pill" href="#pills-request" role="tab" aria-controls="pills-request" aria-selected="false">
            			{{ __('adminWords.new_request') }}
            		</a>
            	  </li>
            	  <li class="nav-item">
            		<a class="nav-link " id="pills-pay_histroy-tab" data-toggle="pill" href="#pills-pay_histroy" role="tab" aria-controls="pills-pay_histroy" aria-selected="true">
            			{{ __('adminWords.payment_history') }}
            		</a>
            	  </li>
            	</ul>
            	<div class="tab-content" id="pills-tabContent">
            	    <div class="tab-pane fade active show" id="pills-request" role="tabpanel">
            			<div class="card">
            				<div class="card-body">
            				     <div class="row align-items-center">
												<div class="table-responsive pl-3 pr-3 pt-0">
													<table id="request_payment" class="table table table-styled mb-0 dt-responsive nowrap">
														<thead>
															<tr>
																<th>#</th>
																<th>{{ __('adminWords.request_amount') }}</th>
																<th>{{ __('adminWords.admin').' '.__('adminWords.status') }}</th>
																<th>{{ __('adminWords.bank_status') }}</th>
																<th>{{ __('adminWords.request_date') }}</th>
															</tr>
														</thead>
														<tbody>
															@php 
																$i = 1; 
																$requestStatus = 1;
																$bankStatus = '';
																$adminStatus = '';
															@endphp
															@forelse($payment_requests as $payment)
																
																<tr>
																	<td>
																		<p class="semi-bold mb-0">{{ $i++ }}</p>
																	</td>                                                               
																	<td>
																		<p class="semi-bold mb-0 ">                                                        
																			{{ $curr.$payment['request_amount'] }}
																		</p>
																	</td>
																	@php
																		if($payment['admin_status'] == 0){
																			$requestStatus = 0;
																			$adminStatus = 'Requested';
																		}elseif($payment['admin_status'] == 1){
																			$adminStatus = 'Transferred';
																			if($payment['bank_status'] == 0){
																				$bankStatus = 'Fail';                                                                        
																			}elseif($payment['bank_status'] == 1){
																				$bankStatus = 'Success';
																			}else{
																				$bankStatus = 'Pending';
																			}    
																		}else{
																			$adminStatus = 'Rejected';
																		}    
																	@endphp
																	<td>
																		<p class="semi-bold mb-0 ">                                                        
																			{{ $adminStatus }}
																		</p>
																	</td>
																	<td>
																		<p class="semi-bold mb-0">{{ $bankStatus }}</p>
																	</td>
																	<td>
																		<p>{{ date('d-m-Y', strtotime($payment['created_at'])) }}</p>
																	</td>  
																	
																</tr>
															@empty
																<tr>
																	<td colspan="5">
																		{{ __('adminWords.no_data') }}
																	</td>
																</tr>
															@endforelse
														</tbody>
													</table>
												</div>                                       
												
											</div>
            				</div>
            			</div>
            	    </div>
            	    <div class="tab-pane fade" id="pills-pay_histroy" role="tabpanel">
            		    <div class="card m-b-30">
            				<div class="card-header">      
            				    <h4 class="has-btn">{{ __('adminWords.all').' '.__('adminWords.payment_history') }} <span>	
            				        <a href="{{ route('artist.request_payment') }}" class="effect-btn btn btn-primary float-right">   
                                        {{ __('adminWords.withdrawal_now') }}
                                    </a>
                                </span></h4>
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
                                                <button type="button" class="effect-btn btn btn-primary multiple_dt_filter" data-tabel="artistPaymentHistoryDataTables" data-url="{{route('artist.payment_history_data')}}">Filter</button>
                                            </div>
                                        </div>
                                    </div>                
                                </div>  
                                <div class="table-responsive">
                                    <form method="post" id="artistPaymentHistoryForm">
                                        <table data-method="post" id="datatable-buttons" class="table table-styled musiooDtToShowData artistPaymentHistoryDataTables" data-url="{{route('artist.payment_history_data')}}">
                                            <thead>
                                                <tr> 
                                                    <th>#</th>
                                                    <th>{{ __('adminWords.txn_id') }}</th> 
                                                    <th>{{ __('adminWords.payment_method') }}</th>
                                                    <th>{{ __('adminWords.total_price') }}</th>
                                                    <th>{{ __('adminWords.transaction_date') }}</th>
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
