@component('mail::message')
# {{ $app_name }}

<p> Hello {{ $name }}, </p>
<span> Welcome to the {{ $app_name }}. </span><br>

<span> Below are your reset password OTP : </span><br>
{{-- <span> Url : <a href="{{ $url }}">{{ $url }}</a> </span><br> --}}
<span> Email : {{ $email }} </span><br>
<span> Otp : {{ $otp }} </span><br>

Thanks,<br>
{{ $app_name }}
@endcomponent
