@extends('layouts.fe')

@section('content')
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <div class="main-container container">
        <div class="col-12 mx-auto text-center mt-5">
            <div class="row h-100">
                <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                    <h2 class="text-center mb-4">{{ __('Confirm Password') }}</h2>
                    <div class="card card-light shadow-sm mb-4">
                        <div class="card-body">
                            <div class="alert alert-info">
                                {{ __('Please confirm your password before continuing.') }}
                            </div>
        
                            <form method="POST" action="{{ route('password.confirm') }}">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="password">{{ __('Password') }}</label>
                                </div>
        
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Confirm Password') }}
                                    </button>
    
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
