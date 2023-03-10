@extends('layouts.admin.main')
@section('title', __('adminWords.invoice'))
@section('content')

    <!-- Container Start -->       
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper">
                <div class="page-title-box">
                    <h4 class="page-title">{{ __('adminWords.invoice') }}</h4>
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
                            if($type == 0){
                                $getPlan = select(['column' => '*', 'table' => 'plans', 'where' => ['id' => $invoiceData->plan_id], 'limit' => 1]);
                                $planData = [];
                            }else{
                                $getPlan = [];
                                $planData = select(['column' => '*', 'table' => 'user_purchased_plans', 'where' => ['user_id' => $invoiceData->user_id, 'order_id' => $order_id], 'order'=>['id','desc'], 'limit' => 1 ]);
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
                            @if(!empty($planData[0]->created_at))
                                <h4>{{ date('d F, Y', strtotime($planData[0]->created_at)) }}</h4>
                            @else
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
                            <p><img src="{{ asset('assets/images/Payment/'.ucfirst($paymentData->payment_gateway).'.png') }}"></p>
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
                                    <th>{{ __('adminWords.order_id') }}</th>  
                                    <th>{{ __('adminWords.plan') }}</th>
                                    <th>{{ __('adminWords.price') }}</th>
                                                                      
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                @php
                                    if(sizeof($planData) > 0){
                                        $planDetail = json_decode($planData[0]->plan_data);
                                        $paymentData = json_decode($planData[0]->payment_data);
                                @endphp
                                    <td>{{ $invoiceData->order_id }}</td>                                        
                                    <td>{{ $planDetail->plan_name }}</td>
                                    <td>{{ $planData[0]->currency.$paymentData[0]->amount }}</td>
                                @php
                                    }else if(sizeof($getPlan) > 0){
                                        $plan_detail = json_decode($invoiceData->payment_data);
                                @endphp                                
                                    <td>{{ $invoiceData->order_id }}</td>                                        
                                    <td>{{ $getPlan[0]->plan_name }}</td>
                                    <td>{{ $plan_detail[0]->currency.$plan_detail[0]->amount }}</td>                                            
                                @php }
                                @endphp         
                                @if(sizeof($planData) > 0 || sizeof($getPlan) > 0)
                                    @php if(sizeof($planData) > 0 ){
                                            $payment_data = json_decode($planData[0]->payment_data);
                                        }else{
                                            $payment_data = json_decode($invoiceData->payment_data);
                                        }
                                    @endphp
                                    <tr>
                                        <td colspan="2">{{ __('adminWords.price') }} :</td>
                                        <td>{{ ((sizeof($planData) > 0) ? $planData[0]->currency : $payment_data[0]->currency).$payment_data[0]->plan_exact_amount }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">{{ __('adminWords.total_tax') }} ({{ $payment_data[0]->taxPercent }}%) :</td>
                                        <td>{{ '+ '.((sizeof($planData) > 0) ? $planData[0]->currency : $payment_data[0]->currency).$payment_data[0]->taxAmount }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">{{ __('adminWords.coupon_discount') }} :</td>
                                        <td>{{ '- '.((sizeof($planData) > 0) ? $planData[0]->currency : $payment_data[0]->currency).$payment_data[0]->discount }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="f-w-7 font-18"><h5>{{ __('adminWords.payable_amnt') }} :</h5></td>
                                        <td class="f-w-7 font-18"><h5>{{ ((sizeof($planData) > 0) ? $planData[0]->currency : $payment_data[0]->currency).$payment_data[0]->amount }}</h5></td>
                                    </tr> 
                                @endif
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