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
                        <h4 class="mb-2">{{ __('My Shop') }}</h4>
                    </div>
                </div>

                @if ($shop)
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-6">
                        <a href="{{ route('shops.edit', $shop) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil-square"></i> {{ __('Update Shop') }}
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-6">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-success float-end">
                            <i class="bi bi-list-ul"></i> {{ __('List Products') }}
                        </a>
                    </div>
                </div>
                @endif

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Shop Information') }}</div>
                    <div class="card-body p-2">
                        @if ($shop)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{{ __('Name') }}</td>
                                    <td>{{ $shop->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Slug') }}</td>
                                    <td>{{ $shop->slug }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Phone') }}</td>
                                    <td>{{ $shop->phone }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Address') }}</td>
                                    <td>{{ $shop->address }}</td>
                                </tr>
                                @if ($shop->district_id > 0 && is_using_district())
                                <tr>
                                    <td>{{ __('District') }}</td>
                                    <td>{{ $shop->district->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('City') }}</td>
                                    <td>{{ $shop->district->city->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Province') }}</td>
                                    <td>{{ $shop->district->city->province->name }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td>{{ __('District City') }}</td>
                                    <td>{{ get_district_value($shop) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ __('Postal Code') }}</td>
                                    <td>{{ $shop->postal_code }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Status') }}</td>
                                    <td>{{ $shop->getStatus() }}</td>
                                </tr>
                                @if ($shop->meta)
                                    @foreach ($shop->meta as $metaName => $metaValue)
                                    <tr>
                                        <td>@if ($metaName == 'daily_open') {{ __('Daily Open') }} @else {{ ucfirst($metaName) }} @endif</td>
                                        @if ($metaName == 'daily_open' && is_array($metaValue))
                                            <td>
                                            <ul>
                                            @foreach ($metaValue as $d => $item)
                                                <li>{{ trans($item['day_name']) }} ({{ $item['open'] }} - {{ $item['closed'] }})</li>
                                            @endforeach
                                            </ul>
                                            </td>
                                        @else 
                                        <td>{{ (!is_array($metaValue)) ? $metaValue : json_encode($metaValue) }}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td>{{ __('Created At') }}</td>
                                    <td>{!! date("d M Y H:i", strtotime($shop->created_at)) !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Last Update') }}</td>
                                    <td>{!! date("d M Y H:i", strtotime($shop->updated_at)) !!}</td>
                                </tr>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            {{ __('You dont have shop at the moment. Lets create new one!') }}<br/>
                            <a href="{{ route('shops.create') }}" class="btn btn-lg btn-default shadow-sm mt-3">{{ __('Create Shop Now') }}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection