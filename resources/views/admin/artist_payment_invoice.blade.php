@extends('layouts.artist.main')
@section('title', __('adminWords.audio').' '.__('adminWords.invoice'))
@section('content')

    <!-- Container Start -->       
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper">
                <div class="page-title-box">
                    <h4 class="page-title">{{ __('adminWords.audio').' '.__('adminWords.invoice') }}</h4>
                </div>
                <div class="breadcrumb-list">
                    <ul>
                        <li class="breadcrumb-link">
                            <a href="index.html"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                        </li>
                        <li class="breadcrumb-link active">{{ __('adminWords.invoice') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Start -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if(!empty($invoiceData))
                        @php
                            if($type == 0 && $invoiceData->status == 2){
                                $cls = 'info_status';
                                $msg = __('adminWords.pending');
                            }elseif($type == 1){
                                $cls = 'success_status';
                                $msg = __('adminWords.success') ;
                            }else{
                                $cls = 'warning_status';
                                $msg = __('adminWords.cancelled');
                            }
                            
                            $paymentData = json_decode($invoiceData->payment_data)[0];
                        @endphp

                    <div class="invoice_header">
                        <div class="invoice_header_left">
                            <div class="ad-invoice-title">
                                <h4>{{ isset($settings['w_title']) ? ucfirst($settings['w_title']) : 'Musioo' }}</h4>
                                <h4>{{ __('adminWords.payment_status').' : '.$msg}}</h4>
                                <h4> {{ __('adminWords.invoice') }} : {{ 'INV'.'-'.$invoiceData->id.'-'.date('Ymd',strtotime($invoiceData->created_at)) }}</h4>
                            </div>                            
                        </div>

                        <div class="invoice_header_right">
                            {{ __('adminWords.txn_date') }}
                            @if(!empty($invoiceData->created_at))                               
                                <h4>{{ date('d F, Y', strtotime($invoiceData->created_at)) }}</h4>
                            @endif
                        </div>
                    </div>

                    <hr>
                    <div class="payment_detailsheaderparent">
                        <div class="bill_detailsheader">
                            <h5 class="mb-2">{{ __('frontWords.bill_to') }}:</h5>
                            <p class="text-muted">{{ $paymentData->user_name }}</p>                            
                            <p>{{ $paymentData->user_email }}</p>                              
                        </div>

                        <div class="pyment_rightheader text-sm-end">
                            
                            <h5 class="mb-2">{{ __('adminWords.payment_method') }}:</h5>
                            <p><img src="{{ asset('public/assets/images/Payment/'.ucfirst($paymentData->payment_gateway).'.png') }}"></p>
                            @php
                                $exp = explode('_',$paymentData->payment_gateway);
                            @endphp
                            <p>{{ __('adminWords.via') }} {{ucfirst($exp[0]).(isset($exp[1]) ? ' '.$exp[1] : '' )}}</p>
                        </div>
                    </div>
                                     
                    
                  
                    <div class="py-2 mt-3 mb-2">
                        <h4 class="font-size-15">Order Summary</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-styled mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('adminWords.id') }}</th> 
                                    <th>{{ __('adminWords.quantity') }}</th>
                                    <th>{{ __('adminWords.price') }}</th>
                                                                      
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>              
                                    <td>1</td> 
                                    <td>{{ $paymentData->currency.$paymentData->amount }}</td>
                                </tr>
                            </tbody>
                        </table> 

                    </div>                

                    <div class="d-print-none mt-2">
                        <div class="float-end">
                            <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i></a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <script>
       
    </script>
    @endsection
@endsection