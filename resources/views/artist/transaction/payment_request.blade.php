@extends('layouts.artist.main')
@section('title', __('adminWords.request_payment'))
@section('style')
	<link href="{{ asset('public/assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/css/artist/custom-style.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')      

@php 
    $tax_amount = 0;
    if(!empty($defaultCurrency->symbol)){
        $curr = $defaultCurrency->symbol; 
    }else{
        $curr = session()->get('currency')['symbol'];
    }
    
    if(isset($default_payment_gateway->default_pay_gateway) && !empty($default_payment_gateway->default_pay_gateway)){
        $defaultGateway = $default_payment_gateway->default_pay_gateway;
    }else{
        $defaultGateway = '';
    }
@endphp
<!-- Page Title Start -->
<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.request_payment') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.request_payment') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
   
<div class="contentbar">  
	<div class="row">
		<div class="col-lg-12"> 
                 <!-- Paymetn Request  -->
				<div class="payment-req-wrapper">
					<div class="row flex-column-reverse">
					   <div class="col-xl-12 col-lg-12 col-12">
						   <div class="row">
							   <!-- All Payment Request Detail Start -->
								<div class="col-xl-12 col-lg-12 col-12" style="display:none;">
									<div class="card m-b-30">
										<div class="card-header">
											<div class="col-10 pl-4">
												<h4 class="card-title mb-0">{{ __('adminWords.all').' '.__('adminWords.request_payment') }}</h4>
											</div>
										</div>
										<div class="card-body ">
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
								<!-- All Payment Request Detail End -->
								
								<!-- Total Earning Start -->
								<div class="col-xl-12 col-lg-12 col-12">
									<div class="card m-b-30">
										<div class="card-header">
											
											
											<div class="row align-items-center">
                            					<div class="col-6">
                            						<h5 class="card-title mb-0">{{ __('adminWords.total_earnings') }}</h5>
                            					</div>
                            					<div class="col-6">
                            						<a href="javascript:void(0)" class="effect-btn btn btn-primary float-right" data-target="#pay_histry_modal" data-toggle="modal">   
                                                        {{ __('adminWords.click_here_to_withdrawal') }}
                                                    </a>
                                                </div>
                            				</div>
										</div>
										<div class="card-body ">
											<div class="row align-items-center">
												<div class="table-responsive pl-3 pr-3 pt-0">
													<table id="total_earnings" class="table table table-styled mb-0 dt-responsive nowrap">
														<thead>
															<tr>
																<th>#</th> 
																<th>{{ __('adminWords.audio_name') }}</th>
																<th>{{ __('adminWords.audio').' '.__('adminWords.price') }}</th>
																<th>{{ __('adminWords.admin').' '.__('adminWords.commission') }}</th>
																<th>{{ __('adminWords.admin').' '.__('adminWords.commission').' '.__('adminWords.price') }}</th>
																<th>{{ __('frontWords.tax_rate') }}</th>
																<th>{{ __('adminWords.total_tax') }}</th>
																<th>{{ __('adminWords.earning') }}</th>                                                            
																<th>{{ __('adminWords.transaction_date') }}</th>                                                            
															</tr>
														</thead>
														<tbody>
															@php 
																$i = 1; 
																$totalAmount = 0;
															@endphp
															@forelse($salesAmountDetails as $salesDetail)
																@php   
																	$artistAmount = 0;
																	if($salesDetail->commission_type == 'percent'){
																		$artistAmount = ($salesDetail->commission)*$salesDetail->amount/100;
																	}elseif($salesDetail->commission_type == 'flat'){
																		$artistAmount = $salesDetail->amount-$salesDetail->commission;
																		$artistAmount = $salesDetail->amount-$artistAmount;
																	}
																@endphp
																<tr>
																	<td>
																		<p class="semi-bold mb-0">{{ $i++ }}</p>
																	</td>
																	<td>
																		<p class="semi-bold mb-0">{{ $salesDetail->audio_title }}</p>
																	</td>
																	<td>
																		<p class="semi-bold mb-0 ">                                                        
																			{{ $salesDetail->currency.$salesDetail->amount }}
																		</p>
																	</td>
																	<td>
																		<p class="semi-bold mb-0">{{ $salesDetail->commission }}{{ isset($salesDetail->commission_type) && $salesDetail->commission_type == 'percent' ? '%' : '' }}</p>
																	</td>                                                    
																	<td>
																		<p class="semi-bold mb-0">{{ $salesDetail->currency.$artistAmount }}</p>
																	</td>  
																	<td>
																		<p class="semi-bold mb-0">
																		    @if(isset($settings['set_tax']) && !empty($settings['set_tax']) && isset($settings['tax']) && !empty($settings['tax']))
																		        {{ $curr.$settings['tax'] }}
																		    @else
																		        {{ $curr.'0' }}
																		    @endif
																	    </p>
																	</td> 
																	<td>
        																@if(isset($settings['set_tax']) && !empty($settings['set_tax']) && isset($settings['tax']) && !empty($settings['tax']))
        																    @php
        																		$tax_amount = ($settings['tax'])*$salesDetail->amount/100;
            																@endphp
            																{{ $salesDetail->currency.number_format($tax_amount,2) }}
        																@else
        																    {{ $salesDetail->currency.number_format($tax_amount,2) }}
        																@endif
																	</td>
																	
																	<td>
																		<p class="semi-bold mb-0">{{ $salesDetail->currency. number_format((($salesDetail->amount-$artistAmount)-$tax_amount),2) }}</p>
																		@php 
																			$totalAmount += ($salesDetail->amount-$artistAmount)-$tax_amount; 
																		@endphp
																	</td>  
																													 
																	<td>
																		<p class="semi-bold mb-0"> {{ date('d-m-Y', strtotime($salesDetail->created_at)) }}</p>
																	</td>  
																</tr>
															@empty
																<tr>
																	<td colspan="7">
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
									
									
									<div class="card m-b-30">
										<div class="card-body ">
											<div class="row align-items-center">
												<div class="table-responsive pl-3 pr-3 pt-0">
													<table class="table table-noborder col-md-5">
														<thead>
															<tr>
																<th>{{ __('adminWords.earning_with_commission') }}</th>
																<th>{{ __('adminWords.earning_without_commission') }}</th>
															</tr>
														</thead>
														<tbody>
															<tr>                                                            
																<td>
																	<h4 class="semi-bold mb-0">{{ $curr.number_format($salesAmount,2) }}</h4>
																</td>
																<td>
																	<h4 class="semi-bold mb-0" style="color:#56cb77;">{{ $curr.number_format($totalAmount,2) }}</h4>
																</td>
															</tr>
														</tbody>
													</table>
												</div>  
											</div>
										</div>
									</div>
									
									
									<!-- Total Earning -->
									<div style="display: none;">
										<div class="card m-b-30">
											<div class="card-header">
												<div class="col-10 pl-4">
													<h4 class="card-title mb-0">{{ __('adminWords.withdrawal_amount') }}</h4>
												</div>
											</div>
											<div class="card-body ">
												<div class="row align-items-center">
													<div class="table-responsive pl-3 pr-3 pt-0">
														<table class="table table-noborder">
															<thead>
																<tr>
																	<th>#</th>
																	<th>{{ __('adminWords.withdrawal_amount') }}</th>
																	<th>{{ __('adminWords.order_id') }}</th>                                                           
																</tr>
															</thead>
															<tbody>
																@php 
																	$i=1; 
																	$totalWithdrawalAmount = 0;
																@endphp
																@forelse($artistPaymentAmountDetails as $paymentDetail)
																	
																	<tr>
																		<td>
																			<h4 class="semi-bold mb-0">{{ $i++ }}</h4>
																		</td>                                                               
																		<td>
																			<h4 class="semi-bold mb-0 ">                                                        
																				{{ $paymentDetail->currency.$paymentDetail->amount }}
																			</h4>
																		</td>
																		<td>
																			<h4 class="semi-bold mb-0">{{ $paymentDetail->order_id }}</h4>
																			@php  
																				$totalWithdrawalAmount += $paymentDetail->amount; 
																			@endphp
																		</td>                                            
																	</tr>
																@empty
		
																@endforelse
															</tbody>
														</table>
													</div>                                            
													<div class="table-responsive pl-3 pr-3 pt-0">
														<table class="table table-noborder">
															<thead>
																<tr>
																	<th>{{ __('adminWords.total').' '.__('adminWords.withdrawal_amount') }}</th>
																</tr>
															</thead>
															<tbody>
																<tr>                                                            
																	<td>
																		<h4 class="semi-bold mb-0 primary-color">{{ $curr.$totalWithdrawalAmount }}</h4>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
		
												</div>
											</div>
										</div>
									</div>
									
								</div>
								<!-- Total Earning End -->
								
						   </div>
						</div>
					</div>
            </div>
        </div>
    </div>
