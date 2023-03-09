@extends('layouts.admin.main')
@section('title', __('adminWords.invoice').' '.__('adminWords.setting') )
@section('content')            


<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.invoice').' '.__('adminWords.setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.invoice').' '.__('adminWords.setting') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="contentbar">  
  <div class="row">
    <div class="col-lg-12">
        <div class="card m-b-30 add-form hide-block">
            <div class="card m-b-30">
                <div class="card-header">                                
                    <div class="row align-items-center">
                        <div class="col-6">
                        <h5 class="card-title mb-0">{{ __('adminWords.invoice').' '.__('adminWords.setting') }}</h5>
                        </div>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                        @php $decodeVal = ''; @endphp

                        @if(sizeof($setting) > 0)
                            {!! Form::model($setting[0], ['method'=>'post', 'route'=>'invoiceDetail', 'id' => 'invoiceSett' ]) !!}
                            @php 
                                $decodeVal = json_decode($setting[0]->invoice_data);
                            @endphp
                        @else
                            {!! Form::open(['method' => 'POST', 'route'=>'invoiceDetail', 'id' => 'invoiceSett']) !!}
                        @endif

                            <div class="col lg-12 col-md-12 col-sm-12 col-xs-12 pl-0">
                                <label class="main_label_heading">{{ __('adminWords.business_address') }}</label>
                            </div>
                            <div class="form-group{{ $errors->has('address1') ? ' has-error' : '' }}">
                                <label for="address1">{{ __('adminWords.address').' 1 ' }}<sup>*</sup></label>
                                {!! Form::text('address1', ($decodeVal != '') ? $decodeVal->address1 : null, ['class' => 'form-control require', 'placeholder' => __('adminWords.enter').' '.__('adminWords.address').' 1 ' ]) !!}
                                <small class="text-danger">{{ $errors->first('address1') }}</small>
                            </div>
                            <div class="form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
                                <label for="address2">{{ __('adminWords.address').' 2 ' }}<sup>*</sup></label>
                                {!! Form::text('address2', ($decodeVal != '') ? $decodeVal->address2 : null, ['class' => 'form-control require', 'placeholder' => __('adminWords.enter').' '.__('adminWords.address').' 2 ' ]) !!}
                                <small class="text-danger">{{ $errors->first('address2') }}</small>
                            </div>
                            <div class="appendTermCond">
                                <div class="col lg-12 col-md-12 col-sm-12 col-xs-12 pl-0">
                                    <label class="main_label_heading">{{ __('adminWords.term_condition') }}</label>
                                </div>
                                <div class="form-group Add_first_child">
                                    <label for="termCond">{{ __('adminWords.term_condition') }}<sup>*</sup></label>
                                    @if($decodeVal != '')
                                        @php 
                                            $explodeTerm = explode(',', $decodeVal->terms);
                                        @endphp
                                        @for( $j=0; $j<sizeof($explodeTerm); $j++)
                                            <div class="form-group">
                                                <input type="text" name="termCond" class="form-control require termCond" placeholder="{{ __('adminWords.enter').' '.__('adminWords.term_condition') }}" value="{{ $explodeTerm[$j] }}" />
                                                <a href="javascript:void(0);" class="addTermCond"><i class="fa fa-plus-circle"></i></a>
                                            @if($j > 0)
                                                <a href="javascript:void(0);" class="deleteCond"><i class="far fa-trash-alt ml-2"></i></a>
                                            @endif
                                            </div>
                                        @endfor
                                        @else
                                            <input type="text" name="termCond" class="form-control require termCond" placeholder = "{{ __('adminWords.enter').' '.__('adminWords.term_condition') }}" value="" />
                                            <a href="javascript:void(0);" class="addTermCond"><i class="fa fa-plus-circle"></i></a>
                                    @endif
                                </div> 
                            </div>

                            <div class="col lg-12 col-md-12 col-sm-12 col-xs-12 pl-0">
                                <label class="main_label_heading">{{ __('adminWords.contact_detail') }}</label>
                            </div>
                            <div class="form-group{{ $errors->has('website_link') ? ' has-error' : '' }}">
                                <label for="website_link">{{ __('adminWords.website_link') }}<sup>*</sup></label>
                                {!! Form::text('website_link', ($decodeVal != '') ? $decodeVal->website_link : null, ['class' => 'form-control require', 'placeholder' => __('adminWords.enter').' '.__('adminWords.website_link') , 'data-valid' => 'url', 'data-error' => __('adminWords.invalid').' '.__('adminWords.url')]) !!}
                                <small class="text-danger">{{ $errors->first('website_link') }}</small>
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email">{{ __('adminWords.email') }}<sup>*</sup></label>
                                {!! Form::text('email', ($decodeVal != '') ? $decodeVal->email : null, ['class' => 'form-control require', 'placeholder' =>  __('adminWords.enter').' '.__('adminWords.email'), 'data-valid' => 'email' ,' data-error' => __('adminWords.invalid').' '.__('adminWords.email') ]) !!}
                                <small class="text-danger">{{ $errors->first('email') }}</small>
                            </div>
                            <div class="form-group{{ $errors->has('contact_number') ? ' has-error' : '' }}">
                                <label for="contact_number">{{ __('adminWords.contact_number') }}<sup>*</sup></label>
                                {!! Form::text('contact_number', ($decodeVal != '') ? $decodeVal->contact_number : null, ['class' => 'form-control require', 'placeholder' => __('adminWords.enter').' '.__('adminWords.contact_number'), 'data-valid' => 'mobile' ,' data-error' => __('adminWords.invalid').' '.__('adminWords.contact_number') ]) !!}
                                <small class="text-danger">{{ $errors->first('contact_number') }}</small>
                            </div> 
                            <hr>
                            <div class="col lg-12 col-md-12 col-sm-12 col-xs-12 pl-0">
                                <label class="main_label_heading">{{ __('adminWords.authorize') }}</label>
                            </div>
                            <div class="form-group{{ $errors->has('author_name') ? ' has-error' : '' }}">
                                <label for="author_name">{{ __('adminWords.author_name') }}<sup>*</sup></label>
                                {!! Form::text('author_name', ($decodeVal != '') ? $decodeVal->author_name : null, ['class' => 'form-control require', 'placeholder' => __('adminWords.enter').' '.__('adminWords.author_name') ]) !!}
                                <small class="text-danger">{{ $errors->first('author_name') }}</small>
                            </div>

                            <div class="img-upload-preview">
                                <div class="row">
                                    <div class="col-md-6"> 
                                        <div class="form-group{{ $errors->has('author_sign') ? ' has-error' : '' }} input-file-block label_full">
                                        <label for="author_sign">{{ __('adminWords.author_sign') }}<sup>*</sup></label>                                        
                                        <label for="author_sign" class="file-upload-wrapper js-labelFile" data-text="Select your file!" data-toggle="tooltip" data-original-title="Author Signature">
                                            {!! Form::file('author_sign', ['class' => 'input-file hide basicImage '.(sizeof($setting) == 0 ? 'require' : '').'', 'id'=>'author_sign', 'data-id'=>'showSign', 'data-label'=>'signLabel', 'data-ext' => "['png', 'jpg', 'jpeg']" ]) !!}
                                            <span class="js-fileName"></span>
                                        </label>
                                        <span class="info" id="signLabel">{{ ($decodeVal != '') ? $decodeVal->author_sign : '' }}</span>
                                        <small class="text-danger">{{ $errors->first('author_sign') }}</small>
                                        <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 150X75 px' }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="image-block site_image_dv">
                                           <img src="" id="showSign" class="img-responsive" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="saveInvoiceFormData" class="effect-btn btn btn-primary">{{ __('adminWords.save_setting_btn') }}</button>
                            <div class="clear-both"></div>
                        {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addSeo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('adminWords.add').' '.__('adminWords.setting') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="addcountry" method="post" action="{{route('seo.add')}}" data-modal="1" data-reset="1">
        <div class="modal-body">
          <div class="form-group">
            <label for="name">{{ __('adminWords.title')}}</label>
            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.setting').' '.__('adminWords.title') }}" name="name" class="form-control require" />
          </div>
          <div class="form-group">
            <label for="value">{{ __('adminWords.value') }}</label>
            <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.setting').' '.__('adminWords.value') }}" name="value" class="form-control require" />
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="effect-btn btn btn-default" data-dismiss="modal">{{ __('adminWords.close') }}</button>
          <button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm">{{ __('adminWords.add') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection 

@section('script')
    <script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>
@endsection