@extends('layouts.admin.main')
@section('title', __('adminWords.testimonial'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('rightbar-content')
              
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-7 col-lg-7">
            <h4 class="page-title">{{ __('adminWords.testimonial') }}</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('adminWords.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ __('adminWords.testimonial') }}</a></li>
                </ol>
            </div>
        </div>
        <div class="col-md-5 col-lg-5"> 
            <div class="widgetbar">
                <a href="{{route('testimonial.create')}}" class="btn btn-primary mr-2"><i class="feather icon-plus mr-2"></i>{{ __('adminWords.create').' '.__('adminWords.testimonial') }}</a>
                <button type="button" class="btn btn-danger" id="bulkDelete" data-msg="{{ __('adminWords.atleast').' '.__('adminWords.testimonial').' '.__('adminWords.must_selected') }}" data-url="{{route('testimonial.delete')}}"><i class="far fa-trash-alt mr-2 "></i> {{ __('adminWords.delete_selected') }}</button>  
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
                            <h5 class="card-title mb-0">{{ __('adminWords.all').' '.__('adminWords.testimonial') }}</h5>
                        </div>
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <form method="post" id="testimonailsForm">
                        <table id="datatable-buttons" class="table table-styled musiooDtToShowData" data-url="{{route('testimonialData')}}" data-method="post">
                            <thead>
                                <tr>
                                    <th> 
                                       <div class="inline custom-checkbox">
                                          <input id="checkboxAll" type="checkbox" class="custom-control-input" onchange="checkAll(this, 'CheckBoxes')" name="checked[]" value="all" />
                                          <label for="checkboxAll" class="custom-control-label">{{ __('adminWords.all') }}</label>
                                        </div>
                                    </th>
                                    <th>{{ __('adminWords.image') }}</th>
                                    <th>{{ __('adminWords.client_name') }}</th>
                                    <th>{{ __('adminWords.designation') }}</th>
                                    <th>{{ __('adminWords.rating') }}</th>
                                    <th>{{ __('adminWords.description') }}</th>
                                    <th>{{ __('adminWords.status') }}</th>
                                    <th>{{ __('adminWords.action') }}</th>
                                </tr>
                            </thead>      
                        </table>
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
    <script src="{{ asset('public/assets/js/musioo-custom.js?'.time()) }}"></script>    
@endsection 