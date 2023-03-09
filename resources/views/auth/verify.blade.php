@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('adminWords.verify_email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('adminWords.email_verification_link') }}
                        </div>
                    @endif

                    {{ __('adminWords.check_email_verify') }}
                    {{ __('adminWords.receive_mail') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('adminWords.another_req') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