</div>

    <!--Payment Modal Div-->
    <div class="modal fade" id="pay_histry_modal" tabindex="-1" role="dialog" aria-labelledby="pay_histry_modal_title" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    		<div class="modal-content">
    		    <div class="modal-header">
    			    <h5 class="modal-title" id="pay_histry_modal_title">{{ __('adminWords.withdrawal_amount') }}</h5>
        			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        			  <span aria-hidden="true">×</span>
        			</button>
    		    </div>
    		    <div class="modal-body">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card table-card users-wrap">
							<div class="card-header">
								<!--<h4 class="card-title">{{ __('adminWords.withdrawal_amount') }}</h4>-->
							</div>
							<div class="card-body">
								<div class="chart-holder">
									<div class="table-responsive">
										<table class="table table-styled mb-0">
											<tbody>
												<tr>
													<td>
														{{ __('adminWords.my_income') }} :
													</td>
													<td>
														{{ $curr.number_format($totalAmount,2) }}
													</td>
												</tr>
												<tr>
													<td>
														{{ __('adminWords.withdrawal_amount') }} :
													</td>
													<td>
													   - {{ $curr.$artistPaymentAmount }}
													</td>
												</tr>
												<tr>
													<td>{{ __('adminWords.balance') }} :</td>
													<td >{{ $curr.number_format($totalAmount-$artistPaymentAmount,2) }}</td>
												</tr>
												<tr>
													<td>{{ __('adminWords.please_enter').' '.__('adminWords.request_amount') }} :</td>
													<td><input class="form-control request_amount" type="number"></td>
												</tr>
	
											</tbody>
										</table>
									</div>
									<div class="msoArtistAmountRequest available-amount">
										<h6 class="m-0 semi-bold"></h6>
										<input type="hidden" id="withdrawBalance" data-amount="{{ $totalAmount-$artistPaymentAmount }}">
										<button type="button" class="effect-btn btn btn-primary" 
											id="@if($defaultGateway == ''){{'defaultGatewayeError'}}@elseif($requestStatus == 1){{'artistWithdrawRequest'}}@else{{ 'alreadyRequested' }}@endif">   
											{{ __('adminWords.withdrawal_now') }}
										</button>
									</div>
									<!--<div class="view-btn-wrap"></div>-->
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#total_earnings').DataTable();
        });
        $(document).ready(function() {
            $('#request_payment').DataTable();
        });
    </script>  
@endsection
