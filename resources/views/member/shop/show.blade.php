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
                        <h4 class="mb-2">{{ __('View Shop') }} #{{ $shop->id }}</h4>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12 col-lg-6 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-header color-dark fw-500">{{ __('Shop Information') }}</div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>{{ __('Name') }}</td>
                                            <td>{{ $shop->name }}</td>
                                        </tr>
                                        @if ($shop->member_id > 0)
                                        <tr>
                                            <td>{{ __('Owner') }}</td>
                                            <td>{{ $shop->member->name }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td>{{ __('Phone') }}</td>
                                            <td>{{ $shop->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Address') }}</td>
                                            <td>{{ $shop->address }}</td>
                                        </tr>
                                        @if ($shop->district_id > 0)
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
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ wa_url($shop->phone) }}" title="{{ __('Chat Shop') }}" target="_blank" class="btn btn-outline-success m-1">
                                    <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Shop') }}
                                </a>
                                <a href="{{ phone_url($shop->phone) }}" title="{{ __('Call Shop') }}" target="_blank" class="btn btn-outline-secondary">
                                    <i class="bi bi-phone size-22"></i> {{ __('Call Shop') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($shop->member)
                    <div class="col-12 col-lg-6 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-header color-dark fw-500">{{ __('Shop Owner Information') }}</div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>{{ __('Name') }}</td>
                                            <td>{{ $shop->member->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Email') }}</td>
                                            <td>{{ $shop->member->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Phone') }}</td>
                                            <td>{{ $shop->member->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Address') }}</td>
                                            <td>{{ $shop->member->address }}</td>
                                        </tr>
        
                                        @if (is_using_district())
                                            @if ($shop->member->district_id > 0)
                                            <tr>
                                                <td>{{ __('District') }}</td>
                                                <td>{{ $shop->member->district->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('City') }}</td>
                                                <td>{{ $shop->member->district->city->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('District') }}</td>
                                                <td>{{ $shop->member->district->city->province->name }}</td>
                                            </tr>
                                            @endif
                                        @else
                                        <tr>
                                            <td>{{ __('District City') }}</td>
                                            <td>{{ $shop->district_name ?? '-' }}</td>
                                        </tr>
                                        @endif
                                        
                                        <tr>
                                            <td>{{ __('Postal Code') }}</td>
                                            <td>{{ $shop->member->postal_code }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Gender') }}</td>
                                            <td>{{ $shop->member->getGender() }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection