@extends('layouts.app')

@section('content')

<div class="container" style="padding-bottom:60px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <div id="prompt" style="display: none;">
                        <div class="alert alert-primary d-flex align-items-center ml-3 mr-3" role="alert" style="text-align:justify">
                            <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
                            <div>
                                While this tool was created to serve the UBC community, anyone can register and use the site. However, since information such as Ministry-related standards and UBC strategic plans are presented, not all users may benefit from every feature.
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">

                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" onchange="notifyNonUBCUser()" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10 offset-md-4">
                                <label for="terms-of-use" class="col-md-14 col-form-label text-md-right">By registering for the website, you agree to the <a href="/terms" target="_blank" rel="noopener noreferrer">Terms of Use</a>.</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_CAPTCHA_PUBLIC_KEY') }}"></div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                                <small class="form-text text-muted">After submitting this form, a verification email will be sent to the email address you
                                    entered.</small>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    window.addEventListener('load', function() {
        const email = document.getElementById('email');
        var input = email.value;
        if (input.includes("@")) {
            // get user email domain 
            var domainArr = input.split('@');
            var domainStr = domainArr[domainArr.length - 1];
            setPrompt(domainStr);
        }
    });

    // display a message to the user if they are not using a UBC email address
    function notifyNonUBCUser(e) {
        const email = document.getElementById('email');
        var input = email.value;

        if (input.includes("@")) {
            // get user email domain 
            var domainArr = input.split('@');
            var domainStr = domainArr[domainArr.length - 1];
            setPrompt(domainStr);
        }
    }

    // TODO: runs on window.load to check if there is a email present and if the prompt needs to be displayed upon page load
    function setPrompt(domainStr) {
        const ubcDomains = ['ubc.ca', 'mail.ubc.ca', 'alumni.ubc.ca', 'student.ubc.ca'];
        // check if user domain == one of UBS's domains
        if (ubcDomains.includes(domainStr.toLowerCase())) {
            $('#prompt').fadeOut("slow");
        }else {
            $('#prompt').fadeIn("slow");
        }
    }
</script>