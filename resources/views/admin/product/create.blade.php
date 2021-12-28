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
                        <h4 class="mb-2">{{ __('Create Product') }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Product') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Add New Product') }}</div>
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
                        <form method="POST" action="{{ route('admin.products.store') }}" >
                            @method('POST')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="name">{{ __('Product Name') }} :</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Product Name" name="title" value="{{ old('title') }}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="content">{{ __('Category') }} :</label>
                                    <select name="category_id" class="form-control">
                                    @foreach ($category_list as $category_id => $category_name)
                                        <option value="{{ $category_id }}" @if ($category_id == old('category_id')) selected="selected" @endif>{{ $category_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-12">
                                    <label for="content">{{ __('Description') }} :</label>
                                    <textarea class="form-control" id="content" name="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="price">{{ __('Price') }} :</label>
                                    <input type="text" class="form-control" id="price" placeholder="Enter product price" name="price" value="{{ old('price') }}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="discount">{{ __('Discount') }} :</label>
                                    <input type="text" class="form-control" id="discount" placeholder="Enter Product discount" name="discount" value="{{ old('discount') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="stock">{{ __('Stock') }} :</label>
                                    <input type="text" class="form-control" id="stock" placeholder="Enter product stock" name="stock" value="{{ old('stock') }}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="weight">{{ __('Weight') }} :</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="weight" placeholder="Enter Product weight" name="weight" value="{{ old('weight') }}">
                                        @php
                                            $units = get_product_units();
                                        @endphp
                                        <select class="form-select" name="unit" aria-label="Example select with button addon">
                                            @foreach ($units as $unit => $unit_name)
                                                <option value="{{ $unit }}" @if ($unit == old('unit')) selected="selected" @endif>{{ $unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="content">Status :</label>
                                    <select name="active" class="form-control">
                                    @foreach ($status_list as $status_id => $status_name)
                                        <option value="{{ $status_id }}" @if ($status_id == old('active')) selected="selected" @endif>{{ $status_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="content">&nbsp;</label>
                                                <div class="checkbox-theme-default custom-checkbox ">
                                                   <input class="checkbox" type="checkbox" id="check-un3" name="enabled" @if (old('enabled') > 0) checked @endif>
                                                   <label for="check-un3">
                                                   <span class="checkbox-text">
                                                    {{ __('Enable') }}
                                                   </span>
                                                   </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="content">&nbsp;</label>
                                                <div class="checkbox-theme-default custom-checkbox ">
                                                   <input class="checkbox" type="checkbox" id="check-un4" name="hidden" @if (old('hidden') > 0) checked @endif>
                                                   <label for="check-un4">
                                                   <span class="checkbox-text">
                                                    {{ __('Hidden') }}
                                                   </span>
                                                   </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="shop_id" value="{{ $shop_id }}"/>
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