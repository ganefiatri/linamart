@extends('layouts.fe')

@section('body_class', 'body-scroll d-flex flex-column h-100')

@section('content')
<!-- Header -->
<header class="container-fluid header">
    <div class="row h-100">
        <div class="col text-center align-self-center">
            <div class="logo-small">
                <i class="bi bi-shop size-24 mr-1"></i>
                <h6>{{ config('global.site_name') }}<br><small>{{ config('global.tag_line') }}</small></h6>
            </div>
        </div>
    </div>
</header>
<!-- Header ends -->

<main class="container-fluid main-container h-100">
    <div class="row h-100">
        <div class="col-12 mx-auto text-center mt-5">
            <div class="row h-100">
                <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    <h2 class="text-center mb-4">{{ __('Reset Password') }}</h2>
                    <div class="card card-light shadow-sm mb-4">
                        <div class="card-body">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-floating mb-3">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="email">{{ __('E-Mail Address') }}</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="password">{{ __('Password') }}</label>
                                </div>
        
                                <div class="form-floating mb-3">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">{{ __('Reset Password') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
