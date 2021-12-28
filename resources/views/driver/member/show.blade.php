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
                        <h4 class="mb-2">{{ __('Buyer Information') }} #{{ $member->id }}</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Buyer Information') }}</div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{{ __('Name') }}</td>
                                    <td>{{ $member->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Email') }}</td>
                                    <td>{{ $member->email }}</td>
                                </tr>
                                {{--<tr>
                                    <td>{{ __('Phone') }}</td>
                                    <td>{{ $member->phone }}</td>
                                </tr>--}}
                                <tr>
                                    <td>{{ __('Address') }}</td>
                                    <td>{{ $member->address }}</td>
                                </tr>

                                @if ($member->district_id > 0)
                                <tr>
                                    <td>{{ __('District') }}</td>
                                    <td>{{ $member->district->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('City') }}</td>
                                    <td>{{ $member->district->city->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('District') }}</td>
                                    <td>{{ $member->district->city->province->name }}</td>
                                </tr>
                                @endif
                                
                                <tr>
                                    <td>{{ __('Postal Code') }}</td>
                                    <td>{{ $member->postal_code }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Gender') }}</td>
                                    <td>{{ $member->getGender() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{--
                    <div class="card-footer">
                        <a href="{{ wa_url($member->phone) }}" title="{{ __('Chat Buyer') }}" target="_blank" class="btn btn-outline-success m-1">
                            <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Buyer') }}
                        </a>
                        <a href="{{ phone_url($member->phone) }}" title="{{ __('Call Buyer') }}" target="_blank" class="btn btn-outline-secondary">
                            <i class="bi bi-phone size-22"></i> {{ __('Call Buyer') }}
                        </a>
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection