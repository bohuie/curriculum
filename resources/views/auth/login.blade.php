@extends('layouts.app')

@section('content')

<div class="container" style="padding-bottom:218px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <div id="prompt" style="display: none;">
                        <div class="alert alert-primary d-flex align-items-center ml-3 mr-3" role="alert" style="text-align:justify">
                            <i class="bi bi-info-circle-fill pr-2 fs-3"></i>                        
                            <div>
                                While this tool was created to serve the UBC community, anyone can register and use the site. However, since information such as Ministry-related standards and UBC strategic plans are presented, not all users may benefit from every feature.
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" onchange="notifyNonUBCUser()" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_CAPTCHA_PUBLIC_KEY') }}"></div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
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