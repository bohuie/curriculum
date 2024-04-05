@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center" style="padding-bottom:265px;">
        <div class="col-md-8">
            @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
            @elseif (!session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('An email has been sent to you for verification. Please be sure to check your spam/junk folder.') }}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>
                <div class="card-body">
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
