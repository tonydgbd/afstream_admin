@extends('layouts.admin.main')
@section('title', __('adminWords.comment'))
@section('style')
    <link href="{{ asset('public/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('public/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
                   
<!-- Page Title Start -->
    <div class="row">
        <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-title-wrapper">
                <div class="page-title-box">
                    <h4 class="page-title bold">
                        {{ __('adminWords.comment') }}
                    </h4>
                </div>
                <div class="musioo-brdcrmb breadcrumb-list">
                    <ul>
                        <li class="breadcrumb-link">
                            <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                        </li>
                        <li class="breadcrumb-link active">{{ __('adminWords.comment') }}</li>
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
                            <h5 class="card-title mb-0">{{ ($type == 'blog') ? $blog_name : $audio_name }}</h5>
                        </div>
                        <div class="col-6">
                            <div class="btn-right">
                                <button type="button" class="btn btn-danger" id="bulkDelete" data-msg="{{__('adminWords.atleast').' '.__('adminWords.comment').' '.__('adminWords.must_selected')}}" data-url="{{route('comment.delete')}}"><i class="far fa-trash-alt mr-2 "></i> {{ __('adminWords.delete_selected') }}</button>  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                      <form method="post" id="commentDataForm">
                        <table id="datatable-buttons" class="table table-styled musiooDtToShowData" data-url="{{url('commentData/'.$type.'/'.($type == 'blog' ? $blog_id : $audio_id))}}" data-method="post">
                            <thead>
                                <tr>
                                    <th class="select-checkbox"> 
                                      <div class="checkbox danger-check">
                                        <input id="checkboxAll" type="checkbox" class="selectAllUser" onchange="checkAll(this, 'CheckBoxes')">
                                        <label for="checkboxAll">{{ __('adminWords.select').' '.__('adminWords.all') }}</label>
                                      </div>
                                    </th>
                                    <th>{{ __('adminWords.user_name') }}</th>
                                    <th>{{ __('adminWords.comment') }}</th>  
                                    <th>{{ __('adminWords.comment_at') }}</th>  
                                    <th>{{ __('adminWords.show_on_dashboard') }}</th>   
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


<div class="modal fade" id="addReply">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ __('adminWords.add').' '.__('adminWords.reply') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="add_reply" method="post" onsubmit="return false" action="{{ url('comment/reply/') }}" data-modal="1" table-reload="musiooDtToShowData">
        <div class="modal-body">
			<div class="form-group">
				<label for="replyBox">{{ __('adminWords.reply') }}<sup>*</sup></label>
        <textarea name="reply" placeholder="{{ __('adminWords.enter').' '.__('adminWords.reply') }}" class="form-control require" rows="3" id="replyBox"></textarea>
			</div>
		</div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('adminWords.close') }}</button>
          <button type="button" class="btn btn-primary" data-action="submitThisForm">{{ __('adminWords.reply') }}</button>
        </div>
      </form>
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