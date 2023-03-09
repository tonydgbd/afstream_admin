@extends('layouts.front.main')
@section('title', __('frontWords.purchase_receipt'))
@section('content')
    @inject('audio', 'Modules\Audio\Entities\Audio')
    
    @php
        $audio_name = '';
        $audio = $audio->where(['id' => $receiptDetail->audio_id])->first();
        if(!empty($audio)){
            $audio_name = $audio->audio_title;
        }
    @endphp
    
    <div class="ms_history_wrapper common_pages_space">
        <div class="ms_history_inner"> 
            
            <div class="ms_free_download">
                <div class="ms_heading">
                    <h1>  {{ __('frontWords.purchase_receipt') }}</h1>
                </div>
                
                <!-- Table Start -->
                <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @if(isset($receiptDetail) && !empty($receiptDetail))
                                @php
                                    if($receiptDetail->status == 2){
                                        $cls = 'info_status';
                                        $msg = __('adminWords.pending');
                                    }elseif($receiptDetail->status == 1){
                                        $cls = 'success_status';
                                        $msg = __('adminWords.success') ;
                                    }else{
                                        $cls = 'warning_status';
                                        $msg = __('adminWords.cancelled');
                                    }
                                    
                                    $paymentData = json_decode($receiptDetail->payment_data)[0];
                                @endphp
        
                            <div class="invoice_header">
                                <div class="invoice_header_left">
                                    <div class="ad-invoice-title">
                                        <h4>{{ isset($settings['w_title']) ? ucfirst($settings['w_title']) : 'Musioo' }}</h4>
                                        <h4>{{ __('adminWords.payment_status').' : '.$msg}}</h4>
                                        <h4> {{ __('adminWords.invoice') }} : {{ 'INV'.'-'.$receiptDetail->id.'-'.date('Ymd',strtotime($receiptDetail->created_at)) }}</h4>
                                        <h4> {{ __('adminWords.txn_id') }} : {{ $paymentData->transaction_id }}</h4>
                                    </div>                            
                                </div>
        
                                <div class="invoice_header_right">
                                    {{ __('adminWords.txn_date') }}
                                    @if(!empty($receiptDetail->created_at))                               
                                        <h4>{{ date('d F, Y', strtotime($receiptDetail->created_at)) }}</h4>
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
                                            <th>{{ __('adminWords.audio_name') }}</th>
                                            <th>{{ __('adminWords.price') }}</th>
                                            <th>{{ __('frontWords.tax_rate') }} </th>
                                            <th>{{ __('adminWords.total_tax') }} </th>
                                            <th>{{ __('adminWords.amount') }} </th>
                                                                              
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        
                                            <td>1</td>                                        
                                            <td>{{ $audio_name }}</td>
                                            @php
                                                $taxPercent = '0%';
                                                $taxAmount = 0;
                                            @endphp
                                            @if(isset($settings['set_tax']) && $settings['set_tax'] == 1)
                                                @php    
                                                    $taxPercent = $settings['tax'].'%';
                                                    $taxAmount = ($settings['tax'])*$receiptDetail->amount/100;
                                                @endphp
                                            @endif
                                            
                                            <td>{{ number_format($receiptDetail->amount-$taxAmount,2) }}</td>
                                            
                                            <td>{{ $taxPercent }}</td>
                                            
                                            <td>{{ $paymentData->currency.$taxAmount }}</td>
                                            
                                            <td>{{ $paymentData->currency.number_format($receiptDetail->amount,2) }}</td>
                                            
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
            
            </div>
            
        </div>
    </div>

@endsection