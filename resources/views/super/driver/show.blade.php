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
                        <h4 class="mb-2">{{ __('View Driver') }} #{{ $driver->id }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('super.drivers.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('Driver List') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('View Driver') }}</div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{{ __('Name') }}</td>
                                    <td>{{ $driver->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Email') }}</td>
                                    <td>{{ $driver->email }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Phone') }}</td>
                                    <td>{{ $driver->phone }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Address') }}</td>
                                    <td>{{ $driver->address }}</td>
                                </tr>
                                @if ($driver->district_id > 0 && is_using_district())
                                    <tr>
                                        <td>{{ __('District') }}</td>
                                        <td>{{ $driver->district->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('City') }}</td>
                                        <td>{{ $driver->district->city->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('District') }}</td>
                                        <td>{{ $driver->district->city->province->name }}</td>
                                    </tr>
                                @else
                                <tr>
                                    <td>{{ __('District City') }}</td>
                                    <td>{{ get_district_value($driver) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ __('Postal Code') }}</td>
                                    <td>{{ $driver->postal_code }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Gender') }}</td>
                                    <td>{{ $driver->getGender() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Created At') }}</td>
                                    <td>{!! $driver->created_at !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Last Update') }}</td>
                                    <td>{!! $driver->updated_at !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer color-dark fw-500">
                        <form method="POST" action="{{ route('super.loginasdriver', $driver) }}">
                            @csrf
                            <button class="btn btn-default btn-lg shadow-sm" onclick="return jumpConfirmation(this);">
                                {{ __('Login As') }} "{{ $driver->name }}"
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection

@section('js')
    <script type="text/javascript">
    function jumpConfirmation(dt)
    {
        if (confirm("{{ __('Are you sure you want to jump as driver?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    </script>
@endsection