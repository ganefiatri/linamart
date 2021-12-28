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
                        <h4 class="mb-2">{{ __('View Product Unit') }} #{{ $productUnit->id }}</h4>
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
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{{ __('Name') }}</td>
                                    <td>{{ $productUnit->title }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Code') }}</td>
                                    <td>{{ $productUnit->code }}</td>
                                </tr>
                                @if ($productUnit->description)
                                <tr>
                                    <td>{{ __('Description') }}</td>
                                    <td>{{ $productUnit->description }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ __('Created At') }}</td>
                                    <td>{!! $productUnit->created_at !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Last Update') }}</td>
                                    <td>{!! $productUnit->updated_at !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection