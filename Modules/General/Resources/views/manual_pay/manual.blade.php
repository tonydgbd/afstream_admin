@extends('layouts.admin.main')
@section('title', __('adminWords.manual_pay'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('rightbar-content')

<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-7 col-lg-7">
            <h4 class="page-title">{{ __('adminWords.manual_pay') }}</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('adminWords.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ __('adminWords.manual_pay') }}</a></li>
                </ol>
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
                            <h5 class="card-title mb-0">{{ __('adminWords.manual_pay') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                      <form method="post" id="manualPayForm">
                        <table id="datatable-buttons" class="table table-styled musiooDtToShowData" data-url="{{route('manualPayData')}}" data-method="post">
                            <thead>
                                <tr>
                                    <th>{{ __('adminWords.id') }}</th>
                                    <th>{{ __('adminWords.payment_proof') }}</th>
                                    <th>{{ __('adminWords.order_id') }}</th>
                                    <th>{{ __('adminWords.user_name') }}</th>
                                    <th>{{ __('adminWords.plan_name') }}</th>
                                    <th>{{ __('adminWords.amount') }}</th>
                                    <th>{{ __('adminWords.purchased_at') }}</th>  
                                    <th>{{ __('adminWords.status') }}</th>   
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

<div class="modal fade" id="payment_proof_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <img src="" />
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