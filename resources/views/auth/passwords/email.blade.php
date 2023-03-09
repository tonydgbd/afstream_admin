@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
    <div id="containerbar" class="containerbar authenticate-bg">
        <div class="container">
            <div class="auth-box forgot-password-box">
                <div class="row no-gutters align-items-center justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-12">
                        <div class="auth-box-right">
                            <div class="card">
                                <div class="card-body">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="form-head">
                                            <a href="{{ url('/') }}"><img src="{{ (isset($settings['logo']) ? asset('public/images/sites/'.$settings['logo']) : '') }}" alt="" class="img-fluid"/></a>
                                        </div> 
                                        <h4 class="auth-title text-center">{{ __('adminWords.forgot_password') }}</h4>
                                        <p class="mb-4">{{ __('adminWords.enter_email_note') }}</p>
                                        <div class="form-group">
                                            <input id="email" type="text" class="form-control required @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="{{ __('adminWords.enter').' '.__('adminWords.email').' '.__('adminWords.address') }}" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>                          
                                      <button type="submit" class="ms_btn btn-lg btn-block font-18">{{ __('adminWords.send_email') }}</button>
                                    </form>
                                    <p class="mb-0 mt-3"> {{ __('adminWords.back_to_login') }} <a href="{{url('/login')}}">{{ __('adminWords.login') }}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
