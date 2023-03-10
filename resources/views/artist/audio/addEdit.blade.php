
@extends('layouts.artist.main')
@section('title', __('adminWords.audio'))
@section('style')
<link href="{{ asset('assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')  

@php 
    
    $curr = '';
    $govt_tax = 0;
    
    if(isset($settings['set_tax']) && !empty($settings['set_tax']) && $settings['set_tax'] == 1 && isset($settings['tax']) && !empty($settings['tax'])){
        $govt_tax = $settings['tax'];
    }
    
    if(!empty($defaultCurrency->symbol)){
        $curr = $defaultCurrency->symbol; 
    }elseif(isset(session()->get('currency')['symbol']) && !empty(session()->get('currency')['symbol'])){
        $curr = session()->get('currency')['symbol'];
    }
@endphp
<!-- Page Title Start -->
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper"> 
                <div class="page-title-box">
                    <h4 class="page-title bold">
                        {{ isset($audioData) ? __('adminWords.update').' '.__('adminWords.audio') : __('adminWords.create').' '.__('adminWords.audio') }}
                    </h4>
                </div>
                <div class="musioo-brdcrmb breadcrumb-list">
                    <ul>
                        <li class="breadcrumb-link">
                            <a href="{{ route('artist.home') }}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                        </li>
                        <li class="breadcrumb-link active">{{ __('adminWords.audio') }}</li>
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
                    <div class="text-right">   
                        <a class="effect-btn btn btn-primary" href="{{ route('artist.audio') }}">{{ __('adminWords.go_back') }}</a>
                    </div>                             
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title mb-0">{{ isset($audioData) ? __('adminWords.update').' '.__('adminWords.audio') : __('adminWords.create').' '.__('adminWords.audio') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <div class="admin-form">
                    @if(isset($audioData))
                      {!! Form::model($audioData, ['method'=>'post', 'files'=>true, 'route'=>['artistAddEditAudio', $audioData->id], 'id'=>'updateAudio', 'onsubmit'=>'return false', 'data-redirect' => route('artist.audio')]) !!}
                    @else
                      {!! Form::open(['method' => 'POST', 'route'=>['artistAddEditAudio','add'], 'data-reset'=>1, 'files' => true, 'onsubmit'=>'return false', 'data-redirect' => route('artist.audio')]) !!}
                    @endif
                    <div class="row">
                        <div class="col-lg-6"> 
                            <div class="form-group{{$errors->has('audio_title') ? 'has-error' : ''}}">
                              <label for="audio_title">{{  __('adminWords.audio').' '.__('adminWords.title') }}<sup>*</sup></label>
                              {!! Form::text('audio_title', null, ['class' => 'form-control require', 'required', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.audio').' '.__('adminWords.title') ]) !!}
                              <small class="text-danger">{{ $errors->first('audio_title')}}</small>
                            </div>
                            <div class="form-group{{ $errors->has('audio_genre_id') ? ' has-error' : '' }}">
                                <label for="audio_genre_id">{{ __('adminWords.select').' '.__('adminWords.audio_genre') }}<sup>*</sup></label>
                                {!! Form::select('audio_genre_id', $audioGenre, (isset($audioData) ? $audioData->audio_genre_id : ''), ['class' => 'form-control select2WithSearch require','placeholder' => __('adminWords.choose') ]) !!}
                                <small class="text-danger">{{ $errors->first('audio_genre_id') }}</small>
                            </div> 
                            <div class="form-group{{ $errors->has('audio_language') ? ' has-error' : '' }}">
                                <label for="audio_language">{{ __('adminWords.select').' '.__('adminWords.language') }}<sup>*</sup></label>
                                {!! Form::select('audio_language', $audioLanguage, (isset($audioData) ? $audioData->audio_language : ''), ['class' => 'form-control select2WithSearch require artistAudioLanguageId','placeholder' => __('adminWords.choose')]) !!}
                                <small class="text-danger">{{ $errors->first('audio_language') }}</small>
                            </div> 
                            <div class="form-group{{ $errors->has('artist_id') ? ' has-error' : '' }}">
                                <label for="artist_id">{{ __('adminWords.select').' '.__('adminWords.artist') }}<sup>*</sup></label>
                                <select name="artist_id[]" class="form-control multipleSelectWithSearch require" data-placeholder="{{__('adminWords.choose')}}"  multiple="multiple" id="audio_artist_list">
                                    @foreach($artist as $key=>$artists)  
                                        <option value="{{$key}}" @if(isset($audioData)) @foreach(json_decode($audioData->artist_id) as $aid) {{ $aid == $key ? "selected" : "" }} @endforeach @endif >{{ $artists }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger">{{ $errors->first('artist_id') }}</small>
                            </div> 
                            <div class="form-group{{ $errors->has('copyright') ? ' has-error' : '' }}">
                                {!! Form::label('copyright', __('adminWords.copyright') ) !!}
                                {!! Form::text('copyright', null, ['class' => 'form-control', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.copyright'), 'rows'=>'3']) !!}
                                <small class="text-danger">{{ $errors->first('copyright') }}</small>
                            </div>

                            <div class="form-group{{ $errors->has('release_date') ? ' has-error' : '' }}">
                              {!! Form::label('release_date', 'Release Date' ) !!}
                              {!! Form::text('release_date', null, ['class' => 'form-control date-calender', 'placeholder'=>__('adminWords.enter').' Release Date', 'rows'=>'3']) !!}
                              <small class="text-danger">{{ $errors->first('release_date') }}</small>
                            </div> 

                            <div class="img-upload-preview">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group{{$errors->has('image') ? 'has-error' : ''}}">
                                            {!! Form::label('image', __('adminWords.audio').' '.__('adminWords.image'), ['class'=>'col-lg-12']) !!}
                                            <label for="image" class="file-upload-wrapper js-labelFile" data-text="Select your file!" data-toggle="tooltip" data-original-title="Audio Image">
                                              <i class="icon fa fa-check"></i>
                                              {!! Form::file('image',['class' => 'basicImage form-control hide', 'name'=>'image', 'data-ext'=>"['jpg','jpeg','png']", 'data-image-id'=>'audioImage', 'data-label'=>'audio_imagee', 'data-image' => __('adminWords.image_error')]) !!}
                                              <span class="js-fileName"></span>
                                            </label>
                                              <input type="hidden" id="image_name" value="{{(isset($audioData) ? $audioData->image:'')}}">
                                              <span class="image_title" id="audio_imagee">{{(isset($audioData) && $audioData->image != '' ? $audioData->image : '' )}}</span>
                                              <small class="text-danger">{{ $errors->first('image')}}</small>
                                              <input type="hidden" id="audioImage" />
                                              <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 500X500 px' }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="image-block site_image_dv">
                                            @if(isset($audioData->image) && $audioData->image != null) 
                                                <img src="{{asset('images/audio/thumb/'.$audioData->image)}}" class="img-responsive" alt="" height="200px" width="200px">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            
                            <div class="img-upload-preview">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group{{$errors->has('audio') ? 'has-error' : ''}}">
                                            {!! Form::label('audio', __('adminWords.audio'), ['class'=>'col-lg-6']) !!}
                                            <label for="audio" class="js-labelFile file-upload-wrapper" data-text="Select your file!" -toggle="tooltip" data-original-title="Audio">
                                              <i class="icon fa fa-check"></i>
                                              {!! Form::file('audio',['class' => 'basicImage form-control hide', 'name'=>'audio', 'data-ext'=>"['mp3','wav']", 'data-audio-id'=>'audio', 'data-label'=>'audio_url']) !!}
                                              <span class="js-fileName"></span>
                                            </label>
                                              <input type="hidden" id="audio_name" value="{{(isset($audioData) ? $audioData->audio:'')}}">
                                              <span class="image_title" id="audio_url">{{(isset($audioData) && $audioData->audio != '' ? $audioData->audio : '' )}}</span>
                                            <small class="text-danger">{{ $errors->first('audio')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                      </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-0 dd-flex">
                                <div class="checkbox mr-4">                                          
                                    {!! Form::checkbox('status', 1, (isset($audioData) &&   $audioData->status == 0 ? 0 : 1),['id'=>'status']) !!}                                       
                                    {!! Form::label('status', __('adminWords.status')) !!}    
                                    <small class="text-danger">{{ $errors->first('status') }}</small>                      
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_featured', 1, (isset($audioData) &&   $audioData->is_featured == 0 ? 0 : 1),['id'=>'is_featured']) !!}
                                    {!! Form::label('is_featured', __('adminWords.featured') ) !!}           
                                    <small class="text-danger">{{ $errors->first('is_featured') }}</small>                 
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_trending', 1, (isset($audioData) && $audioData->is_trending == 0 ? 0 : 1),['id'=>'is_trending']) !!}
                                    {!! Form::label('is_trending', __('adminWords.trending') ) !!}   
                                    <small class="text-danger">{{ $errors->first('is_trending') }}</small>                         
                                </div>
                                <div class="checkbox mr-4">
                                    {!! Form::checkbox('is_recommended', 1, (isset($audioData) &&   $audioData->is_recommended == 0 ? 0 : 1),['id'=>'is_recommended']) !!}
                                    {!! Form::label('is_recommended', __('adminWords.recommended') ) !!}   
                                    <small class="text-danger">{{ $errors->first('is_recommended') }}</small>                        
                                </div>           
                            </div>
                            
                            <div class="form-group{{ $errors->has('lyrics') ? ' has-error' : '' }}">
                                {!! Form::label('lyrics', __('adminWords.lyrics') ) !!}
                                {!! Form::textarea('lyrics', null, ['id' => 'summernote', 'class' => 'form-control', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.lyrics'), 'rows'=>'3']) !!}
                                <small class="text-danger">{{ $errors->first('adminWords.lyrics') }}</small>
                            </div>
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                {!! Form::label('description', __('adminWords.description')) !!}
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder'=> __('adminWords.enter').' '.__('adminWords.description'), 'rows'=>'3']) !!}
                                <small class="text-danger">{{ $errors->first('description') }}</small>
                            </div> 
                            
                            
                            <label>{{ __('adminWords.download').' '.__('adminWords.audio') }}</label>
                            <div class="form-group dd-flex">
                                <div class="radio radio-primary mr-4">
                                  {!! Form::radio('is_price', 0, (isset($audioData) && $audioData->is_price == 0 ? 'checked' :  (isset($audioData) && ($audioData->is_price != 1) ? 'checked' : 'checked')), ['id'=>'unpaid','class'=>'audioDownloadByPlan']) !!}
                                  <label for="unpaid" class="mb-0">{{ __('adminWords.by_plan') }}</label>
                                </div>
                                <div class="radio radio-primary mr-4">
                                  {!! Form::radio('is_price', 1,(isset($audioData) && $audioData->is_price == 1 ? 'checked' : ''), ['id'=>'paid','class'=>'paidAudioDownload']) !!}
                                  <label for="paid" class="mb-0">{{ __('adminWords.is_paid') }}</label>
                                </div> 
                                </br>
                                @php 
                                    $downloadPrice = '';
                                    if(isset($audioData) && $audioData->download_price == 0 && $audioData->download_price == ''){
                                        $downloadPrice = 'display:none;';
                                    }elseif(isset($audioData) && $audioData->download_price != '' && $audioData->download_price > 0){
                                        $downloadPrice = 'display:block;';
                                    }else{
                                        $downloadPrice = 'display:none;';
                                    }
                                @endphp
                                
                                <!--Audio Download Form Start  -->
                                    <div class="form-group{{ $errors->has('download_price') ? ' has-error' : '' }} paidArtistAudioDownload" style="{{$downloadPrice}} margin-top:20px;">
                                    <label for="download_price" class="mb-0 toltiped " title="" data-original-title="{{ __('adminWords.artist_audio_price_note') }}">
                                        {{  __('adminWords.download').' '.__('adminWords.price') }}
                                    </label>
                                    
                                    {!! Form::number('download_price', null, ['class' => 'form-control inptArtistAudioAfterCommission', 'placeholder'=>__('adminWords.enter').' '.__('adminWords.download').' '.__('adminWords.price'), 'rows'=>'3']) !!}
                                    <small class="text-danger">{{ $errors->first('download_price') }}</small>
                                    <div class="adminCommissionForArtist">
                                    <input type="hidden" class="currencyType" value="{{(isset($curr) && $curr != '' ? $curr : '$')}}">              
                                    <input type="hidden" class="govTaxPercent" value="{{(isset($govt_tax) ? $govt_tax : '0')}}">
                                        @if(isset($settings) && isset($settings['is_commission']) && $settings['is_commission'] == 1 && isset($settings['commission_val']) && !empty($settings['commission_val']))
                                            <input type="hidden" class="checkAdminCommssion" value="{{ $settings['is_commission'] }}">
                                            <input type="hidden" class="adminCommssionValue" value="{{ $settings['commission_val'] }}">
                                            <input type="hidden" class="adminCommissionType" value="{{ $settings['commission_type'] }}">
                                            @php 
                                                if($settings['commission_type'] == 'percent'){
                                                    $sym = '%';
                                                }elseif($settings['commission_type'] == 'flat'){
                                                    $sym = ' ';
                                                }
                                            @endphp
                                            
                                                <div class="musio-artist-amount-wrapper">
                                                    <div class="artist-amount">
                                                        @if(isset($audioData) && !empty($audioData->download_price))
                                                            
                                                            @php 
                                                                $withdrawal_amount = 0; 
                                                                $adminCommissionAmount = 0;
                                                                $govTaxAmount = 0;
                                                                $totalTaxAndCommission = 0;
                                                                $adminCommTax = 0;
                                                                $finalWithdrowAmount = 0;
                                                                
                                                                if($settings['commission_type'] == 'percent'){
                                                                    $percentAmount = intval(($settings['commission_val'])*$audioData->download_price/100);
                                                                    $withdrawal_amount = $audioData->download_price - $percentAmount;
                                                                    $adminCommissionAmount = $audioData->download_price-$withdrawal_amount;
                                                                }elseif($settings['commission_type'] == 'flat'){
                                                                    $withdrawal_amount = $audioData->download_price-$settings['commission_val'];
                                                                    $adminCommissionAmount = $audioData->download_price-$withdrawal_amount;
                                                                }
                                                                
                                                                if(isset($settings['set_tax']) && !empty($settings['set_tax'] && $settings['set_tax'] == 1 && !empty($settings['tax']))){
                                                                    $percentTaxAmount = number_format(($audioData->download_price)*$settings['tax']/100,2);
                                                                    $adminCommTax = $percentTaxAmount;
                                                                }
                                                                
                                                                $totalTaxAndCommission = number_format($adminCommissionAmount+$percentTaxAmount,2);
                                                                
                                                            @endphp
                                                            
                                                            
                                                            <div class="artist-amount-section">
                                                                <div class="amount-info">
                                                                    <h4>{{ __('adminWords.contract_amount') }}</h4>
                                                                    <p>{{ __('adminWords.amount_charge_to_client') }}</p>
                                                                </div>
                                                                <div class="amount-total">
                                                                    <h5 class="showAddedAudioPrice">{{(isset($audioData) ? $curr.$audioData->download_price : $curr.'0')}}</h5>
                                                                </div>
                                                            </div>
                                                            <div class="artist-amount-section">
                                                                <div class="amount-info">
                                                                    <h4>{{ __('adminWords.admin_commission_include_vat') }}</h4>
                                                                    <p>{{ __('adminWords.admin_commission') }} <span class="txt-blue showAddedAudioPrice">{{(isset($audioData) && $audioData->download_price != '' ? $curr.$audioData->download_price : $curr.'')}}</span> - {{$settings['commission_val'].$sym }} = <span class="txt-yellow adminCommissionAmount">{{ $curr.$adminCommissionAmount }}</span></p>
                                                                    @if(isset($settings['set_tax']) && !empty($settings['set_tax']) && $settings['set_tax'] == 1 && isset($settings['tax']) && !empty($settings['tax']))
                                                                        <p>{{ __('adminWords.eservice_gov_vat') }} <span class="txt-blue showAddedAudioPrice">{{(isset($audioData) && $audioData->download_price != '' ? $curr.$audioData->download_price : $curr.'0')}}</span> - {{$settings['tax'].'%' }} = <span class="txt-yellow govTaxAmount">{{ $curr.$adminCommTax }}</span></p>
                                                                    @else                                                                  
                                                                        <p>{{ __('adminWords.eservice_gov_vat') }} <span class="txt-blue showAddedAudioPrice">{{(isset($audioData) && $audioData->download_price != '' ? $curr.$audioData->download_price : $curr.'0')}}</span> - {{$govt_tax.'%' }} = <span class="txt-yellow govTaxAmount">{{ $curr.'0' }}</span></p>
                                                                    @endif
                                                                    
                                                                </div>
                                                                <div class="amount-total">
                                                                    <h5 class="txt-yellow adminCommissionWithTaxAmount">-{{ $curr.$totalTaxAndCommission }}</h5>
                                                                </div>
                                                            </div>
                                                            <div class="artist-amount-section">
                                                                <div class="amount-info">
                                                                    <h4><span class="txt-green">{{ __('adminWords.take_home_earnings') }}</span>{{ __('adminWords.after_feesandvat') }}</h4>
                                                                    <p>{{ __('adminWords.this_what_you_earn') }} {{(isset($settings['commission_val']) && !empty($settings['commission_val']) ? $settings['commission_val'] : '0').$sym}} {{ __('adminWords.admin_vat_deducted_to') }}</p>
                                                                </div>
                                                                <div class="amount-total">
                                                                    <h5 class="finalArtistWithdrawalAmount">{{ $curr.(number_format($withdrawal_amount-$adminCommTax,2)) }}</h5>
                                                                </div>
                                                            </div>                                                       
                                                        
                                                        @else
                                                            
                                                            <div class="artist-amount-section">
                                                                <div class="amount-info">
                                                                    <h4>{{ __('adminWords.contract_amount') }}</h4>
                                                                    <p>{{ __('adminWords.amount_charge_to_client') }}</p>
                                                                </div>
                                                                <div class="amount-total">
                                                                    <h5 class="showAddedAudioPrice">{{(isset($audioData) ? $curr.$audioData->download_price : $curr.'0')}}</h5>
                                                                </div>
                                                            </div>
                                                            <div class="artist-amount-section">
                                                                <div class="amount-info">
                                                                    <h4>{{ __('adminWords.admin_commission_include_vat') }}</h4>
                                                                    <p>{{ __('adminWords.admin_commission') }} <span class="txt-blue showAddedAudioPrice">{{(isset($audioData) && $audioData->download_price != '' ? $curr.$audioData->download_price : $curr.'')}}</span> - {{$settings['commission_val'].$sym }} = <span class="txt-yellow adminCommissionAmount"></span></p>
                                                                    @if(isset($settings['set_tax']) && !empty($settings['set_tax']) && $settings['set_tax'] == 1 && isset($settings['tax']) && !empty($settings['tax']))
                                                                        <p>{{ __('adminWords.eservice_gov_vat') }} <span class="txt-blue showAddedAudioPrice">{{(isset($audioData) && $audioData->download_price != '' ? $curr.$audioData->download_price : $curr.'0')}}</span> - {{$settings['tax'].'%' }} = <span class="txt-yellow govTaxAmount"></span></p>
                                                                    @else                                                                  
                                                                        <p>{{ __('adminWords.eservice_gov_vat') }} <span class="txt-blue showAddedAudioPrice">{{(isset($audioData) && $audioData->download_price != '' ? $curr.$audioData->download_price : $curr.'0')}}</span> - {{$govt_tax.'%' }} = <span class="txt-yellow govTaxAmount">{{ $curr.'0' }}</span></p>
                                                                    @endif
                                                                    
                                                                </div>
                                                                <div class="amount-total">
                                                                    <h5 class="txt-yellow adminCommissionWithTaxAmount"></h5>
                                                                </div>
                                                            </div>
                                                            <div class="artist-amount-section">
                                                                <div class="amount-info">
                                                                    <h4><span class="txt-green">{{ __('adminWords.take_home_earnings') }}</span>{{ __('adminWords.after_feesandvat') }}</h4>
                                                                    <p>{{ __('adminWords.this_what_you_earn') }} {{(isset($settings['commission_val']) && !empty($settings['commission_val']) ? $settings['commission_val'] : '0').$sym}} {{ __('adminWords.admin_vat_deducted_to') }}</p>
                                                                </div>
                                                                <div class="amount-total">
                                                                    <h5 class="finalArtistWithdrawalAmount"></h5>
                                                                </div>
                                                            </div>    
                                                        @endif
                                                    </div>
                                                </div>
                                        @else
                                            <input type="hidden" class="checkAdminCommssion" value="0">
                                            {{ __('adminWords.admin').' '.__('adminWords.commission').' = '.$curr.'0' }}
                                            <br>
                                            {{ __('adminWords.withdrawal_amount').' = ' }}
                                            @if(isset($audioData) && !empty($audioData->download_price))
                                                {{ $curr.$audioData->download_price }}
                                            @else
                                                {{ $curr }}<label class="finalArtistWithdrawalAmount">{{ '0' }}</label>
                                            @endif 
                                            
                                        @endif
    
                                    </div>
                                </div>
                                <!-- Audio Download Form End -->                        
                                
                            </div>
                        
                            
                            
                            @if(isset($settings) && !empty($settings['artist_upload_on_s3']) && $settings['artist_upload_on_s3'] == 1) 
                                <div class="form-group mb-0 dd-flex">
                                    <div class="checkbox mr-4">  
                                        {!! Form::checkbox('aws_upload', 1, (isset($audioData) && $audioData->aws_upload == 0 ? 0 : 1),['id'=>'aws_upload']) !!}
                                        {!! Form::label('aws_upload', __('adminWords.aws_upload')) !!}
                                    </div>
                                        <small class="text-danger">{{ $errors->first('aws_upload') }}</small>
                                </div>    
                            @endif
                        </div>

                      <div class="col-lg-8">
                        <div class="form-group"> 
                          @if(!isset($audioData))
                            <button type="reset" class="effect-btn btn btn-danger"> {{ __('adminWords.reset') }}</button>
                          @endif  
                          <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm"> {{isset($audioData) ? __('adminWords.update') : __('adminWords.add') }}</button>  
                        </div>
                        <div class="clear-both"></div>
                      </div>
                    </div>
                    {!! Form::close() !!}
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
@section('script')
<script src="{{ asset('assets/plugins/datepicker/datepicker.min.js') }}"></script> 
<script src="{{asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/datepicker/i18n/datepicker.en.js') }}"></script> 
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script> 
<script src="{{ asset('assets/js/artist-custom.js') }}"></script>  
<script type="text/javascript">
    if ($('.date-calender').length > 0) {
        $('.date-calender').datepicker({
            format: 'dd-mm-yyyy',
            multidate: false,
            todayHighlight: true,
            language: 'en'
        });
    }
</script>

@endsection
