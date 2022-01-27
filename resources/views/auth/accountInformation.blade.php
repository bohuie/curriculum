@extends('layouts.app')

@section('content')
<div class="container" style="padding-bottom:218px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Account Information</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('accountInformation.update') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name:</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$user->name}}" required>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>

                    </form>

                    <div class="form-group row">
                        <div class="text-center">
                            <a class="btn btn-link" href="{{ route('password.request') }}">Reset Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection