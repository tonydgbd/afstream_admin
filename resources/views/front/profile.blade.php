@extends('layouts.front.main')
@section('title', __('frontWords.profile'))
@section('content')

@php
    $planData = \App\UserPurchasedPlan::where([ ['expiry_date', '>=', date('Y-m-d')] ])->orderBy('id', 'desc')->limit(1)->first();
    if(!empty($user)){
@endphp
<div class="ms_artist_wrapper common_pages_space">
    <div class="ms_profile_wrapper">
        <div class="slider_heading_wrap">
            <div class="slider_cheading">
                <h4 class="cheading_title">{{ __('frontWords.edit_profile') }} &nbsp;</h4>
            </div>
        </div>
        <div class="ms_prodile_form"> 
            <form action="{{url('update_profile')}}" method="post" id="updateUserForm" enctype="multipart/form-data" data-redirect="{{url('/user/profile') }}">
                <div class="ms_pro_img">
                    <img src="{{ ($user->image != '' ? asset('images/user/'.$user->image) : asset('assets/images/users/profile.svg')) }}" alt="" id="showuserProfileImage" class="img-fluid">
                    <label class="pro_img_overlay">
                        <input class="form-control pz_filed_file" name="user_image" data-dimension="500x500" data-ext="['jpg','jpeg','png']"  data-image="Image can not be blank." type="file" id="basicImage" data-id="showuserProfileImage">
                        <i class="fa_icon edit_icon"></i>
                    </label>
                </div>
                <div class="ms_pro_form">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                             <div class="form-group">
                                <label>{{ __('adminWords.name') }}*</label>
                                <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.name') }}" id="user_name" name="user_name" class="form-control" value="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label>{{ __('adminWords.email') }} *</label>
                                <input type="text" placeholder="{{ __('adminWords.enter').' '.__('adminWords.email') }}" id="user_email" class="form-control" value="{{ $user->email }}" readonly>
                            </div>
                        </div>  
                        @if(!empty($userPlan) && !empty($planData))
                           
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label>{{ __('adminWords.plan_name') }} </label>
                                    <label class="form-control plan_value" style="color:#63858e;">{{ $userPlan->plan_name }}</label>
                                   
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label>{{ __('adminWords.expiry_date') }}</label>
                                    <label class="form-control plan_value" style="color:#63858e;">{{ date('d-m-Y', strtotime($planData->expiry_date)) }}</label>
                                </div>
                            </div>
                        @else
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label>{{ __('adminWords.plan_name') }} </label>
                                    <label class="form-control plan_value" style="color:#63858e;">{{ __('frontWords.no_plan_err') }}</label>
                                   
                                </div>
                            </div>
                        @endif
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                             <div class="form-group">
                                    <a href="javascript:;" id="change_pass" class="ms_btn">{{ __('adminWords.change_pass') }}</a>
                                </div>
                                <div class="change_password_slide hide ">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>{{ __('adminWords.new_pass') }} *</label>
                                        <input type="password" placeholder="******" class="form-control" id="userPassword" name="user_password" length="6" data-length-error="{{ __('frontWords.pass_length') }}">
                                    </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>{{ __('adminWords.confirm_password') }} *</label>
                                        <input type="password" placeholder="******" class="form-control" id="confirmPassword" name="confirm_password" data-match="userPassword" data-error="{{ __('adminWords.cnf_not_match') }}">
                                    </div>
                                    </div>
                                    </div>
                                </div>
                        </div>
                        
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="pro-form-btn text-center marger_top15" style="margin-right:30px;">
                                <button type="button" class="ms_btn" data-action="submitThisForm" >{{ __('adminWords.save') }}</button>
                            </div>
                            <div class="pro-form-btn text-center marger_top15" style="display:none;">
                                <button type="button" class="ms_btn deleteAccountPermanent" data-id="{{ auth()->user()->id }}">
                                    {{ __('frontWords.delete_account') }}
                                </button> 
                            </div>
                        </div>
                    </div>
                   
                </div>
            </form>
        </div>
    </div>
    @include('layouts.front.footer')
</div>

@php
    }
@endphp
@endsection 
