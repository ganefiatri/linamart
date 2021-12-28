@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <div class="main-container container">

        @include('layouts.partial._message')

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-center mb-3">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-2">{{ __('Update Admin') }} #{{ $admin->id }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('super.admins.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Admins') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Update Admin') }}</div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('super.admins.update', $admin->id) }}" >
                            @method('PATCH')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="name">{{ __('Name') }} :</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter admin name" name="name" value="{{ $admin->name }}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="email">Email :</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter email address" name="email" value="{{ $admin->email }}" required>
                                </div>
                            </div>
                            <div class="row mb-3 border border-warning px-3 py-4">
                                <div class="alert alert-warning">
                                    Isi inputan Password hanya jika ingin merubah password
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="password">Password Baru :</label>
                                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" value="{{ old('password') }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="password_confirmation">Password Baru (Diulang) :</label>
                                    <input type="password" class="form-control" id="password_confirmation" placeholder="Repeat password" name="password_confirmation">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-4">
                                    <label for="status">{{ __('Status') }} :</label>
                                    <select name="status" class="form-control">
                                        @foreach ($statusList as $status_code => $status_name)
                                            <option value="{{ $status_code }}" @if ($status_code == $admin->status) selected @endif>{{ $status_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">{{ __('Update Now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection
