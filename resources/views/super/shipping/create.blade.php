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
                        <h4 class="mb-2">{{ __('Create Shipping') }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('super.shippings.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('Shipping List') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Add New Shipping') }}</div>
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
                        <form method="POST" action="{{ route('super.shippings.store') }}" >
                            @method('POST')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="title">{{ __('Title') }} :</label>
                                    <input type="text" class="form-control" id="title" placeholder="Masukkan nama ongkir" name="title" value="{{ old('title') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="distance_from">{{ __('Distance From') }} :</label>
                                    <input type="text" class="form-control" id="distance_from" placeholder="Masukkan jarak dari" name="distance_from" value="{{ old('distance_from') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="distance_to">{{ __('Distance To') }} :</label>
                                    <input type="text" class="form-control" id="distance_to" placeholder="Masukkan jarak hingga" name="distance_to" value="{{ old('distance_to') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="cost">{{ __('Cost') }} :</label>
                                    <input type="text" class="form-control" id="cost" placeholder="Masukkan biaya" name="cost" value="{{ old('cost') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-12">
                                    <label for="description">{{ __('Description') }} :</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Masukkan deskripsi">{{ old('description') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-4">
                                    <label for="enabled">{{ __('Status') }} :</label>
                                    <select name="enabled" class="form-control">
                                        @php
                                            $statuses = [0 => __('Disabled'), 1 => __('Enabled')];
                                        @endphp
                                        @foreach ($statuses as $status_code => $status_name)
                                            <option value="{{ $status_code }}" @if ($status_code == old('enabled')) selected @endif>{{ $status_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">{{ __('Save Now') }}</button>
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
