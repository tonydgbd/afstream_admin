@extends('layouts.front.main')
@section('title', __('frontWords.audio_purchase_history'))
@inject('audio', 'Modules\Audio\Entities\Audio')

@section('content')
    
     @php
        $curr = '';
        if(isset($defaultCurrency->symbol) && !empty($defaultCurrency->symbol)){
            $curr = $defaultCurrency->symbol; 
        }elseif((session()->get('currency')['symbol']) && !empty(session()->get('currency')['symbol'])){
            $curr = session()->get('currency')['symbol'];
        }
    @endphp
    <div class="ms_history_wrapper common_pages_space">
        <div class="ms_history_inner"> 
        
            <div class="ms_free_download">
                <div class="ms_heading">
                    <h1>  {{ __('frontWords.audio_purchase_history') }}</h1>
                </div>
                <div class="album_inner_list">
                    <div class="album_list_wrapper">
                        <ul class="album_list_name">
                            <li>#</li>
                            <li>{{ __('adminWords.order_id') }}</li>
                            <li>{{ __('adminWords.audio').' '.__('adminWords.title') }} </li>
                            <li>{{ __('adminWords.price') }} </li>
                            <li>{{ __('adminWords.payment_method') }} </li>
                            <li>{{ __('adminWords.download') }} </li>
                            <li>{{ __('adminWords.txn_date') }} </li>
                            <!--<li>License</li>-->
                        </ul>
                        @php
                            $i = 1;
                        @endphp
                        @forelse($audioPurchaseHistory as $purchaseHistory)
                            <ul>
                                <li>{{ $i++ }}</li>
                                <li><a href="{{ route('audioPurchaseReceipt',['id'=>$purchaseHistory['id']])}}" target="_blank">{{ $purchaseHistory['order_id'] }}</a></li>
                                @php
                                    $audio_title = '';
                                    $audio = $audio->where('id',$purchaseHistory['audio_id'])->first();
                                    
                                    if(isset($audio) && !empty($audio)){
                                        $audio_title = $audio->audio_title;
                                    }
                                @endphp
                                <li>{{ $audio_title }}</li>
                                <li>{{ $curr.$purchaseHistory['amount'] }}</li>
                                <li>{{ ucfirst($purchaseHistory['payment_gateway']) }}</li>
                                @php
                                    $audioDetail = \Modules\Audio\Entities\Audio::where(['id'=> $purchaseHistory['audio_id']])->first();
                                    $checkDownload = \App\UserAction::select('download')->where(['user_id'=> auth()->user()->id , 'audio_id' => $purchaseHistory['audio_id']])->first();
                                @endphp
                                @if(empty($checkDownload) && $checkDownload == '')
                                    <li class="downloadPurchaseSong">
                                        @if(isset($audioDetail) && $audioDetail->aws_upload == 1)
                                            <a href=" {{ getSongAWSUrlHtml($audioDetail) }} ">
                                                <i class="fa fa-download mr-2"></i></span>
                                            </a> 
                                        @else    
                                            <a href="javascript:void(0);" class="download_track" data-musicid="{{ $purchaseHistory['audio_id'] }}">
                                                <i class="fa fa-download mr-2"></i></span>
                                            </a>
                                        @endif   
                                    </li>
                                @else
                                    <li></li>
                                @endif
                                <li>{{ date('d-m-Y', strtotime($purchaseHistory['created_at'])) }}</li>
                            </ul>
                        @empty
                            <ul>
                                <li class="text-center">{{ __('adminWords.no_data') }}</li>
                            </ul>
                        @endforelse
                        
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>    
@endsection