@extends('layouts.fe')

@section('body_class', 'body-scroll d-flex flex-column h-100')

@section('content')
<!-- Header -->
<header class="container-fluid header">
    <div class="row h-100">
        <div class="col text-center align-self-center">
            @if (!empty(config('global.company_logo')))
                @php
                    $img_params = [
                        'title' => config('global.site_name') . ' logo'
                    ];
                @endphp
                {{ get_image(config('global.company_logo'), null, 40, $img_params) }}
            @else
            <div class="logo-small">
                <i class="bi bi-shop size-32 mr-1"></i>
                <h6>{{ config('global.site_name') }}<br><small>{{ config('global.tag_line') }}</small></h6>
            </div>
            @endif
        </div>
    </div>
</header>
<!-- Header ends -->

<!-- Begin page content -->
<main class="container-fluid main-container h-100">
    <div class="overlay-image text-end">
        <img src="{{ asset('img/applelogo.png') }}" class="orange-slice" alt="">
    </div>

    <div class="row h-100">
        
        <div class="col-12 mx-auto text-center">
            <div class="row h-100">
                <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                    <h2 class="text-center mb-4">Sign in</h2>
                    <div class="card card-light shadow-sm mb-4">
                        <div class="card-body">
                            @if (Session::has('warning'))
                            <div class="alert alert-danger">
                                {{ Session::get('warning') }}
                            </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="emailaddress" placeholder="Email Anda" 
                                        @if (request('email')) value="{{ request('email') }}" readonly="readonly" @else value="{{ old('email') }}" @endif>
                                    <label for="emailaddress">Email</label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" name="password" class="form-control @error('email') is-invalid @enderror" id="password"
                                        placeholder="Password Anda">
                                    <label for="password">Password</label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">Masuk</button>
                                </div>

                                <div class="d-grid">
                                    @if (Route::has('password.request'))
                                        <a class="link mt-3 mb-1" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>

                                <div class="d-grid">
                                    <div class="form-check">
                                        <input class="form-check-input float-none" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
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