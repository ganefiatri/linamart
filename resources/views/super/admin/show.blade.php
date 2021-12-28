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
                        <h4 class="mb-2">{{ __('View Admin') }} #{{ $admin->id }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('super.admins.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Admin') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('View Admin') }}</div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{{ __('Name') }}</td>
                                    <td>{{ $admin->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Email') }}</td>
                                    <td>{{ $admin->email }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Status') }}</td>
                                    <td>{{ $admin->getStatus() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Created At') }}</td>
                                    <td>{!! $admin->created_at !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Last Update') }}</td>
                                    <td>{!! $admin->updated_at !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer color-dark fw-500">
                        <form method="POST" action="{{ route('super.loginasadmin', $admin) }}">
                            @csrf
                            <button class="btn btn-default btn-lg shadow-sm" onclick="return jumpConfirmation(this);">
                                {{ __('Login As') }} "{{ $admin->name }}"
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
        if (confirm("{{ __('Are you sure you want to jump as admin?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    </script>
@endsection