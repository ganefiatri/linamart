@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @php
        $admin = Auth::user();
    @endphp
    <!-- Header -->
    <header class="container-fluid header">
        <div class="row h-100">
            <div class="col-auto align-self-center">
                <a href="{{ route(Auth::user()->role .'.home') }}" class="btn btn-link back-btn text-color-theme">
                    <i class="bi bi-arrow-left size-20"></i>
                </a>
            </div>
            <div class="col text-center align-self-center">
                <h5 class="mb-0">My Profile</h5>
            </div>
            <div class="col-auto align-self-center">
                <a href="{{ route(Auth::user()->role .'.home') }}" class="link text-color-theme">
                    <i class="bi bi-house size-22"></i>
                </a>
            </div>
        </div>
    </header>
    <!-- Header ends -->

    <!-- main page content -->
    <div class="main-container container">

        <!-- profile picture -->
        <div class="row  mb-4">
            <div class="col-auto">
                <figure class="avatar avatar-100 rounded mx-auto">
                    <img src="https://via.placeholder.com/100.webp" alt="">
                </figure>
            </div>
            <div class="col align-self-center">
                <h5 class="">{{ $admin->name }}</h5>
            </div>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
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

        <!-- profile information -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">Basic Information</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-light shadow-sm mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('super.updateprofile') }}">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                            value="{{ $admin->name }}" placeholder="Name" id="names">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="names">{{ __('Name') }}</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                            value="{{ $admin->email }}" placeholder="Email" id="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                                <div class="d-grid mt-3">
                                    <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                                        <button type="submit" class="btn btn-lg btn-default shadow-sm w-100">{{ __('Update Profile') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- change password -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Change Password') }}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-light shadow-sm mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('super.password') }}">
                            @csrf
                            <div class="row mt-3 h-100">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-floating  mb-3">
                                        <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror"
                                            value="{{ old('old_password') }}" placeholder="Password Lama" id="old-password">
                                        @error('old_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="old-password">Password Lama</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-floating  mb-3">
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                            value="{{ old('password') }}" placeholder="Password Baru" id="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="new-password">Password Baru</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-floating ">
                                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                            value="{{ old('password_confirmation') }}" placeholder="Confirm Password" id="confirmpassword">
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="confirmpassword">Konfirmasi Password Baru</label>
                                    </div>
                                </div>
    
                                <div class="d-grid mt-3">
                                    <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                                        <button type="submit" class="btn btn-lg btn-default shadow-sm w-100">{{ __('Reset Password') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- main page content ends -->

</main>
<!-- Page ends-->

@endsection
