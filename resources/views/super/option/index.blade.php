@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    <!-- Header -->
    <header class="container-fluid header">
        <div class="row h-100">
            <div class="col-auto align-self-center">
                <a href="{{ route(Auth::user()->role .'.home') }}" class="btn btn-link back-btn text-color-theme">
                    <i class="bi bi-arrow-left size-20"></i>
                </a>
            </div>
            <div class="col text-center align-self-center">
                <h5 class="mb-0">{{ __('Site Settings') }}</h5>
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

        @include('layouts.partial._message')
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('super.options.update') }}">
            @method('PATCH')
            @csrf

        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('General Setting') }}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-light shadow-sm mb-4">
                    <div class="card-body">
                            <div class="row mt-3">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror" 
                                            value="{{ $options['site_name'] }}" placeholder="Nama situs" id="site_name">
                                        @error('site_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="site_names">{{ __('Site Name') }}</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-8">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="text" name="tag_line" class="form-control @error('tag_line') is-invalid @enderror" 
                                            value="{{ $options['tag_line'] }}" placeholder="Tag Line" id="tag_line">
                                        @error('tag_line')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="tag_line">{{ __('Tag Line') }}</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="text" name="site_description" class="form-control @error('site_description') is-invalid @enderror" 
                                            value="{{ $options['site_description'] }}" placeholder="Tag Line" id="site_description">
                                        @error('site_description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="site_description">{{ __('Site Description') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="email" name="admin_email" class="form-control @error('admin_email') is-invalid @enderror" 
                                            value="{{ $options['admin_email'] }}" placeholder="Email" id="admin_email">

                                        @error('admin_email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="admin_email">{{ __('Admin Email') }}</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="text" name="admin_phone" class="form-control @error('admin_phone') is-invalid @enderror" 
                                            value="{{ $options['admin_phone'] }}" placeholder="Nomor telepon admin" id="admin_phone">

                                        @error('admin_phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="admin_phone">{{ __('Admin Phone') }}</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="text" name="admin_wa" class="form-control @error('admin_wa') is-invalid @enderror" 
                                            value="{{ $options['admin_wa'] }}" placeholder="Nomor wa admin" id="admin_wa">

                                        @error('admin_wa')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="admin_wa">{{ __('Admin WhatsApp') }}</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Invoice Setting') }}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-light shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group form-floating  mb-3">
                                    <input type="text" name="invoice_serie" class="form-control @error('invoice_serie') is-invalid @enderror" 
                                        value="{{ $options['invoice_serie'] }}" placeholder="Prefix/awalan nomor tagihan" id="invoice_serie">

                                    @error('invoice_serie')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="invoice_serie">{{ __('Invoice Prefix') }}</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group form-floating  mb-3">
                                    <input type="text" name="invoice_cn_series" class="form-control @error('invoice_cn_series') is-invalid @enderror" 
                                        value="{{ $options['invoice_cn_series'] }}" placeholder="Prefix/awalan nomor tagihan refund" id="invoice_cn_series">

                                    @error('invoice_cn_series')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="invoice_cn_series">{{ __('Refund Invoice Prefix') }}</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group form-floating  mb-3">
                                    <input type="text" name="invoice_due_days" class="form-control @error('invoice_due_days') is-invalid @enderror" 
                                        value="{{ $options['invoice_due_days'] }}" placeholder="Tagihan berlaku hingga" id="invoice_due_days">

                                    @error('invoice_due_days')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="invoice_due_days">{{ __('Invoice Due Days') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Other Settings') }}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-light shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group form-floating  mb-3">
                                    <select name="language"  class="form-control @error('language') is-invalid @enderror">
                                        <option value="id" @if($options['language'] == 'id') selected="selected" @endif>Bahasa Indonesia</option>
                                        <option value="en" @if($options['language'] == 'en') selected="selected" @endif>English</option>
                                    </select>
                                    @error('language')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="language">{{ __('Language') }}</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group form-floating  mb-3">
                                    <select name="timezone"  class="form-control @error('timezone') is-invalid @enderror">
                                        <option value="Asia/Jakarta" @if($options['timezone'] == 'Asia/Jakarta') selected="selected" @endif>Asia/Jakarta</option>
                                    </select>
                                    @error('timezone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="timezone">{{ __('Timezone') }}</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="form-group form-floating  mb-3">
                                    <input type="text" name="meta_robots" class="form-control @error('meta_robots') is-invalid @enderror" 
                                        value="{{ $options['meta_robots'] }}" placeholder="Meta Robots" id="meta_robots">

                                    @error('meta_robots')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="meta_robots">{{ __('Meta Robots') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid mt-3">
            <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                <button type="submit" class="btn btn-lg btn-default shadow-sm w-100">{{ __('Update Now') }}</button>
            </div>
        </div>
        </form>

    </div>
    <!-- main page content ends -->

</main>
<!-- Page ends-->

@endsection
