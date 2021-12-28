@extends('layouts.fe')

@section('body_class', 'body-scroll d-flex flex-column h-100')

@section('content')
<!-- Header -->
<header class="container-fluid header">
    <div class="row h-100">
        <div class="col-auto align-self-center">
            <a href="#" class="btn btn-link back-btn text-color-theme">
                <i class="bi bi-arrow-left size-20"></i>
            </a>
        </div>
        <div class="col text-center align-self-center">
            <h5 class="mb-0">{{ __('Reset Password') }}</h5>
        </div>
        <div class="col-auto align-self-center">
            <a href="{{ route('home') }}" class="link text-color-theme">
                <i class="bi bi-house size-22"></i>
            </a>
        </div>
    </div>
</header>
<!-- Header ends -->

<main class="container-fluid main-container h-100">
    <div class="row h-100">
        <div class="col-12 mx-auto text-center mt-5">
            <div class="row h-100">
                <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h4 class="text-center mb-4">Silakan masukkan email Anda</h4>
                    <div class="card card-light shadow-sm mb-4">
                        <div class="card-body">
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="emailaddress" placeholder="Email Anda" value="{{ old('email') }}">
                                    <label for="emailaddress">{{ __('E-Mail Address') }}</label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">{{ __('Send Password Reset Link') }}</button>
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
