@extends('layouts.admin.main')
@section('rightbar-content')               
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">{{ __('adminWords.users_profile') }}</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">{{ __('adminWords.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ __('adminWords.users_profile') }}</a></li>
                </ol>
            </div>
        </div>
    </div>          
</div>

<div class="contentbar">                
    <div class="row">
        <div class="col-lg-10">
            <div class="card m-b-30">
                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-lg-3 text-center">
                            <img src="{{ !empty($reply->users->image) ? asset('public/images/user/'. Auth::user()->image) : asset('assets/images/users/boy.svg' }}" class="img-fluid mb-3" alt="user">
                        </div>
                        
                        <div class="col-lg-6">
                            <h4>{{$user->name}}</h4>
                            
                            <div class="table-responsive">
                                <table class="table table-styled mb-0">
                                    <tbody>
                                       @if(!empty($user->mobile))
                                        <tr>
                                            <th scope="row" class="p-1">Mobile No :</th>
                                            <td class="p-1">{{$user->mobile}}</td>
                                        </tr>
                                        @endif
                                         @if(!empty($user->email))
                                         <tr>
                                            <th scope="row" class="p-1">Email :</th>
                                            <td class="p-1">{{Auth::user()->email}}</td>
                                            
                                        </tr>
                                        @endif
                                        @if(!empty($user->gender))
                                        <tr>
                                            <th scope="row" class="p-1">Gender :</th>
                                            <td class="p-1">{{$user->gender == '0' ? 'Male' : 'Female'}}</td>
                                        </tr>
                                        @endif
                                         @if(!empty($user->dob))
                                        <tr>
                                            <th scope="row" class="p-1">Date Of Birth :</th>
                                            <td class="p-1">{{date('d M Y', strtotime($user->dob))}}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($user->address))
                                        <tr>
                                            <th scope="row" class="p-1">Address :</th>
                                            <td class="p-1">{{$user->address}} 
                                            @if(!empty($user->city_id)){{','.$user->city->name}}@endif 
                                            @if(!empty($user->state_id)) {{','.$user->state->name}}@endif 
                                            @if(!empty($user->country_id)){{','.$user->Country->name}}@endif 
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-3 ">
                        <a href="edit/{{Auth::user()->id}}" class="btn btn-primary">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 