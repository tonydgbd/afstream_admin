@extends('layouts.admin.main')
@section('title', __('adminWords.faq'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
            

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.faq') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.faq') }}</li>
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
                            <h5 class="card-title mb-0">{{ __('adminWords.faq') }}</h5>
                        </div>
                        <div class="col-6">
                            <div class="btn-right">
                                <a href="{{url('/addFaq')}}" class="effect-btn btn btn-primary mr-2"><i class="feather icon-plus mr-2"></i>
                                    {{ __('adminWords.create').' '.__('adminWords.faq') }}
                                </a>
                                <button type="button" class="effect-btn btn btn-danger" id="bulkDelete" data-msg="{{ __('adminWords.atleast').' '.__('adminWords.faq').' '.__('adminWords.must_selected') }}" data-url="{{url('/bulkDeleteFaq')}}"><i class="far fa-trash-alt mr-2 "></i> {{__('adminWords.delete_selected')}}</button>  
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <form method="post" id="faqsForm">
                        <table id="datatable-buttons" class="table table-styled musiooDtToShowData" data-url="{{route('show_faq')}}" data-method="post">
                            <thead>
                                <tr>
                                  <th class="select-checkbox"> 
                                    <div class="checkbox danger-check">
                                      <input id="checkboxAll" type="checkbox" class="selectAllUser" onchange="checkAll(this, 'CheckBoxes')">
                                      <label for="checkboxAll">{{ __('adminWords.all') }}</label>
                                    </div>
                                  </th>
                                      
                                  <th>{{ __('adminWords.question') }}</th>
                                  <th>{{ __('adminWords.answer') }}</th>
                                  <th>{{ __('adminWords.status') }}</th>
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
    <script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  
@endsection 