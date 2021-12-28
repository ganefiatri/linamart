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
                        <h4 class="mb-2">{{ __('View Shipping') }} #{{ $shipping->id }}</h4>
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
                    <div class="card-header color-dark fw-500">{{ __('View Shipping') }}</div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{{ __('Title') }}</td>
                                    <td>{{ $shipping->title }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Distance') }}</td>
                                    <td>{{ $shipping->distance_from }} - {{ $shipping->distance_to }} km</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Cost') }}</td>
                                    <td>{{ to_money_format($shipping->cost) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Description') }}</td>
                                    <td>{{ $shipping->description }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Enabled') }}</td>
                                    <td>{{ ($shipping->enabled > 0)? __('Yes'):__('No') }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Created At') }}</td>
                                    <td>{!! $shipping->created_at !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Last Update') }}</td>
                                    <td>{!! $shipping->updated_at !!}</td>
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