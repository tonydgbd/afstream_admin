@section('title') 
Users List
@endsection 
@extends('layouts.admin.main')
@section('style')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('rightbar-content')
    <!-- Start Breadcrumbbar -->                    
<div class="breadcrumbbar">
	<div class="row align-items-center">
		<div class="col-md-7 col-lg-7">
			<h4 class="page-title">Users</h4>
			<div class="breadcrumb-list">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
					<li class="breadcrumb-item"><a href="#">Users</a></li>
				</ol>
			</div>
		</div>
		<div class="col-md-5 col-lg-5">
			<div class="widgetbar">
				<a href="{{route('create')}}" class="btn btn-primary-rgba mr-2"><i class="feather icon-plus mr-2"></i>Create user</a>
				<button type="button" class="btn btn-danger" id="bulkDelete" data-msg="Atleast 1 user must be selected." data-url="{{route('bulkDelete','user')}}"><i class="fa fa-trash"></i> Delete Selected</button>  
			</div>                        
		</div>
	</div>          
</div>
<!-- End Breadcrumbbar -->
<!-- Start Contentbar -->    
<div class="contentbar">  
	<div class="row">
		<div class="col-lg-12"> 
			<div class="card m-b-30">
				<div class="card-header">                                
					<div class="row align-items-center">
						<div class="col-6">
							<h5 class="card-title mb-0">All Users</h5>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<form method="post" id="usersForm">
							<table data-method="post" id="datatable-buttons" class="table table-styled musiooDtToShowData" data-url="{{route('usersData')}}">
								<thead>
									<tr> 
										<th class="select-checkbox"> 
											<div class="inline custom-checkbox">
												<input id="checkboxAll" type="checkbox" class="custom-control-input selectAllUser" onchange="checkAll(this, 'CheckBoxes')">
												<label for="checkboxAll" class="custom-control-label"></label>
											</div>
										</th>
										<th>Image</th>
										<th>Name</th>
										<th>Email</th>
										<th>Role</th>
										<th>Mobile</th>
										<!-- <th>Address</th> -->
										<th>Status</th>
										<th>Action</th>
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


<!-- End Contentbar -->
@endsection
@section('script')

    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>      
    <script src="{{ asset('assets/js/mrcls-custom.js') }}"></script>  
@endsection
