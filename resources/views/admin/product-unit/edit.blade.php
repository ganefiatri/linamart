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
                        <h4 class="mb-2">{{ __('Update Product Unit') }} #{{ $productUnit->id }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('admin.product-units.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Product Unit') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Update Product Unit') }}</div>
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
                        @if ($isUsed)
                        <div class="alert alert-warning">
                            Unit ini digunakan dalam beberapa produk. Jika Anda mengganti kode-nya maka beberapa produk yang menggunakan
                            kode unit <b>"{{ $productUnit->code }}"</b> akan terkena update.
                        </div>
                        @endif
                        <form method="POST" action="{{ route('admin.product-units.update', $productUnit->id) }}" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="name">{{ __('Name') }} :</label>
                                    <input type="text" class="form-control" id="name" placeholder="Masukkan nama unit, contoh: Kilogram" name="title" value="{{ $productUnit->title }}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="code">{{ __('Code') }} :</label>
                                    <input type="text" class="form-control" id="code" placeholder="Masukkan kode unit, contoh: kg" name="code" value="{{ $productUnit->code }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="content">{{ __('Description') }} :</label>
                                    <textarea class="form-control" id="content" name="description" placeholder="Masukkan deskripsi">{{ $productUnit->description }}</textarea>
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
