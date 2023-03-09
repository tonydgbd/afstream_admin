@extends('layouts.admin.main')
@section('title', __('adminWords.users'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />    

@endsection
@section('content')             
            <!-- Main Body -->  
    
            <!-- Page Title Start -->
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="page-title-wrapper">
                        <div class="page-title-box ad-title-box-use">
                            <h4 class="page-title">{{ __('adminWords.all').' '.__('adminWords.users') }}</h4>
                        </div>
                        <div class="ad-breadcrumb">
                            <ul>
                                <li>
									<div class="ad-user-btn">										
									</div>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page Title Start -->
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-6">
                       <h4>{{ __('adminWords.all').' '.__('adminWords.users') }}</h4>
                    </div>
                    <div class="col-6">
                        <div class="btn-right">
                            <a href="{{route('create')}}" class="effect-btn btn btn-primary mt-2 mr-2"><i class="feather icon-plus mr-2"></i>{{ __('adminWords.create').' '.__('adminWords.user') }}</a>
                            <button type="button" class="effect-btn btn btn-danger mt-2 mr-2" id="bulkDelete" data-msg="{{ __('adminWords.atleast').' '.__('adminWords.user').' '.__('adminWords.must_selected') }}" data-url="{{route('bulkDelete')}}"><i class="far fa-trash-alt mr-2 "></i> {{ __('adminWords.delete_selected') }}</button> 
                        </div>
                    </div>                                
                </div>
            </div>

            <!-- Table Start -->
            <div class="row">
                <!-- Styled Table Card-->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card table-card">                         

                        <div class="card-body">
                            <div class="chart-holder">                               

                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">                            
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"> 
                                        <div class="data_tablefilter_option">
                                            <div id="reportrange" class="reportrangefilter">
                                                <i class="fa fa-calendar"></i>&nbsp;
                                                <span></span> <i class="fa fa-caret-down"></i>
                                            </div>                              
                                            <div class="data_tablefilter_btn">
                                                <button type="button" class="effect-btn btn btn-primary multiple_dt_filter" data-tabel="usersDataTables" data-url="{{route('usersData')}}">Filter</button>
                                            </div>
                                        </div>
                                    </div>                
                                </div>  
                               
                                <div class="table-responsive">
                                	<form method="post" id="usersForm">
                                        <table class="table table-styled mb-0 musiooDtToShowData usersDataTables" data-method="post" id="datatable-buttons" data-url="{{route('usersData')}}">
                                            <thead>
                                                <tr> 
													<th class="select-checkbox"> 
														<div class="checkbox danger-check">
															<input id="checkboxAll" type="checkbox" class="selectAllUser" onchange="checkAll(this, 'CheckBoxes')">
															<label for="checkboxAll" class="custom-control-label">{{ __('adminWords.all') }}</label>
														</div>
													</th>
													<th>{{ __('adminWords.image') }}</th>
													<th>{{ __('adminWords.name') }}</th>
													<th>{{ __('adminWords.email') }}</th>
													<th>{{ __('adminWords.plan') }}</th>
													<th>{{ __('adminWords.created_at') }}</th>
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
