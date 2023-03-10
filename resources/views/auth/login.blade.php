@extends('layouts.app')
@section('title', 'Admin Login')
@section('content') 
<div id="containerbar" class="containerbar authenticate-bg">
    <div class="container">
        <div class="auth-box login-box">
            <div class="row no-gutters align-items-center justify-content-center">
                <div class="col-xl-5 col-lg-6 col-12">
                    <div class="auth-box-right">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ route('login.process') }}">
                                    @csrf
                                    <div class="form-head">
                                        <a href="{{ url('/') }}"><img src="{{ (isset($settings['logo']) ? asset('images/sites/'.$settings['logo']) : '') }}" alt="" class="img-fluid"/></a>
                                    </div>                                        
                                    <h4 class="auth-title text-center">{{ __('adminWords.admin_login') }}</h4>
                                    <div class="form-group">
                                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="{{ __('adminWords.enter').' '.__('adminWords.email').' '.__('adminWords.address') }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{__('adminWords.enter').' '.__('adminWords.password') }}" value="{{ old('password') }}">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-row mb-3">
                                        <div class="col-sm-6">
                                            <div class="custom-control custom-checkbox remember_checkbox text-left">
                                                <label class="custom-control-label font-14" for="remember">{{ __('adminWords.remember_me') }}
                                                    <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>                                
                                        </div>
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('adminWords.forgot_password') }}
                                            </a>
                                        @endif
                                    </div>   
                                    <button type="submit" class="ms_btn btn-lg btn-block font-18">{{ __('adminWords.login') }}</button>                       
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection