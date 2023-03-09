@extends('layouts.admin.main')
@section('title', __('adminWords.request_payment'))
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
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.all').' '.__('adminWords.request_payment') }}</h4>
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
			<div class="card m-b-30">
				<div class="card-header">                                
					<div class="row align-items-center">
						<div class="col-6">
							<h5 class="card-title mb-0">{{ __('adminWords.all').' '.__('adminWords.request_payment') }}</h5>
						</div>
					</div>
				</div>

                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 adminMultipleFilterOption"> 
                            <div class="data_tablefilter_option">
                                <div class="form-group">
                                    <label for="PrArtistData">{{ __('adminWords.select').' '.__('adminWords.artist') }}<sup>*</sup></label> 
                                    <select class="form-control select2WithSearch require getArtistId" id="PrArtistData">
                                            <option value="" disabled selected>{{ __('adminWords.select').' '.__('adminWords.artist') }}</option>
                                        @forelse($artists as $artist)
                                            <option value="{{$artist->id}}">{{$artist->name}}</option>
                                        @empty
                                            <option value="">{{ __('adminWords.no_artist_found') }}</option>
                                        @endforelse

                                    </select>
                                </div>
                                <div id="reportrange" class="reportrangefilter">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>                              
                                <div class="data_tablefilter_btn">
                                    <button type="button" class="effect-btn btn btn-primary multiple_dt_filter" data-tabel="paymentRequestDataTables" data-url="{{route('admin.payment_request_data')}}">{{ __('adminWords.filter') }}</button>
                                </div>
                            </div>
                        </div>                
                    </div>  
                    <div class="table-responsive">
                        <form method="post" id="paymentRequestForm">
                            <table data-method="post" id="datatable-buttons" class="table table-styled musiooDtToShowData paymentRequestDataTables" data-url="{{route('admin.payment_request_data')}}">
                                <thead>
                                    <tr> 
                                        <th>#</th>
                                        <th>{{ __('adminWords.artist_name') }}</th> 
                                        <th>{{ __('adminWords.request_amount') }}</th>
                                        <th>{{ __('adminWords.status') }}</th>
                                        <th>{{ __('adminWords.bank_status') }}</th>
                                        <th>{{ __('adminWords.request_date') }}</th>
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

<div class="modal fade" id="releasePaymentToArtistByAdmin">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('adminWords.pay_with').' '.__('adminWords.paypal') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">

                    <div class="form-group paypalSubmit" style="display:none;">
                        <form class="form-horizontal" method="POST" id="paypal-form" role="form" action="">
                            {{ csrf_field() }}
                            <input type="hidden" class="artistRequestId" name="request_id" value="">
                            <input type="hidden" class="artistPayableAmount" name="amount" value="">    
                            <div>
                                <button type="submit" class="effect-btn btn btn-primary">
                                    {{ __('adminWords.pay_with').' '.__('adminWords.paypal') }}
                                </button>
                            </div>
                        </form>
                    </div>
        
                    <div class="stripeSubmit" style="display:none;">
                        <label>{{ __('frontWords.card_detail') }}</label>
                        <form action="{{ route('stripe.artistReleasePayment') }}" method="POST" id="stripe-form" class="card_Detail" data-redirect="{{ route('admin.payment_request') }}">
                            {{ csrf_field() }}
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                         <input placeholder="Card number" class="require" type="tel" name="number">
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input placeholder="Full name" class="require" type="text" name="name">
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input placeholder="MM/YY" class="require" type="tel" name="expiry">
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input placeholder="CVC" class="require" type="number" name="cvc">
                                    </div>
                                </div>  
                            </div>
                            <input type="hidden" class="artistRequestId" name="request_id" value="">
                            <input type="hidden" class="artistPayableAmount" name="amount" value=""> 
                            <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{ __('adminWords.pay_with').' '.__('adminWords.stripe') }} </button>
                           
                        </form>
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
